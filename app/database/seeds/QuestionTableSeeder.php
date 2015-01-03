<?php

class QuestionTableSeeder extends Seeder 
{
    public function run()
    {
        DB::table('questions')->delete();
        Question::create(array(
            'question' => 'Texto de la primera pregunta',
            'option1' => 'Opcion primera. *',
            'option2' => 'Opción segunda',
            'option3' => 'Opción tercera',
            'option4' => 'Opción cuatro',
            'option5' => 'Opción quinta',
            'numoptions' => 5,
            'answer' => 1
        ));
        
        Question::create(array(
            'question' => 'Texto de la segunda pregunta',
            'option1' => 'Opcion primera',
            'option2' => 'Opción segunda',
            'option3' => 'Opción tercera',
            'option4' => 'Opción cuatro *',
            'option5' => 'Opción quinta',
            'numoptions' => 5,
            'answer' => 4
        ));
        
        Question::create(array(
            'question' => 'Texto de la tercera pregunta',
            'option1' => 'Opcion primera',
            'option2' => 'Opción segunda',
            'option3' => 'Opción tercera',
            'option4' => 'Opción cuatro *',
            'option5' => 'Opción quinta',
            'numoptions' => 5,
            'answer' => 4
        ));        
        
        Question::create(array(
            'question' => 'Texto de la cuarta pregunta',
            'option1' => 'Opcion primera',
            'option2' => 'Opción segunda',
            'option3' => 'Opción tercera',
            'option4' => 'Opción cuatro *',
            'option5' => 'Opción quinta',
            'numoptions' => 5,
            'answer' => 4
        ));        

        Question::create(array(
            'question' => 'Texto de la quinta pregunta',
            'option1' => 'Opcion primera',
            'option2' => 'Opción segunda',
            'option3' => 'Opción tercera',
            'option4' => 'Opción cuatro *',
            'option5' => 'Opción quinta',
            'numoptions' => 5,
            'answer' => 4
        ));        

        Question::create(array(
            'question' => 'Texto de la sexta pregunta',
            'option1' => 'Opcion primera',
            'option2' => 'Opción segunda',
            'option3' => 'Opción tercera',
            'option4' => 'Opción cuatro *',
            'option5' => 'Opción quinta',
            'numoptions' => 5,
            'answer' => 4
        ));        

        Question::create(array(
            'question' => 'Texto de la séptima pregunta',
            'option1' => 'Opcion primera',
            'option2' => 'Opción segunda *',
            'option3' => 'Opción tercera',
            'option4' => 'Opción cuatro ',
            'option5' => 'Opción quinta',
            'numoptions' => 5,
            'answer' => 2
        ));

        Question::create(array(
            'question' => 'Texto de la octava pregunta',
            'option1' => 'Opcion primera',
            'option2' => 'Opción segunda',
            'option3' => 'Opción tercera',
            'option4' => 'Opción cuatro *',
            'option5' => 'Opción quinta',
            'numoptions' => 5,
            'answer' => 4
        ));        

        Question::create(array(
            'question' => 'Texto de la novena pregunta',
            'option1' => 'Opcion primera',
            'option2' => 'Opción segunda',
            'option3' => 'Opción tercera',
            'option4' => 'Opción cuatro',
            'option5' => 'Opción quinta *',
            'numoptions' => 5,
            'answer' => 5
        ));        

        Question::create(array(
            'question' => 'Texto de la décima pregunta',
            'option1' => 'Opcion primera *',
            'option2' => 'Opción segunda',
            'option3' => 'Opción tercera',
            'option4' => 'Opción cuatro',
            'option5' => 'Opción quinta',
            'numoptions' => 5,
            'answer' => 1
        ));        
        
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