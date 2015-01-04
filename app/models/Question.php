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

}
