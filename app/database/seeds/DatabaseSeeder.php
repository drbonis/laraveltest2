<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UserTableSeeder');
                
                $this->call('QuestionTableSeeder');
                
                $this->call('ConceptTableSeeder');
                
                $this->call('TermTableSeeder');
                
                //$this->call('ExamTableSeeder'); //integrated now in QuestionTableSeeder
	}

}
