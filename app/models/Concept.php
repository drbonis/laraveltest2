<?php

class Concept extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'concepts';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
         public function parents() {
             return $this->belongsToMany('Concept', 'concepts_concepts', 'aui', 'parentaui');
         }
         
         public function children() {
             return $this->belongsToMany('Concept', 'concepts_concepts', 'parentaui', 'aui');
         }

         public function questions() {
             return $this->belongsToMany('Concept');
         }
}
