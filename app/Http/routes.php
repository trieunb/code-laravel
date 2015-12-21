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

get('pdf', function() {
      $snappy = \App::make('snappy.pdf');
       $snappy->generateFromHtml( $this->content, public_path('abc.pdf'));
});

/*get('test', function() {
    $job = \App\JobTest::find(1);
     $skills = []; 
    foreach ($job->skill as $skill)
        $skills[] = $skill['skill'];
    $userIds = [];
    $queryWork = \DB::table('user_work_histories')->select('user_id')
        ->WhereRaw('MATCH (company, job_title, job_description) AGAINST(?)', [$job->job_description.','.$job->job_title]);
    $querySkill = \DB::table('user_skills')->select('user_id')
        ->whereRaw('MATCH (skill_name, experience) AGAINST (?)', [implode(',', $skills)]);
    $data = \DB::table('users')
        ->selectRaw('id')
        // ->join('user_educations', 'user_educations.user_id', '=', 'users.id')
        // ->join('user_work_histories', 'user_work_histories.user_id', '=', 'users.id')
        ->whereIn('id', function($query) use($job, $skills, $last_query) {
            $query->select('user_id')
                ->from('user_skills')
                ->whereRaw('MATCH (skill_name, experience) AGAINST (?) UNION (SELECT user_id FROM user_work_histories '.
                    'MATCH (company, job_title, job_description) AGAINST(?))', [implode(',', $skills), $job->job_description.','.$job->job_title]);

        })
        
        ->WhereIn('users.id', function($query) use ($job){
            $query->select('user_id')
                ->from('user_work_histories')
                ->WhereRaw('MATCH (company, job_title, job_description) AGAINST(?)', [$job->job_description.','.$job->job_title]);
        })
        // ->orWhereRaw('MATCH (school_name, degree, result) AGAINST (?)', [$job->education])
        // ->orWhereRaw('MATCH (company, job_title, job_description) AGAINST(?)', [implode(',', $skills)])
        // ->orWhereRaw('MATCH (company, job_title, job_description) AGAINST(?)', [$job->description.','.$job->title])
        ->toSql();
        $t =$job->job_description.','.$job->job_title;
        dd($data, $t);
    $dataUsers = [];
    foreach ($data as $value) {
        $userIds[] = $value->id;
    }
    dd($data);
    $data = \DB::table('users')
        ->selectRaw('DISTINCT users.id')
        ->join('user_educations', 'user_educations.user_id', '=', 'users.id')
        ->whereNotIn('users.id', [implode(',', $userIds)])
        ->get();

    $users = \App\Models\User::whereNotIn('id', [implode(',', $userIds)])->get();

    foreach ($users as $user) {

        if ($user->location != null)
            $dataUsers[$user->id] = $user->location;
    }


        dd($dataUsers, $data);
});*/

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
    /**
     * Question Route
     */
    get('question', ['as' => 'admin.question.get.index', 'uses' => 'QuestionsController@index']);
    get('question/create', ['as' => 'admin.question.get.create', 'uses' => 'QuestionsController@create']);
    get('question/edit/{id}', ['as' => 'admin.question.get.edit', 'uses' => 'QuestionsController@edit']);
    get('question/answer/{id}', ['as' => 'admin.question.get.answer', 'uses' => 'QuestionsController@answer']);
    
    post('question/create', ['as' => 'admin.question.post.create', 'uses' => 'QuestionsController@store']);

    /**
     * Report
     */
    get('report/user', ['as' => 'admin.report.user.month', 'uses' => 'ReportController@reportUserByMonth']);
    get('report/template', ['as' => 'admin.report.template', 'uses' => 'ReportController@reportTemplate']);
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
});

