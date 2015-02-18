<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Concepts Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/


  
        public function sandboxjson($a) {
            $r = array();
            $related_questions = json_decode(medquizlib::getSimilarQuestions($a));
            foreach($related_questions as $question_id=>$score) {
                $question = DB::select('select * from questions where id = ?',arraY($question_id))[0]   ;
                $r[$question->id] = ["score"=>$score, "question"=>$question->question, "option1"=>$question->option1, "option2"=>$question->option2, "option3"=>$question->option3, "option4"=>$question->option4, "option5"=>$question->option5];
            }
            return View::make('sandbox',array("r"=>$r));
        }
        
 
        
        public function showConcept() {
            return View::make('concept.show');
        }

}
