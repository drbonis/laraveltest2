<?php

class AnswerTableSeeder extends Seeder 
{

    public function run()
    {

        DB::table('answers')->delete();     
        DB::table('executions')->delete();
       
        $users_query = DB::select('select id from users');
        $exams_query = DB::select('select id from exams');
        if(count($exams_query)>0){
            foreach($exams_query as $exam) {
                $questions_form_exam_query = DB::select('select questions.id as id, questions.answer as answer from questions, exams_questions where questions.id = exams_questions.question_id and exams_questions.exam_id = ?',array($exam->id));
                if(count($questions_form_exam_query)>0){
                    foreach($users_query as $user) {
                        for($exec=0;$exec<10;$exec++) {
                            DB::insert('insert into executions(exam_id, user_id, created_at, updated_at) values(?,?,now(),now())',array($exam->id,$user->id));
                            $exec_id = DB::getPdo()->LastInsertId();
                            foreach($questions_form_exam_query as $question) {
                                DB::insert('insert into answers(execution_id, exam_id, question_id, user_id, created_at, updated_at, answered, correct_answer) values(?,?,?,?,now(),now(),?,?)',array($exec_id, $exam->id,$question->id,$user->id,rand(0,3),$question->answer));
                            }
                        }
                    }
                }
            }
        }
    }       
}

