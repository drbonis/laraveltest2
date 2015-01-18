<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptsQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
            Schema::create('concepts_questions', function(Blueprint $table)
            {
                $table->increments('id');     
                $table->integer('concept_id')->unsigned();
                $table->integer('question_id')->unsigned();
                $table->timestamps();
                
                //$table->foreign('concept_id')->references('id')->on('concepts');
                //$table->foreign('question_id')->references('id')->on('questions');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
            Schema::drop('concepts_questions');
	}

}
