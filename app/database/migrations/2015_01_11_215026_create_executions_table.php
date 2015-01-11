<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExecutionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('executions', function(Blueprint $table)
            {
                $table->increments('id');     
                $table->integer('exam_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->integer('total_questions_count');
                $table->integer('correct_questions_count');
                $table->integer('incorrect_questions_count');
                $table->timestamps();

                
                $table->foreign('exam_id')->references('id')->on('exams');
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
		Schema::table('executions', function(Blueprint $table)
		{
			//
                    Schema::drop('executions');
		});
	}

}
