<?php

class QuestionTableSeeder extends Seeder 
{

    public function run()
    {
        
        
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