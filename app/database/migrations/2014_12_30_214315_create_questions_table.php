<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
            Schema::create('questions', function(Blueprint $table)
            {
                $table->increments('id');
                
                $table->text('question');
                $table->text('option1');
                $table->text('option2');
                $table->text('option3')->nullable();
                $table->text('option4')->nullable();
                $table->text('option5')->nullable();
                $table->integer('numoptions');
                $table->integer('answer');
                $table->timestamps();
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
            Schema::drop('questions');
	}

}
