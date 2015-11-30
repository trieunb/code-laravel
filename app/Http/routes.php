<?php

use App\Models\Question;
use App\Models\User;

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
//, 'middleware' => 'role:admin|member'
get('admin/login', ['as' => 'admin.login', 'uses' => 'Admin\DashBoardsController@getLogin']);
post('admin/login', ['as' => 'admin.login', 'uses' => 'Admin\DashBoardsController@postLogin']);

Route::group(['prefix' => 'admin', 'namespace' => 'Admin' , 'middleware' => 'role:admin'], function() {
    get('/', ['as' => 'admin.dashboard', 'uses' => 'DashBoardsController@index']);
    get('/logout', ['as' => 'admin.logout', 'uses' => 'DashBoardsController@getLogout']);
    
    /**
     * User Route
     */
    get('user', ['as' => 'admin.user.get.index', 'uses' => 'UsersController@index']);
    get('user/answer/{id}', ['as' => 'admin.user.get.answer', 'uses' => 'UsersController@answer']);
    
    post('user/answer/{id}', ['as' => 'admin.user.post.answer', 'uses' => 'UsersController@postAnswer']);
    /**
     * Template Route
     */
    get('template/create', ['as' => 'admin.template.get.create', 'uses' => 'TemplateMarketsController@create']);
    get('template/check', ['as' => 'admin.template.check', 'uses' => 'TemplateMarketsController@checkTitle']);
    get('template', ['as' => 'admin.template.get.index', 'uses' => 'TemplateMarketsController@index']);
    get('template/edit/{id}', ['as' => 'admin.template.get.edit', 'uses' => 'TemplateMarketsController@edit']);
    get('template/detail/{id}', ['as' => 'admin.template.get.detail', 'uses' => 'TemplateMarketsController@detail']);
    get('template/delete/{id}', ['as' => 'admin.template.delete', 'uses' => 'TemplateMarketsController@delete']);

    post('template/create', ['as' => 'admin.template.post.create', 'uses' => 'TemplateMarketsController@postCreate']);
    post('template/edit/{id}', ['as' => 'admin.template.post.edit', 'uses' => 'TemplateMarketsController@postEdit']);
    post('template/status/{id}', ['as' => 'admin.status', 'uses' => 'TemplateMarketsController@changeStatus']);

    /**
     * Question Route
     */
    get('question', ['as' => 'admin.question.get.index', 'uses' => 'QuestionsController@index']);
    get('question/create', ['as' => 'admin.question.get.create', 'uses' => 'QuestionsController@create']);
    get('question/edit/{id}', ['as' => 'admin.question.get.edit', 'uses' => 'QuestionsController@edit']);
    get('question/answer/{id}', ['as' => 'admin.question.get.answer', 'uses' => 'QuestionsController@answer']);
    
    post('question/create', ['as' => 'admin.question.post.create', 'uses' => 'QuestionsController@store']);
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
    post('auth/forget-password', ['uses' => 'AuthenticatesController@postForgetPassword']);
    post('auth/change-password', ['uses' => 'AuthenticatesController@postChangePassword']);
    Route::any('auth/login-with-linkedin', ['as' => 'auth.linkedin', 'uses' => 'AuthenticatesController@loginWithLinkedin']);
    Route::any('auth/login-with-facebook', ['as' => 'auth.facebook', 'uses' => 'AuthenticatesController@loginWithFacebook']);

    /**
     * User Route
     */
    get('user/datatable', ['as' => 'api.admin.user.get.dataTable', 'uses' => 'UsersController@dataTable']);
    get('user/profile', 'UsersController@getProfile');
    get('user/status', 'UsersController@getStatus');
    get('user/removephoto/{id}', 'UsersController@removePhoto');
    get('user/answer/{id}', ['as' => 'api.user.get.answers', 'uses' => 'UsersController@getAnswersForAdmin']);

    post('user/{id}/profile', ['uses' => 'UsersController@postProfile']);
    post('user/upload', ['uses' => 'UsersController@uploadImage']);
    post('user/status', 'UsersController@postStatus');

    /**
     * Template Route
     */
    get('template', 'TemplatesController@index');
    get('template/detail/{id}', 'TemplatesController@show');
    get('template/create', 'TemplatesController@create');
    get('template/view/{id}', 'TemplatesController@view');
    get('template/edit/{id}', 'TemplatesController@edit');
    get('template/edit/{id}/{section}', ['as' => 'api.template.edit.section', 'uses' => 'TemplatesController@editView']);
    get('template/{id}/attach', 'TemplatesController@attach');
    get('template/view-template/{id}', 'TemplatesController@renderUserInfoToTemplate');
    get('template/{id}/section', 'TemplatesController@getSections');
    get('template/menu/{id}', ['as' => 'api.template.get.menu', 'uses' => 'TemplatesController@menu']);
    get('template/apply/{id}/{section}', ['as' => 'api.template.get.profile.section', 'uses' => 'TemplatesController@apply']);

    post('template/basic', 'TemplatesController@postBasicTemplate');
    post('template/edit/{id}/{section}', ['as' => 'api.template.post.edit', 'uses' => 'TemplatesController@postEdit']);
    post('template/create', 'TemplatesController@postCreate');
    post('template/delete/{id}', 'TemplatesController@postDelete');
    post('template/menu/{id}', ['as'=> 'edit.template','uses' => 'TemplatesController@editFullTemplate']);
    post('template/{id}/edit/photo', ['as' => 'api.template.post.edit.photo', 'uses' => 'TemplatesController@editPhoto']);
    post('template/view/{id}', ['as'=> 'edit.template','uses' => 'TemplatesController@editFullTemplate']);

    /**
     * Market Route
     */
    get('market', ['uses' => 'MarketPlacesController@getAllTemplateMarket']);
    get('market/template/{id}', ['uses' => 'MarketPlacesController@getDetailTemplateMarket']);
    get('market/view/{id}', 'MarketPlacesController@view');
    
    /**
     * Cart Route
     */

    post('cart/createpayment', 'CartsController@createPayment');
    post('cart/checkout/{id}', 'CartsController@checkout');

    /**
     * Section Route
     */
    
    get('section/names', ['as' => 'api.section.get.names', 'uses' => 'SectionsController@getNames']);
    
    /**
     * Question Route
     */
    get('question/datatable', ['as' => 'api.question.get.dataTable', 'uses' => 'QuestionsController@showDataTableForAdmin']);
    get('question/delete/{id}', ['as' => 'api.question.get.deleteAdmin', 'uses' => 'QuestionsController@destroy']);

    post('question/edit/admin', ['as' => 'api.question.post.editAdmin', 'uses' => 'QuestionsController@postEditAdmin']);
});

get('/test', function() {
    return User::whereHas('questions', function($q) {
        $q->whereQuestionId(11);
    })->get();
    dd(User::with(['questions', function($q) {
  
    }])->get());
    dd(User::find(1)->questions, User::whereHas('questions', function($q) {
        $q->whereQuestionId(11);
    })->get());
});