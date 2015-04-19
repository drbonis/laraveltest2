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
                    return Redirect::to('exam/select');
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
        
        public function selectExam() {
            return View::make('exam.select');
        }
        
        public function statisticsExam($exam_id) {
            $user_id = Auth::user()->id;
            $statistics_user = DB::select('select answers.exam_id, count(answers.answered) as answered, sum(if(answers.answered = answers.correct_answer,1,0)) as correct, sum(if(answers.answered <> answers.correct_answer, 1, 0)) as incorrect, sum(if(answers.answered = answers.correct_answer,1,0))/count(answers.answered) as percorrect from answers where answers.exam_id = ? and answers.user_id = ?', array($exam_id, $user_id))[0];
            
            $statistics_nonuser = DB::select('select answers.exam_id, count(answers.answered) as answered, sum(if(answers.answered = answers.correct_answer,1,0)) as correct, sum(if(answers.answered <> answers.correct_answer, 1, 0)) as incorrect, sum(if(answers.answered = answers.correct_answer,1,0))/count(answers.answered) as percorrect from answers where answers.exam_id = ?', array($exam_id))[0];

            return json_encode(array('exam_id'=>$exam_id, 'user_id'=>$user_id, 'user_statistics'=>array('answered'=>$statistics_user->answered, 'correct'=>$statistics_user->correct, 'incorrect'=>$statistics_user->incorrect, 'percorrect'=>$statistics_user->percorrect), 'nonuser_statistics'=>array('answered'=>$statistics_nonuser->answered, 'correct'=>$statistics_nonuser->correct, 'incorrect'=>$statistics_nonuser->incorrect, 'percorrect'=>$statistics_nonuser->percorrect)));

        }

        public function statisticsExamIndirect($exam_id) {
            $user_id = Auth::user()->id;
            
            //get the list of questions included in the exam_id
            
            $questions_list = DB::select('select distinct exams_questions.question_id from exams_questions where exams_questions.exam_id = ?',array($exam_id));
            
            //for each question get the number of correct and incorrect questions answered
            
            $results = array('exam_id'=>$exam_id, 'user_id'=>$user_id, 'user_statistics'=>array('answered'=>0, 'correct'=>0, 'incorrect'=>0, 'percorrect'=>0), 'nonuser_statistics'=>array('answered'=>0, 'correct'=>0, 'incorrect'=>0, 'percorrect'=>0));
            
            foreach($questions_list as $question) {

                $statistics_user = DB::select('select count(answers.answered) as answered, sum(if(answers.answered = answers.correct_answer,1,0)) as correct, sum(if(answers.answered <> answers.correct_answer, 1, 0)) as incorrect from answers where answers.question_id = ? and answers.user_id = ?', array($question->question_id, $user_id))[0];

                $results['user_statistics']['answered'] += $statistics_user->answered;
                $results['user_statistics']['correct'] += $statistics_user->correct;
                $results['user_statistics']['incorrect'] += $statistics_user->incorrect;
                        
                
                $statistics_nonuser = DB::select('select count(answers.answered) as answered, sum(if(answers.answered = answers.correct_answer,1,0)) as correct, sum(if(answers.answered <> answers.correct_answer, 1, 0)) as incorrect from answers where answers.question_id = ? and answers.user_id <> ?', array($question->question_id, $user_id))[0];
                
                $results['nonuser_statistics']['answered'] += $statistics_nonuser->answered;
                $results['nonuser_statistics']['correct'] += $statistics_nonuser->correct;
                $results['nonuser_statistics']['incorrect'] += $statistics_nonuser->incorrect;
                
            }
            
            if($results['user_statistics']['answered'] > 0) {
                $results['user_statistics']['percorrect'] = $results['user_statistics']['correct'] / $results['user_statistics']['answered'];
            }
            
            if($results['nonuser_statistics']['answered'] > 0) {
                $results['nonuser_statistics']['percorrect'] = $results['nonuser_statistics']['correct'] / $results['nonuser_statistics']['answered'];
            }
            
           
            //var_dump($results);
            return json_encode($results);

        }
        
        
        
        
        
        public function showExam() {
            $i = Input::all();
            $r = array();
            $questions_list = json_decode($i['questions_list']);
            foreach ($questions_list as $question_id) {
                $r[] = $this->getQuestionWithConcepts($question_id);
            }
              
            /* insert new execution user_id, exam_id ... and get new execution_id*/
            
            DB::insert('insert into executions (exam_id, user_id, created_at, updated_at) values (?, 1, now(), now())',array($i['exam_id'])); // pendiente de obtener user_id dinamicamente
            //$new_execution_id = DB::getPdo()->lastInsertId();
            
            return View::make('exam.show', array(
                "questions"=>$r, 
                "questions_id_list"=>$questions_list,
                "exam_id"=>$i['exam_id'], 
                'execution_id'=>DB::getPdo()->lastInsertId() 
            ));
        }
        
        public function listExam() {
            return View::make('exam.list', array(
                "user"=>Auth::user()->email,
                "exams"=>DB::select('select * from exams order by created_at;')
                ));
            
        }
        /*
        public function showExam($exam_id) {

            // shows the exam
            return View::make('exam.show', array(
                "user"=>Auth::user()->email,
                "questions"=>DB::select('select * from questions, exams_questions where questions.id = exams_questions.question_id and exams_questions.exam_id = ? order by questions.id;',array($exam_id)),
                "exam_id"=>$exam_id
                ));
        }
        */
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
                    
                    DB::insert('insert into answers (exam_id, execution_id, question_id, user_id, answered, correct_answer, created_at, updated_at) values (?, ?,?,?,?,?,now(),now())',array($exam_id, $new_execution_id, $key, $user_id, $answer, $correct_option));
                    
                    echo "<pre>pregunta ".$key." respuesta ".$answer." correcta ".var_dump($correct_option)."<pre>";
                }
            }
            return Redirect::to('user/profile');
        }
        
        
        
        
        public function showExamCarrousel($exam_id) {
            return View::make('exam.carrousel', array(
                "user"=>Auth::user()->email,
                "questions"=>DB::select('select * from questions, exams_questions where questions.id = exams_questions.question_id and exams_questions.exam_id = ? order by questions.id;',array($exam_id)),
                "exam_id"=>$exam_id
                ));
        }
        
        
        
        
        
        /*
        public function showConcept() {
            return View::make('concept.show');
        }
        */
        /*
        public function getQuestionsFromConcept($cui,$option) {
            return Response::json(json_decode(medquizlib::getQuestionsFromConcept($cui,$option)));
        }
        */
        
        public function showQuestion($question_id) {
            $r = json_decode($this->getQuestionWithConcepts($question_id,'json'));

            return View::make('question.show'
                    ,array(
                        'question_id'=>$r->id,
                        'question'=>$r->question,
                        'opt1'=>$r->option1,
                        'opt2'=>$r->option2,
                        'opt3'=>$r->option3,
                        'opt4'=>$r->option4,
                        'opt5'=>$r->option5,
                        'numoptions'=>$r->numoptions,
                        'answer'=>$r->answer,
                        'concepts'=>$r->concepts,
                        'img'=>$r->img
                    )); 

        }
        
        public function editQuestion($question_id) {
            $r = json_decode($this->getQuestionWithConcepts($question_id,'json'));
            $msg = Session::get('msg');
            Session::put('msg','');
            return View::make('question.edit'
                    ,array(
                        //'exam_list'=>json_decode($this->getAllExams())
                        'msg'=>$msg,
                        'question_id'=>$r->id,
                        'question'=>$r->question,
                        'opt1'=>$r->option1,
                        'opt2'=>$r->option2,
                        'opt3'=>$r->option3,
                        'opt4'=>$r->option4,
                        'opt5'=>$r->option5,
                        'numoptions'=>$r->numoptions,
                        'answer'=>$r->answer,
                        'concepts'=>$r->concepts, 
                        'img'=>$r->img
                       
                    )); 
        }
        
        public function doEditQuestion() {
            $i = Input::all();
            var_dump($i);

            $file = $i['img'];
 
            
           
            if($file!=null) {
                $file_name = time().".".$i['img']->guessExtension();
                $file->move("img/questions",$file_name);               
            } else {
                $file_name = $i['prev_img'];
            }; 
                        
            
            
            
            
            DB::table('questions')->where('id',$i['question_id'])->update(array(
                    'question'=>$i['question'],
                    'option1'=>$i['option1'],
                    'option2'=>$i['option2'],
                    'option3'=>$i['option3'],
                    'option4'=>$i['option4'],
                    'option5'=>$i['option5'],
                    'answer'=>$i['answer'],
                    'img'=>$file_name
                    ));
            DB::table('concepts_questions')->where('question_id',$i['question_id'])->delete();
            
            $cui_list = json_decode($i['cui_list_input']);
            
            foreach($cui_list as $cui) {
                DB::insert('insert into concepts_questions (concept_id, term_id, question_id, cui, created_at, updated_at, direct) values (?, ?, ?, ?, now(), now(), ?)',array($cui->concept_id, $cui->term_id, $i['question_id'], $cui->cui, $cui->direct));
                
            }
            
            
            return Redirect::to("question/edit/".$i['question_id'])->with('msg','Pregunta actualizada'); 
          
        }
        
        public function createQuestion() {
            return View::make('question.create',array('exam_list'=>json_decode($this->getAllExams())));
        }
		
        public function createQuestionOld() {
            return View::make('question.create2');
        }
        
        public function doCreateQuestion() {
            $i = Input::all();
            $file = $i['img'];
            var_dump($i);
            $cui_list = json_decode($i['cui_list_input']);

            
           
            if($file!=null) {
                $file_name = time().".".$i['img']->guessExtension();
                $file->move("img/questions",$file_name);               
            } else {
                $file_name = null;
            }; 
            
            DB::insert('insert into questions (question, option1, option2, option3, option4, option5, numoptions, answer, created_at, updated_at, img) values(?, ?, ?, ?, ?, ?, 5, ?, now(), now(), ?)',array($i['question'], $i['option1'], $i['option2'], $i['option3'], $i['option4'], $i['option5'], $i['answer'], $file_name));
            $new_question_id = DB::getPdo()->LastInsertId();
            
            DB::insert('insert into exams_questions (exam_id, question_id, created_at, updated_at) values (?, ?, now(), now())', array($i['exam_list'], $new_question_id)); 
            
            //get concepts
            
            $cui_list = json_decode($i['cui_list_input']);
            
            foreach($cui_list as $cui) {
                DB::insert('insert into concepts_questions (concept_id, term_id, question_id, cui, created_at, updated_at, direct) values (?, ?, ?, ?, now(), now(), ?)',array($cui->concept_id, $cui->term_id, $new_question_id, $cui->cui, $cui->direct));
                
            }
             
            
            return Redirect::to('question/create');

        }
        
        /* API SECTION */
        
        public function answerQuestion(){
            $i = Input::all();
            /*
             * $i->answer
             * $i->correct_answer
             * $i->exam_id
             * $i->execution_id
             * $i->user_id
             * 
             * table answer id, execution_id, exam_id, question_id, user_id, created_at, updated_at, answered, correct_answer
             */
            
            DB::insert('insert into answers (execution_id, exam_id, question_id, user_id, created_at, updated_at, answered, correct_answer) values (?, ?, ?, ?, now(), now(), ?, ?)',array($i['execution_id'], $i['exam_id'], $i['question_id'], $i['user_id'], $i['answer'], $i['correct_answer']));
            return json_encode(array('new_answer_id'=>DB::getPdo()->lastInsertId()));
            
        }
        
        
        public function getQuestion($question_id,$json='') {
            $r = DB::select('select id, question, option1, option2, option3, option4, option5, numoptions, answer, img from questions where id = ?',array($question_id));
            return medquizlib::responseFacade($r[0],$json);
            //return json_encode($r[0]);
        }
        
        public function getQuestionWithConcepts($question_id,$json='') {
            $r = json_decode($this->getQuestion($question_id,'json'));
            $r->concepts = array();
            $concepts_list = DB::select('select id, cui, concept_id, term_id, direct from concepts_questions where question_id = ?',array($question_id));
            if(count($concepts_list)>0){
                $concepts_added = array();
                foreach($concepts_list as $cui) {
                    if(!in_array($cui->cui,$concepts_added)) {
                        $concepts_added[] = $cui->cui;
                        $request = Request::create("concept/str/".$cui->cui."/json", 'GET');
                        $str = Route::dispatch($request)->getContent();
                        $r->concepts[] = array("id"=>$cui->id, "cui"=>$cui->cui, "direct"=>$cui->direct, "str"=>$str, "concept_id"=>$cui->concept_id, "term_id"=>$cui->term_id);
                    }
                    
                }
            }
            return medquizlib::responseFacade($r,$json);
        }
        
        public function removeConceptFromQuestion($question_id,$cui) {
            DB::table('concepts_questions')->where('question_id','=',$question_id)->where('cui','=',$cui)->delete();
            return json_encode(array($question_id=>$cui));
            //return Redirect::to("question/edit/".$question_id);
        }
        
        public function getAllExams($json='json') {
            $r = DB::table('exams')->get();
            return medquizlib::responseFacade($r,$json);
        }
        
        public function getConceptsFromText($json='json') {
            $i = Input::all();
            $r = medquizlib::getConceptsFromText($i['text']);
            return medquizlib::responseFacade($r,$json);
        }


}



/*

 * Muestra lista de cuantas preguntas contiene cada concepto.
 * select concepts.id, concepts.str, count(concepts_questions.id) from concepts_questions left join concepts on concepts_questions.concept_id = concepts.id group by concepts_questions.concept_id order by count(concepts_questions.id) desc;
 * 
 * 
 *  */