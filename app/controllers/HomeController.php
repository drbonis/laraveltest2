<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

    
        public function sandbox() {

            $mytext = "Angina de pecho";
            return View::make('sandbox',array("a"=>medquizlib::getConceptsFromText($mytext)));
        }
  
        public function sandboxjson($a) {
            //return json_encode(json_decode(medquizlib::getQuestionsFromConcept($a)));
            return json_encode(json_decode(medquizlib::getConceptsFromQuestion($a)));
            //medquizlib::getSimilarQuestions($a);
            //return json_encode(json_decode(medquizlib::getAscendants($cui)));
            //return json_encode(medquizlib::getFreqOfConcept($cui));
        }
        
 
        
        public function showLogin()
        {
            //if logged redirect to profile, else show login form
            if(Auth::check()){ 
                return Redirect::to('profile');
            } else {
                return View::make('login');
            };
            
        }
        
        public function doLogin() {
            $rules = array(
                'email' => 'required|email',
                'password' => 'required|alphaNum|min:3'
            );
            $validator = Validator::make(Input::all(),$rules);
            
            if ($validator->fails()) {
                return Redirect::to('login')->withErrors($validator)->withInput(Input::except('password'));
            } else {
                $userdata = array(
                    'email' => Input::get('email'),
                    'password' => Input::get('password')
                );
                
                if(Auth::attempt($userdata)) {
                    return Redirect::to('user/profile');
                } else {
                    return Redirect::to('login');
                }
            }
        }
        
        public function doLogout() {
            Auth::logout();
            return Redirect::to('login');
        }

        public function showNewUser() {
            if (Auth::check()) {
                // user is logged, first logout
                return Redirect::to('logout');
            } else {
                // validate form and create new user
                
                return View::make('user.new');
            }
        }
        
        
        public function showProfile() {
            $user_id = Auth::user()->id;
            $results_select = DB::select('select * from answers where user_id = ?',array($user_id));
            
            $results = array();
            $results_concept = array();
            
            foreach($results_select as $select_item) {
                
                if($select_item->answered==$select_item->correct_answer) {
                    $right = 1;
                    $wrong = 0;
                } else {
                    $right = 0;
                    $wrong = 1;
                }
                
                if(array_key_exists($select_item->question_id,$results)) {
                    $results[$select_item->question_id]['done'] ++;
                    $results[$select_item->question_id]['right'] += $right;
                    $results[$select_item->question_id]['wrong'] += $wrong;
                } else {
                    $results[$select_item->question_id] = array("done"=> 1,
                        "right"=> $right,
                        "wrong"=> $wrong);
                }
            }    

            
            $results_concept_select = DB::select('select answers.question_id, answered, correct_answer, concepts.id as concept_id, concepts.cui, concepts.aui, concepts.meshcode, concepts.str from answers inner join concepts_questions on answers.question_id = concepts_questions.`question_id` inner join concepts on concepts_questions.cui = concepts.cui where user_id = ?',array($user_id));

            foreach ($results_concept_select as $concept_item) {
                if($concept_item->answered == $concept_item->correct_answer) {
                    $c_right = 1;
                    $c_wrong = 0;
                } else {
                    $c_right = 0;
                    $c_wrong = 1;
                }

                if(array_key_exists($concept_item->concept_id, $results_concept)) {
                    $results_concept[$concept_item->concept_id]['done'] ++;
                    $results_concept[$concept_item->concept_id]['right'] += $c_right;
                    $results_concept[$concept_item->concept_id]['wrong'] += $c_wrong;

                } else {
                    $results_concept[$concept_item->concept_id] = array(
                        "cui"=>$concept_item->cui,
                        "aui"=>$concept_item->aui,
                        "meshcode"=>$concept_item->meshcode,
                        "str"=>$concept_item->str,
                        "done"=>1,
                        "right"=>$c_right,
                        "wrong"=>$c_wrong
                    );
                }
            }
 
            
            
            return View::make('profile', array(
                "user"=>Auth::user()->email,
                "results"=>$results,
                "results_concept"=>$results_concept
                    ));
        }
        
        public function listExam() {
            return View::make('exam.list', array(
                "user"=>Auth::user()->email,
                "exams"=>DB::select('select * from exams order by created_at;')
                ));
            
        }
        
        public function showExam($exam_id) {

            // shows the exam
            return View::make('exam.show', array(
                "user"=>Auth::user()->email,
                "questions"=>DB::select('select * from questions, exams_questions where questions.id = exams_questions.question_id and exams_questions.exam_id = ? order by questions.id;',array($exam_id)),
                "exam_id"=>$exam_id
                ));
        }
        
        public function doExam() {
            $answers = Input::all();
            $exam_id = $answers["exam_id"];
            $user_id = Auth::user()->id;
            DB::insert('insert into executions (exam_id, user_id) values (?,?)', array($exam_id, $user_id ));
            $new_execution_id = DB::getPdo()->LastInsertId();
            var_dump($new_execution_id);
            foreach ($answers as $key => $answer) {
                if (is_int($key)) {
                    $correct_option = DB::select('select * from questions where id = ?',array($key))[0]->answer;
                    
                    DB::insert('insert into answers (exam_id, execution_id, question_id, user_id, answered, correct_answer) values (?, ?,?,?,?,?)',array($exam_id, $new_execution_id, $key, $user_id, $answer, $correct_option));
                    
                    echo "<pre>pregunta ".$key." respuesta ".$answer." correcta ".var_dump($correct_option)."<pre>";
                }
            }
            return Redirect::to('user/profile');
        }
        
        public function showConcept() {
            return View::make('concept.show');
        }
        
        
        public function getQuestionsFromConcept($cui,$option) {
            return Response::json(json_decode(medquizlib::getQuestionsFromConcept($cui,$option)));
        }
        
        public function getQuestion($question_id) {
            $r = DB::select('select id, question, option1, option2, option3, option4, option5, numoptions, answer from questions where id = ?',array($question_id));
            return Response::json($r[0]);
        }

}
