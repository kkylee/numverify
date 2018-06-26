<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();
Route::get('/', function () {
    return redirect('/login');
});

Route::post('/auth',['uses'=> 'AuthController@authenticate','as'=>'login']);



//auth
Route::group(array('before' => 'auth'), function()
{
	Route::get('/', function()
    {
       return redirect()->route('home');
    });

	Route::get('/home',['uses'=> 'HomeController@index','as'=>'home']);
	Route::post('/upload',['uses'=> 'UploadController@upload','as'=>'upload']);
	Route::get('/download/{name}',['uses'=> 'UploadController@download','as'=>'download']);
});

