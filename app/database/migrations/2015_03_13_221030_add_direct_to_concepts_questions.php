<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDirectToConceptsQuestions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('concepts_questions', function(Blueprint $table)
		{
			//
                    $table->integer('direct')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('concepts_questions', function(Blueprint $table)
		{
			//
                    $table->dropColumn('direct');
		});
	}

}
