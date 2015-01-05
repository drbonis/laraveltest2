<?php

class Answer extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'answers';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
        
        public function questions() {
            $this->belongsTo('Question');
        }
        
        public function users() {
            $this->belongsTo('User');
        }
        
        public function exams() {
            $this->belongsTo('Exam');
        }

}