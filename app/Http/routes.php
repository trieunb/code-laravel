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
Route::pattern('id', '[0-9]+');

Route::get('/', function () {
    return view('welcome');
});

Route::group(['namespace' => 'App\Http\Controllers\Admin'], function() {

});


Route::group(['namespace' => 'App\Http\Controllers\Frontend'], function() {

});

Route::group(['prefix' => 'api'], function() {
    Route::controller('auth', 'API\AuthenticateController', [
      'getLogin' => 'auth.login',
      'getRegister' => 'auth.register',
      'postLogin' => 'auth.login',
      'getLoginWithLinkedin' => 'auth.linkedin'
    ]);
});