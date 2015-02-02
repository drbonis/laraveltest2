<?php

class QuestionTableSeeder extends Seeder 
{

    public function run()
    {
        
        /*
            $rand_string = "";
            for ($j=0;$j<mt_rand(30,200);$j++) {
                for ($i=0;$i<mt_rand(3,15);$i++) {
                    $rand_string=$rand_string.chr(mt_rand( 97 ,122 ));            
                };
                $rand_string = $rand_string." ";
            };
            $rand_string = $rand_string."\n";
        
        
        
        DB::table('questions')->delete();
        $i = 0;
        while($i < 600) {
            Question::create(array(
                'question'=> substr($rand_string,mt_rand(0,30),mt_rand(30,50)),
                'option1'=> substr($rand_string,mt_rand(0,30),mt_rand(30,50)),
                'option2'=> substr($rand_string,mt_rand(0,30),mt_rand(30,50)),
                'option3'=> substr($rand_string,mt_rand(0,30),mt_rand(30,50)),
                'option4'=> substr($rand_string,mt_rand(0,30),mt_rand(30,50)),
                'option5'=> substr($rand_string,mt_rand(0,30),mt_rand(30,50)),
                'numoptions'=>5,
                'answer'=>mt_rand(1,5)

            ));
            $i++;
        }
        */
        
        DB::table('questions')->delete();     
        
        
        DB::table('exams')->delete();
        DB::table('exams_questions')->delete();
        
        $exam_id_ref = array(2011=>null, 2012=>null, 2013=>null);
        
        DB::insert('insert into exams (shortname, longname, description) values (?, ?, ?)',array("MIR2011","Examen MIR 2011","Examen MIR oficial del año 2011"));
        $exam_id_ref[2011] = DB::getPdo()->LastInsertId();
        DB::insert('insert into exams (shortname, longname, description) values (?, ?, ?)',array("MIR2012","Examen MIR 2012","Examen MIR oficial del año 2012"));
        $exam_id_ref[2012] = DB::getPdo()->LastInsertId();
        DB::insert('insert into exams (shortname, longname, description) values (?, ?, ?)',array("MIR2013","Examen MIR 2013","Examen MIR oficial del año 2013"));
        $exam_id_ref[2013] = DB::getPdo()->LastInsertId();
        
        $rows = file('./app/database/seeds/questions/questions.csv');
        
        foreach($rows as $row) {
            //echo $row."\n";
            $columns = explode("|",trim($row));
            
            if(array_key_exists($columns[8],$exam_id_ref)) {
                //var_dump(array($columns[8],$exam_id_ref[$columns[8]]));
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
                DB::insert('insert into exams_questions (exam_id, question_id) values (?, ?)', array($exam_id_ref[$columns[8]], $new_question_id));
                
                $rand_concepts = DB::table('concepts')->orderBy(DB::raw('RAND()'))->take(5)->get();
                foreach ($rand_concepts as $concept) {
                   DB::insert('insert into concepts_questions (concept_id, question_id) values (?, ?)', array($concept->id, $new_question_id)); 
                }
                
            }
        }
        
    }
}

/*
 *                 $table->increments('id');
                
                $table->string('question',320);
                $table->string('option1',320);
                $table->string('option2',320);
                $table->string('option3',320)->nullable();
                $table->string('option4',320)->nullable();
                $table->string('option5',320)->nullable();
                $table->integer('numoptions');
                $table->integer('answer');
                $table->timestamps();
 */