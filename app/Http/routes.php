<?php

use PhpOffice\PhpWord\IOFactory;
use RobbieP\CloudConvertLaravel\Facades\CloudConvert;

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
Route::pattern('id', '[0-9]+');

Route::get('/', function () {
  \Auth::loginUsingId(2);
  // \Auth::logout();
    return view('welcome');
});


Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'role:admin|member'], function() {
  get('/', 'DashBoardsController@index');
});


Route::group(['namespace' => 'Frontend'], function() {

});

Route::group(['prefix' => 'api', 'namespace' => 'API'], function() {
    Route::controller('auth', 'AuthenticateController', [
      'getLogin' => 'auth.login',
      'getRegister'   => 'auth.register',
      'postLogin' => 'auth.login',
      'getLoginWithLinkedin' => 'auth.linkedin',
    ]);
    get('/user/profile', 'UsersController@getProfile');
    post('/user/{id}/profile', ['uses' => 'UsersController@postProfile']);
});

get('/docs', function() {
 /* $phpWord = IOFactory::load(public_path('test.docx'));
  $objWriter = IOFactory::createWriter($phpWord, 'HTML');
  $objWriter->save('test.html');
  dd($objWriter);*/
  CloudConvert::file(public_path('test.docx'))->to(public_path('test.html'));
  return "done";
});