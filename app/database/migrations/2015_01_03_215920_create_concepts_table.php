<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('concepts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
                        $table->string('cui',12)->index();
                        $table->string('aui',12);
                        $table->string('meshcode',12);
                        $table->string('str',320);
                        
                        
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('concepts');
	}

}
