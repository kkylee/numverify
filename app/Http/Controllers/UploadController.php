<?php

namespace App\Http\Controllers;

use  App\Services\Validators\NumVerify;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Model\Storage;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class UploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function upload(Request $request)
    {
        try{
            if($request->hasFile('file') && $request->file('file')->getClientOriginalExtension() == 'xlsx'){
                //empty the table first
                $Storage = new Storage;
                $Storage->truncate();

                $data = [];
                $country = $request->input('country');
                $destinationPath = public_path().'/file'; // upload path
                $extension = $request->file('file')->getClientOriginalExtension(); // getting file extension
                $filename  = str_random(5).'.'.$extension; // give a name to the image
                $request->file('file')->move($destinationPath, $filename); //save file
                $fullPathName = $destinationPath.'/'.$filename; //get the full path and file name

                //read and convert to array
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $objPHPExcel = $reader->load($fullPathName);
                $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
                foreach($rowIterator as $row){
                    $cellIterator = $row->getCellIterator();
                    foreach ($cellIterator as $cell) {
                        $data[$row->getRowIndex()][$cell->getColumn()] = $cell->getCalculatedValue();
                    }
                }

                $NumVerify = new NumVerify;
                //save to database
                foreach ($data as $key => $item) {
                    if($key == 1){
                        continue; //skip header
                    }
                    $Storage->insert(['name'=>$item['A'], 'number' => $item['B']]);
                }

                //validate items
                $result = $Storage->get();
                foreach ($result as $key => $val) {
                    if($NumVerify->singleCheck($val->number,$NumVerify->getCurrencyCode($country))){
                        $val->result = 'Valid';
                    }else{
                        $val->result = 'Invalid';
                    }
                }

                // build xlsx file
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                //header
                $sheet->setCellValue('A1','Name');
                $sheet->setCellValue('B1','Number');
                $sheet->setCellValue('C1','Result');

                //start in row 2
                $row = 2;
                foreach ($result as $key => $value) {
                    $sheet->setCellValue('A'.$row,$value->name);
                    $sheet->setCellValue('B'.$row,$value->number);
                    $sheet->setCellValue('C'.$row,$value->result);
                    $row++;
                }
                $writer = new Xlsx($spreadsheet);

                //save xlsx file
                $file_name = str_random(5).'.'.$extension;
                $writer->save(public_path().'/verified/'.$file_name);

                // delete the uploaded file and data in database
                unlink($fullPathName);
                $Storage->truncate();

                //return status and upload button
                return back()->with('status',$file_name);
            }else{
                return back()->with('error','Invalid file format! Please insert (.xlsx).');
            }

        }catch(Exception $e){
            return back()->with('error','Invalid file format! Please insert (.xlsx).');
        }
    }

    public function download($name)
    {
        //download
        return response()->download(public_path().'/verified/'.$name);
    }

}
