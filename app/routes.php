<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('sandbox', array('uses'=>'HomeController@sandbox'));

Route::get('/', array('before'=>'auth', 'uses'=>'HomeController@showProfile'));


Route::get('login',array('uses'=>'HomeController@showLogin'));
Route::post('login',array('uses'=>'HomeController@doLogin'));



Route::get('logout',array('uses'=>'HomeController@doLogout'));

Route::get('user/profile',array('before'=>'auth','uses'=>'HomeController@showProfile'));

Route::get('user/new',array('uses'=>'HomeController@showNewUser'));

Route::get('exam/list', array('before'=>'auth', 'uses'=>'HomeController@listExam'));

Route::get('exam/show/{id}', array('before'=>'auth', 'uses'=>'HomeController@showExam'));

Route::post('exam/show', array('uses'=>'HomeController@doExam'));


Route::get('concept/show', array('uses'=>'HomeController@showConcept'));


Route::get('concept/getdata',function(){
    $data = DB::select("select * from terms where str LIKE ?",array("%".Input::get('term')."%"));
    $result = array();
    foreach ($data as $term) {
        $result[] = $term->str;
    }
    return Response::json($result);

});