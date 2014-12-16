<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});



Route::get('usuarios', 'UsuariosController@mostrarUsuarios');

Route::get('createtable', function(){
	DB::transaction(function(){
		DB::statement('CREATE TABLE IF NOT EXISTS preguntas (id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, created_at TIMESTAMP, updated_at TIMESTAMP, enunciado VARCHAR(255), opcion_a VARCHAR(255), opcion_b VARCHAR(255), opcion_c VARCHAR(255), opcion_d VARCHAR(255), opcion_e VARCHAR(255)  CHARACTER SET utf8 COLLATE utf8_unicode_ci)');
		//DB::insert('INSERT INTO preguntas (enunciado, opcion_a, opcion_b, opcion_c, opcion_d, opcion_e) VALUES (?,?,?,?,?,?)',array('Este sería el enunciado','La primera opción', 'La segunda', 'Tercera opción', 'Cuarta alternativa', 'Quinta respuesta posible'));
		//$r = DB::select('SELECT * FROM preguntas');
		$r = Pregunta::all();
		dd($r);
	});

});
