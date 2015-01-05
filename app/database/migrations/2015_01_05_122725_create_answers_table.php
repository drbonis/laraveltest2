<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
            Schema::create('answers', function(Blueprint $table)
            {
                $table->increments('id');     
                $table->integer('exam_id')->unsigned();
                $table->integer('question_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->timestamps();
                $table->integer('option');
                $table->integer('correct_option');
                
                $table->foreign('exam_id')->references('id')->on('exams');
                $table->foreign('question_id')->references('id')->on('questions');
                $table->foreign('user_id')->references('id')->on('users');
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
            Schema::drop('answers');
	}

}
