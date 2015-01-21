<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptsConceptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('concepts_concepts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
                        $table->string('cui',8);
                        $table->string('aui',8);
                        $table->string('parentaui',8);
                        $table->string('auihier',320);
                        $table->string('meshhier',320);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('concepts_concepts');
	}

}
