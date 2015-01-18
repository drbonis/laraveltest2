<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::create('exams_questions', function(Blueprint $table)
            {
                $table->increments('id');     
                $table->integer('exam_id')->unsigned();
                $table->integer('question_id')->unsigned();
                $table->timestamps();
                
                //$table->foreign('exam_id')->references('id')->on('exams');
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
            Schema::drop('exams_questions');
	}

}
