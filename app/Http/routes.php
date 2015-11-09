<?php


use App\Helper\BainTreeSKD;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
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
get('/', function() {
    return view('welcome');
});
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'role:admin|member'], function() {
    get('/', 'DashBoardsController@index');
});


Route::group(['namespace' => 'Frontend'], function() {
    /**
     * Template Route
     */
    get('template/create', ['as' => 'frontend.template.get.create', 'uses' => 'TemplatesController@create']);
    get('template/{id}', ['uses' => 'TemplatesController@detail']);
    get('template/convert', ['uses' => 'TemplatesController@convert']);

    post('template/create', ['as' => 'frontend.template.post.create', 'uses' => 'TemplatesController@postCreate']);
});

Route::group(['prefix' => 'api', 'namespace' => 'API'], function() {
    /**
     * Authenticate Route
     */
    get('auth/login', ['as' => 'auth.login', 'uses' => 'AuthenticatesController@getLogin']);
    get('auth/register', ['as' => 'auth.register', 'uses' => 'AuthenticatesController@getRegister']);

    post('auth/register', ['as' => 'auth.register', 'uses' => 'AuthenticatesController@postRegister']);
    post('auth/login', ['as' => 'auth.login', 'uses' => 'AuthenticatesController@postLogin']);
    post('auth/reset-password', ['uses' => 'AuthenticatesController@postResetPassword']);
    Route::any('auth/login-with-linkedin', ['as' => 'auth.linkedin', 'uses' => 'AuthenticatesController@postLoginWithLinkedin']);

    /**
     * User Route
     */
    get('/user/profile', 'UsersController@getProfile');

    post('/user/{id}/profile', ['uses' => 'UsersController@postProfile']);
    post('/user/upload', ['uses' => 'UsersController@uploadImage']);

    /**
     * Template Route
     */
    get('template', 'TemplatesController@getAllTemplate');
    get('template/test/{id}', 'TemplatesController@test');
    get('template/detail/{id}', 'TemplatesController@getDetailTemplate');
    get('template/create', 'TemplatesController@create');
    get('template/view/{id}', 'TemplatesController@view');
    get('template/edit/{id}', 'TemplatesController@edit');
    get('template/edit/view/{id}', 'TemplatesController@editView');
    get('template/{id}/attach', 'TemplatesController@attach');
    get('template/view-template/{id}', 'TemplatesController@renderUserInfoToTemplate');

    post('template/preview', 'TemplatesController@updateBasicTemplate');
    post('template/basic', 'TemplatesController@postBasicTemplate');
    post('template', 'TemplatesController@postTemplates');
    post('template/edit/{id}', 'TemplatesController@postEdit');
    post('template/create', 'TemplatesController@postCreate');
    post('template/delete/{id}', 'TemplatesController@postDeleteTemplate');
    
    /**
     * Market Route
     */
    get('market/all-template', ['uses' => 'MarketPlacesController@getAllTemplateMarket']);
    get('market/detail-template/{id}', ['uses' => 'MarketPlacesController@getDetailTemplateMarket']);

    /**
     * Cart Route
     */
    get('cart/checkout/{id}', 'CartsController@checkout');
    
    post('cart/createpayment', 'CartsController@createPayment');
    post('cart/buy/{id}', 'CartsController@postBuy');

    
});
