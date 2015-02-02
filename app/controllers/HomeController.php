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
            $rand_concepts = DB::table('concepts')->orderBy(DB::raw('RAND()'))->take(5)->get();
            $results = "";
            foreach ($rand_concepts as $concept) {
               $results = $results.$concept->id;
            }
            
            return var_dump($results);
        }
    
	public function showWelcome()
	{
		return View::make('hello');
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
                    $results[$select_item->question_id]['right'] = $results[$select_item->exam_id]['right'] + $right;
                    $results[$select_item->question_id]['wrong'] = $results[$select_item->exam_id]['wrong'] + $wrong;
                } else {
                    /*
                    $results[$select_item->exam_id]['done'] = 1;
                    $results[$select_item->exam_id]['right'] = $results[$select_item->exam_id]['right'] + $right;
                    $results[$select_item->exam_id]['wrong'] = $results[$select_item->exam_id]['wrong'] + $wrong;
                */
                    $results[$select_item->question_id] = array("done"=> 1,
                        "right"=> $right,
                        "wrong"=> $wrong);
                }
                
                
                $results_concept_select = DB::select('select answers.question_id, answered, correct_answer, concept_id, cui, aui, meshcode, str from answers inner join concepts_questions on answers.question_id = concepts_questions.`question_id` inner join concepts on concepts_questions.concept_id = concepts.id where user_id = ?',array($user_id));
                
                
                
                foreach ($results_concept_select as $concept_item) {
                    if($concept_item->answered == $concept_item->correct_answer) {
                        $right = 1;
                        $wrong = 0;
                    } else {
                        $right = 0;
                        $wrong = 1;
                    }
                    
                    if(array_key_exists($concept_item->concept_id, $results_concept)) {
                        $results_concept[$concept_item->concept_id]['done'] ++;
                        $results_concept[$concept_item->concept_id]['right'] = $results_concept[$concept_item->concept_id]['right'] + $right;
                        $results_concept[$concept_item->concept_id]['wrong'] = $results_concept[$concept_item->concept_id]['wrong'] + $wrong;
                        
                    } else {
                        $results_concept[$concept_item->concept_id] = array(
                            "cui"=>$concept_item->cui,
                            "aui"=>$concept_item->aui,
                            "meshcode"=>$concept_item->meshcode,
                            "str"=>$concept_item->str,
                            "done"=>1,
                            "right"=>$right,
                            "wrong"=>$wrong
                        );
                    }
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

}
