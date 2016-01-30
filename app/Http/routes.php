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

get('admin/login', ['as' => 'admin.login', 'uses' => 'Admin\DashBoardsController@getLogin']);
post('admin/login', ['as' => 'admin.login', 'uses' => 'Admin\DashBoardsController@postLogin']);

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'role:admin' ], function() {
    get('/', ['as' => 'admin.dashboard', 'uses' => 'DashBoardsController@index']);
    get('/logout', ['as' => 'admin.logout', 'uses' => 'DashBoardsController@getLogout']);

    /**
     * User Route
     */
    get('user', ['as' => 'admin.user.get.index', 'uses' => 'UsersController@index']);
    get('user/datatable', ['as' => 'api.admin.user.get.dataTable', 'uses' => 'UsersController@dataTable']);
    get('user/delete/{id}', ['as' => 'admin.user.delete', 'uses' => 'UsersController@destroy']);
    get('user/detail/{id}', ['as' => 'admin.user.get.detail', 'uses' => 'UsersController@detail']);
    get('user/send-notification', ['as' => 'admin.user.get.send-notification', 'uses' => 'UsersController@getSendNotification']);

    post('user/delete', ['as' => 'admin.user.post.delete', 'uses' => 'UsersController@postDelete']);
    post('user/send-notification', ['as' => 'admin.user.post.send-notification', 'uses' => 'UsersController@postSendNotification']);
    /**
     * Template Route
     */
    get('template/create', ['as' => 'admin.template.get.create', 'uses' => 'TemplateMarketsController@create']);
    get('template/check', ['as' => 'admin.template.check', 'uses' => 'TemplateMarketsController@checkTitle']);
    get('template', ['as' => 'admin.template.get.index', 'uses' => 'TemplateMarketsController@index']);
    get('template/edit/{id}', ['as' => 'admin.template.get.edit', 'uses' => 'TemplateMarketsController@edit']);
    get('template/detail/{id}', ['as' => 'admin.template.get.detail', 'uses' => 'TemplateMarketsController@detail']);
    get('template/delete/{id}', ['as' => 'admin.template.delete', 'uses' => 'TemplateMarketsController@delete']);
    get('template/datatable', ['as' => 'api.template.get.dataTable', 'uses' => 'TemplateMarketsController@showDatatableTemplate']);
    get('template/view/{id}', ['as' => 'admin.template.get.view', 'uses' => 'TemplateMarketsController@getView']);
    get('template/{id}/define', ['as' => 'admin.template.get.define', 'uses' => 'TemplateMarketsController@getDefine']);
    get('template/status/{id}', ['as' => 'admin.template.status', 'uses' => 'TemplateMarketsController@changeStatus']);

    post('template/create', ['as' => 'admin.template.post.create', 'uses' => 'TemplateMarketsController@postCreate']);
    post('template/edit/{id}', ['as' => 'admin.template.post.edit', 'uses' => 'TemplateMarketsController@postEdit']);
    post('template/define', ['as' => 'admin.template.post.define', 'uses' => 'TemplateMarketsController@postDefine']);
    post('template/action', ['as' => 'admin.template.post.action', 'uses' => 'TemplateMarketsController@postAction']);
    /**
     * Question Route
     */
    get('question', ['as' => 'admin.question.get.index', 'uses' => 'QuestionsController@index']);
    get('question/create', ['as' => 'admin.question.get.create', 'uses' => 'QuestionsController@create']);
    get('question/edit/{id}', ['as' => 'admin.question.get.edit', 'uses' => 'QuestionsController@edit']);
    get('question/answer/{id}', ['as' => 'admin.question.get.answer', 'uses' => 'QuestionsController@answer']);

    post('question/create', ['as' => 'admin.question.post.create', 'uses' => 'QuestionsController@store']);

    /**
     * Report Route
     */
    get('report/user', ['as' => 'admin.report.user.month', 'uses' => 'ReportController@reportUserByMonth']);
    get('report/template', ['as' => 'admin.report.template', 'uses' => 'ReportController@reportTemplate']);

    /**
     * Category Route
     */
    get('category', ['as' => 'admin.category.get.index', 'uses' => 'CategoriesController@index']);
    get('category/create', ['as' => 'admin.category.get.create', 'uses' => 'CategoriesController@create']);
    get('category/edit/{id}', ['as' => 'admin.category.get.edit', 'uses' => 'CategoriesController@edit']);
    get('category/detail/{id}', ['as' => 'admin.category.get.detail', 'uses' => 'CategoriesController@detail']);
    get('category/datatable', ['as' => 'admin.category.get.datatable', 'uses' => 'CategoriesController@datatable']);

    post('category/checkname', ['as' => 'admin.category.post.checkname', 'uses' => 'CategoriesController@checkName']);
    post('category/create', ['as' => 'admin.category.post.create', 'uses' => 'CategoriesController@postCreate']);
    post('category/edit', ['as' => 'admin.category.post.edit', 'uses' => 'CategoriesController@postEdit']);

    /**
     * resume route (buy template from market)
     */
    get('resume/detail/{id}', ['as' => 'admin.resume.detail', 'uses' => 'DashBoardsController@getDetailResume']);
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
    post('auth/reset-password', ['uses' => 'AuthenticatesController@postForgetPassword']);
    post('auth/change-password', ['uses' => 'AuthenticatesController@postChangePassword']);
    Route::any('auth/login-with-linkedin', ['as' => 'auth.linkedin', 'uses' => 'AuthenticatesController@loginWithLinkedin']);
    Route::any('auth/login-with-facebook', ['as' => 'auth.facebook', 'uses' => 'AuthenticatesController@loginWithFacebook']);

    /**
     * User Route
     */
    get('user/profile', 'UsersController@getProfile');
    get('user/status', 'UsersController@getStatus');
    get('user/removephoto/{id}', 'UsersController@removePhoto');
    get('user/answer/{id}', ['as' => 'api.user.get.answers', 'uses' => 'UsersController@getAnswersForAdmin']);
    get('user/{id}/{section}', ['as' => 'api.user.get.section', 'uses' => 'UsersController@getSection']);

    post('user/{id}/profile', ['uses' => 'UsersController@postProfile']);
    post('user/upload', ['uses' => 'UsersController@uploadImage']);
    post('user/status', 'UsersController@postStatus');
    post('user/{resume_id}/apply-job/{job_id}', 'UsersController@applyJob');

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
    post('template/getfromprofile/{id}/{section}', ['as' => 'api.template.get.fromprofile', 'uses' => 'TemplatesController@getFromProfile']);
    post('template/rename/{id}', 'TemplatesController@renameResume');

    /**
     * Market Route
     */
    get('market/', ['uses' => 'MarketPlacesController@getAllTemplateMarket']);
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
    get('question/', 'QuestionsController@index');

    post('question/edit/admin', ['as' => 'api.question.post.editAdmin', 'uses' => 'QuestionsController@postEditAdmin']);
    post('answers/', 'QuestionsController@postAnswerOfUser');

    /**
     * Job Route
     */
    get('job/search', 'JobsController@search');
    get('job/match', 'JobsController@match');

});
get('shared/template-category', 'API\MarketPlacesController@getListTemplateCategory');
get('shared/job-skills', 'API\JobsController@getListJobSkill');
get('shared/job-categories', 'API\JobsController@getListJobCategory');
get('developer', 'DeveloperController@index');
post('developer/send_job_match_notification', 'DeveloperController@sendJobMatchNotification');