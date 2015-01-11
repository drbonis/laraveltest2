<?php

class Question extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'questions';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
        
        public function scopeRandom($query)
        {
            return $query->orderBy(DB::raw('RAND()'));
        }
        
        public function exams() {
            return $this->belongsToMany('Exam');
        }
        
        public function answers() {
            return $this->belongsToMany('Answer');
        }
        
        public function concepts() {
            return $this->belongsToMany('Concepts');
        }

}
