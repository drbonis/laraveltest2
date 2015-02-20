<?php

class ConceptController extends BaseController {

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

        /*JSON functions */
    
    
        public function getTermByStr($str) {
            $r = DB::select('select cui, aui, meshcode, str from terms where str LIKE ?',array("%".$str."%"));
            $r2 = array();
            foreach($r as $row) {
                $r2[] = array("value"=>$row->str, "data"=>$row->cui);
            }
            return Response::json($r2);
        }
        
        public function getConceptFromAui($aui) {
            /*
             * ["aui":, "cui":, "str":]
             */
            $a_query = DB::select('SELECT terms.aui, concepts.cui, concepts.str FROM terms left join concepts on terms.cui = concepts.cui where homestead.terms.aui = ?',array($aui));
            if(count($a_query)>0){
                $r = array("aui"=>$a_query[0]->aui,"cui"=>$a_query[0]->cui,"str"=>$a_query[0]->str);
            } else {
                $r = array("aui"=>"","cui"=>"","str"=>"");
            }
            
            return Response::json($r);
        }
        
        /*public function getAscendants($cui) {
            return Response::json(json_decode(medquizlib::getAscendants($cui)));
        }*/
        
        /*public function selectConcept(){
            return View::make('concept.show');
        }*/
  
        public function getAscendantsFromCui($cui) {
            /*
             * output:
             * ["ref_cui":$cui,
             * "ascendants":
             *  [
             *      0:  [
             *              ["cui":"","str":""],["cui":"","str":""]...
             *          ],
             *      1:  [
             *              ["cui":"","str":""],["cui":"","str":""]...
             *          ],
             *      2:  [
             *              ["cui":"","str":""],["cui":"","str":""]...
             *          ]
             *          ...
             *  ]
             * ]
             */
            $r = array("ref_cui"=>$cui, "ascendants"=>array());
            
            $query_results = DB::select('select ',array($cui));
            
            return Response::json($r);
        }
        
        public function getDescendantsFromCuiTree($cui,$depth) {
            /*
             * 
             */
            return true;
        } 
        
        public function getDescendantsFromCuiAll($cui) {
            /* [
             *  "ref_cui":, 
             *  "descendants":
             *      [
             *          ["cui":,"str":],["cui":,"str":]...
             *      ]
             * ]
             */
            return true;
        }
        
        public function getQuestionsFromCui($cui) {
            return true;
        }
        
        public function getAnswersFromCuiUser($cui, $user_id, $option = "direct") {
            return true;
        }
        
        public function getAnswersFromCuiAllUsers($cui,$option = "direct") {
            return true;
        }
        
        public function getAnswersHistoryFromCui($cui, $user_id, $option = "direct") {
            return true;
        }
            


}
