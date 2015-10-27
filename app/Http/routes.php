<?php

use PhpOffice\PhpWord\IOFactory;

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
    get('/template/{id}', ['uses' => 'TemplatesController@detail']);
    get('/template/convert', ['uses' => 'TemplatesController@convert']);
});

Route::group(['prefix' => 'api', 'namespace' => 'API'], function() {

    /*Route::controller('auth', 'AuthenticateController', [
      'getLogin' => 'auth.login',
      'getRegister'   => 'auth.register',
      'postLogin' => 'auth.login',
      'postLoginWithLinkedin' => 'auth.linkedin',
    ]);*/
    get('auth/login', [
      'as' => 'auth.login', 
      'uses' => 'AuthenticateController@getLogin'
    ]);
    get('auth/register', [
      'as' => 'auth.register', 
      'uses' => 'AuthenticateController@getRegister'
    ]);
    post('auth/register', [
      'as' => 'auth.register', 
      'uses' => 'AuthenticateController@postRegister'
    ]);
    post('auth/login', [
      'as' => 'auth.login', 
      'uses' => 'AuthenticateController@postLogin'
    ]);
    Route::any('auth/login-with-linkedin', 
      ['as' => 'auth.linkedin', 
      'uses' => 'AuthenticateController@postLoginWithLinkedin']);

    /**
     * User Route
     */
    get('user/profile', 'UsersController@getProfile');
    get('user/template', ['uses' => 'UsersController@getTemplates']);
    get('user/template/market', ['uses' => 'UsersController@getAllTemplatesFromMarket']);
    get('user/template/{id}', ['uses' => 'UsersController@getDetailTemplate']);

    post('user/template', ['uses' => 'UsersController@postTemplates']);
    post('user/{id}/profile', ['uses' => 'UsersController@postProfile']);
    post('user/upload', ['uses' => 'UsersController@uploadImage']);

    /**
     * Market Route
     */
    get('market/all-template', ['uses' => 'MarketPlaceController@getAllTemplateMarket']);
    get('market/detail-template/{id}', ['uses' => 'MarketPlaceController@getDetailTemplateMarket']);
});
