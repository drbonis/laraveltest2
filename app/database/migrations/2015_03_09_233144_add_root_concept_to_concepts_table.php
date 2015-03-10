<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRootConceptToConceptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            DB::delete('delete from concepts where cui = "C1140135" AND aui = "A13402003" AND meshcode = "D1000048" AND str = "Todos los temas"');
            DB::delete('delete from terms where cui = "C1140135" AND aui = "A13402003" AND meshcode = "D1000048" AND str = "Todos los temas"');
            DB::insert('insert into concepts (created_at, updated_at, cui, aui, meshcode, str) values (now(), now(), "C1140135", "A13402003", "D1000048", "Todos los temas")');	
            DB::insert('insert into terms (created_at, updated_at, cui, aui, meshcode, str) values (now(), now(), "C1140135", "A13402003", "D1000048", "Todos los temas")');        
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            DB::delete('delete from concepts where cui = "C1140135" AND aui = "A13402003" AND meshcode = "D1000048" AND str = "Todos los temas"');
            DB::delete('delete from terms where cui = "C1140135" AND aui = "A13402003" AND meshcode = "D1000048" AND str = "Todos los temas"');
	}

}
