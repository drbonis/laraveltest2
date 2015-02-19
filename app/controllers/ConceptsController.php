<?php

class ConceptsController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

    
        public function getTermByStr($str) {
            $r = DB::select('select cui, aui, meshcode, str from terms where str LIKE ?',array("%".$str."%"));
            $r2 = array();
            foreach($r as $row) {
                $r2[] = array("value"=>$row->str, "data"=>$row->cui);
            }
            return Response::json($r2);
        }
        
        public function getAscendants($cui) {
            return Response::json(json_decode(medquizlib::getAscendants($cui)));
        }
        
        public function selectConcept(){
            return View::make('concept.show');
        }
  



}
