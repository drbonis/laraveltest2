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
                
                $table->string('question',320);
                $table->string('option1',320);
                $table->string('option2',320);
                $table->string('option3',320)->nullable();
                $table->string('option4',320)->nullable();
                $table->string('option5',320)->nullable();
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
