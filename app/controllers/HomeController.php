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
            return View::make('profile', array("user"=>Auth::user()->email));
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

            foreach ($answers as $key => $answer) {
                if (is_int($key)) {
                    $correct_option = DB::select('select * from questions where id = ?',array($key))[0]->answer;
                    
                    DB::insert('insert into answers (exam_id, question_id, user_id, answered, correct_answer) values (?,?,?,?,?)',array($exam_id, $key, Auth::user()->id, $answer, $correct_option));
                  
                    echo "<pre>pregunta ".$key." respuesta ".$answer." correcta ".var_dump($correct_option)."<pre>";
                }
            }
            return var_dump(Input::all());
        }

}
