<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConconrelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('conconrels', function(Blueprint $table)
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
		Schema::drop('conconrels');
	}

}
