<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
                ini_set('memory_limit', '1024M');
            
		Eloquent::unguard();

		$this->call('UserTableSeeder');

                $this->call('ConceptTableSeeder');
                
                $this->call('TermTableSeeder');
                
                $this->call('QuestionTableSeeder');
                
                $this->call('AnswerTableSeeder');
                
                $this->call('ExamByconceptTableSeeder'); 
	}

}
