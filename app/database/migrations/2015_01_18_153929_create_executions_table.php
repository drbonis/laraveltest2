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
		//
            
            Schema::create('executions', function(Blueprint $table)
            {
                $table->increments('id');    
                $table->integer('exam_id')->unsigned();
                $table->integer('user_id')->unsigned();
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
            Schema::drop('executions');
	}

}
