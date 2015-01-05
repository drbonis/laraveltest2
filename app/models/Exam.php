<?php

class Exam extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'exams';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
        
        public function questions() {
            $this->belongsToMany('Question');
        }

}
