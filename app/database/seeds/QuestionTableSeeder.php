<?php

class QuestionTableSeeder extends Seeder 
{

    public function run()
    {

        DB::table('questions')->delete();     
        DB::table('exams')->delete();
        DB::table('exams_questions')->delete();
        
        $exam_id_ref = array(2011=>null, 2012=>null, 2013=>null);
        
        DB::insert('insert into exams (shortname, longname, description, created_at, updated_at) values (?, ?, ?, now(), now())',array("MIR2011","Examen MIR 2011","Examen MIR oficial del año 2011"));
        $exam_id_ref[2011] = DB::getPdo()->LastInsertId();
        DB::insert('insert into exams (shortname, longname, description, created_at, updated_at) values (?, ?, ?, now(), now())',array("MIR2012","Examen MIR 2012","Examen MIR oficial del año 2012"));
        $exam_id_ref[2012] = DB::getPdo()->LastInsertId();
        DB::insert('insert into exams (shortname, longname, description, created_at, updated_at) values (?, ?, ?, now(), now())',array("MIR2013","Examen MIR 2013","Examen MIR oficial del año 2013"));
        $exam_id_ref[2013] = DB::getPdo()->LastInsertId();
        
        $rows = file('./app/database/seeds/questions/questions.csv');
        
        foreach($rows as $row) {
            $columns = explode("|",trim($row));
            
            if(array_key_exists($columns[8],$exam_id_ref)) {

                Question::create(array(
                    'question'=>$columns[1],
                    'option1'=>$columns[2],
                    'option2'=>$columns[3],
                    'option3'=>$columns[4],
                    'option4'=>$columns[5],
                    'option5'=>$columns[6],
                    'numoptions'=>5,
                    'answer'=>$columns[7]
                ));
                $new_question_id = DB::getPdo()->LastInsertId();
                DB::insert('insert into exams_questions (exam_id, question_id, created_at, updated_at) values (?, ?, now(), now())', array($exam_id_ref[$columns[8]], $new_question_id));
                
                $question_concepts = json_decode(medquizlib::getConceptsFromText($columns[1]." ".$columns[2]." ".$columns[3]." ".$columns[4]." ".$columns[5]." ".$columns[6]));
                foreach ($question_concepts as $concept) {
                   DB::insert('insert into concepts_questions (concept_id, term_id, question_id, cui, created_at, updated_at) values (?, ?, ?, ?, now(), now())', array($concept->concept_id, $concept->term_id, $new_question_id, $concept->cui));                 
                }
                
            }
        }
        
    }
}

