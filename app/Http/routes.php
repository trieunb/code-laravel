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
    get('/user/profile', 'UsersController@getProfile');
    get('/user/template', ['uses' => 'UsersController@getTemplates']);
    post('user/template', ['uses' => 'UsersController@postTemplates']);
    get('user/template/market', ['uses' => 'UsersController@getAllTemplatesFromMarket']);
    get('user/template/{id}', ['uses' => 'UsersController@getDetailTemplate']);

    post('/user/{id}/profile', ['uses' => 'UsersController@postProfile']);
    post('/user/{id}/upload', ['uses' => 'UsersController@uploadImage']);
});

get('/test', function() {
/*  $phpWord = new \PhpOffice\PhpWord\PhpWord();
  $section = $phpWord->addSection();
  $html = '<h1>Adding element via HTML</h1>';
$html .= '<p>Some well formed HTML snippet needs to be used</p>';
$html .= '<p>With for example <strong>some<sup>1</sup> <em>inline</em> formatting</strong><sub>1</sub></p>';
$html .= '<p>Unordered (bulleted) list:</p>';
$html .= '<ul><li>Item 1</li><li>Item 2</li><ul><li>Item 2.1</li><li>Item 2.1</li></ul></ul>';
$html .= '<p>Ordered (numbered) list:</p>';
$html .= '<ol><li>Item 1</li><li>Item 2</li></ol>';
$html .= '<img src="'.public_path('assets/images/avatar_2x.png').'"/>';
$html .= '<p>Some well formed HTML snippet needs to be used</p>';
  \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html);

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save(public_path('helloWorld.docx'));
$pdf = app()->make('dompdf.wrapper');
$pdf->loadHTML($html);
 $pdf->save(public_path('test.pdf'));

  dd($html);*/
  header("Content-type: application/vnd.ms-word");
  header("Content-Disposition: attachment; Filename=12345.doc");
  $html = file_get_contents(public_path('test.html'));
  echo $html;
});
