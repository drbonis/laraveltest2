<?php

class ExamByConceptTableSeeder extends Seeder 
{

    public function run()
    {

        $concept_rows = DB::select('select concepts.id as concept_id, concepts.str as concept_str, concepts.meshcode as meshcode, count(concepts_questions.id) from concepts_questions left join concepts on concepts_questions.concept_id = concepts.id group by concepts_questions.concept_id order by count(concepts_questions.id) desc');
        
        foreach($concept_rows as $concept_row) {
            DB::insert('insert into exams (shortname, longname, description, created_at, updated_at) values (?, ?, ?, now(), now())',array($concept_row->meshcode,$concept_row->concept_str,"Preguntas sobre ".$concept_row->concept_str));
            var_dump($concept_row->concept_str);
            $new_exam_id = DB::getPdo()->LastInsertId();
            $question_rows = DB::select('select concepts_questions.question_id as question_id from concepts_questions where concept_id = '.$concept_row->concept_id);
            
            foreach($question_rows as $question_row) {
                DB::insert('insert into exams_questions (exam_id, question_id, created_at, updated_at) values (?, ?, now(), now())', array($new_exam_id, $question_row->question_id));
                
            }
        }
        //DB::insert('insert into exams (shortname, longname, description) values (?, ?, ?)',array("MIR2008","Examen MIR 2008","Examen MIR oficial del año 2008"));

        
        
    }
}
