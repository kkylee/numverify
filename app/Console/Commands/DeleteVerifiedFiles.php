<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class DeleteVerifiedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete verified files under public/verified';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $date = strtotime("-7 day", date("Y/m/d"));
        $date = date("Y/m/d",$date);
        $manuals = [];
        $filesInFolder = File::files(public_path().'/verified');
        foreach($filesInFolder as $path)
        {
            $manuals[] = pathinfo($path);
        }

        for ($i=0; $i < count($manuals); $i++) { 
            $timestamp = File::lastModified($manuals[$i]["dirname"]);
            $timestamp = date("Y/m/d", $timestamp);
            if($timestamp == $date){
                File::delete($manuals[$i]["dirname"].'/'.$manuals[$i]["basename"]);
            }
        }

       
    }
}
