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

Route::get('sandbox/{a}',function($a){
    $r = medquizlib::getConceptsFromText($a);
    var_dump($r);
});

Route::get('/', array('before'=>'auth', 'uses'=>'HomeController@showProfile'));


Route::get('login',array('uses'=>'HomeController@showLogin'));
Route::post('login',array('uses'=>'HomeController@doLogin'));



Route::get('logout',array('uses'=>'HomeController@doLogout'));

Route::get('user/profile',array('before'=>'auth','uses'=>'HomeController@showProfile'));

Route::get('user/new',array('uses'=>'HomeController@showNewUser'));


/*Exam*/

Route::get('exam/select', array('before'=>'auth', 'uses'=>'HomeController@selectExam'));

Route::post('exam/show', array('before'=>'auth', 'uses'=>'HomeController@showExam'));

Route::get('exam/list', array('before'=>'auth', 'uses'=>'HomeController@listExam'));

Route::get('exam/statistics/{exam_id}', array('before'=>'auth', 'uses'=>'HomeController@statisticsExamIndirect'));

//Route::get('exam/show/{id}', array('before'=>'auth', 'uses'=>'HomeController@showExam'));
//Route::post('exam/show', array('uses'=>'HomeController@doExam'));

Route::get('exam/carrousel/{id}', array('uses'=>'HomeController@showExamCarrousel'));
Route::post('exam/carrousel', array('uses'=>'HomeController@doExamCarrousel'));

/*end Exam*/


Route::get('concept/show/{cui}',array('uses'=>'ConceptController@getConceptDetailsFromCui'));



Route::get('concept/select', array('uses'=>'ConceptController@selectConcept'));
Route::get('concept/ascendants/{cui}', array('uses'=>'ConceptController@getAscendantsFromCui'));
Route::get('concept/ascendantsall/{cui}', array('uses'=>'ConceptController@getAscendantsFromCuiAll'));
Route::get('concept/descendantstree/{cui}', array('uses'=>'ConceptController@getDescendantsFromCuiTree'));
Route::get('concept/descendantsall/{cui}', array('uses'=>'ConceptController@getDescendantsFromCuiAll'));
Route::get('concept/children/{cui}/{json?}',array('uses'=>'ConceptController@getChildrenFromConcept'));
Route::get('concept/str/{cui}/{json?}',array('uses'=>'ConceptController@getStrFromCui'));

Route::get('concept/questions/{cui}/{option?}',array('uses'=>'ConceptController@getQuestionsFromConcept'));

Route::get('concept/answers/{cui}/{user_id}/{option?}',array('uses'=>'ConceptController@getAnswersFromConceptUser'));
Route::get('concept/answers/{cui}/{option?}',array('uses'=>'ConceptController@getAnswersFromConceptUser'));

Route::get('concept/statistics/all',array('before'=>'auth', 'uses'=>'HomeController@conceptAllStatistics'));

/*question section*/

Route::get('question/show/{question_id}', array('uses'=>'HomeController@showQuestion'));


Route::get('question/edit/{question_id}', array('uses'=>'HomeController@editQuestion'));
Route::post('question/edit',array('uses'=>'HomeController@doEditQuestion'));


Route::get('question/delete/concept/{question_id}/{cui}',array('uses'=>'HomeController@removeConceptFromQuestion'));

Route::get('question/create',array('before'=>'auth', 'uses'=>'HomeController@createQuestion'));
Route::post('question/create',array('before'=>'auth', 'uses'=>'HomeController@doCreateQuestion'));

Route::get('conceptdetector',array('uses'=>'HomeController@createQuestionOld'));


/*API section*/

Route::get('api/exam/list', array('before'=>'auth', 'uses'=>'HomeController@getAllExams'));
Route::post('api/concept/fromtext', array('before'=>'auth', 'uses'=>'HomeController@getConceptsFromText'));
Route::post('api/question/create', array('before'=>'auth', 'uses'=>'HomeController@createQuestionPost'));
Route::post('api/question/answer', array('before'=>'auth', 'uses'=>'HomeController@answerQuestion'));