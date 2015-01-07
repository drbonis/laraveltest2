<?php

class ExamTableSeeder extends Seeder 
{

    public function run()
    {
        
        
        DB::table('exams')->delete();
        DB::table('exams_questions')->delete();
        
        /*Exam::create(array(
           'shortname'=> 'MIR2008',
           'longname'=> 'Examen MIR 2008',
            'description'=> 'Examen MIR oficial del año 2008'
        ));*/
        
        DB::insert('insert into exams (shortname, longname, description) values (?, ?, ?)',array("MIR2008","Examen MIR 2008","Examen MIR oficial del año 2008"));
        
        $i = 1;
        while($i < 130) {
            DB::insert('insert into exams_questions (exam_id, question_id) values (?, ?)',array(1,$i));
            $i++;
        }
        
        
    }
}
