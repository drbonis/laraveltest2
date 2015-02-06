<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('terms', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
                        $table->string('cui',8);
                        $table->string('aui',8);
                        $table->string('meshcode',8);
                        $table->string('str',320)->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('terms');
	}

}
