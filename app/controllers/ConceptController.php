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
    
        private function responseFacade($r,$json='') {
            if($json=='json') {
                var_dump('json');
                return Response::json($r);
            } else {
                return $r;
            }
        }
    
        public function getTermByStr($str) {
            $r = DB::select('select cui, aui, meshcode, str from terms where str LIKE ?',array("%".$str."%"));
            $r2 = array();
            foreach($r as $row) {
                $r2[] = array("value"=>$row->str, "data"=>$row->cui);
            }
            return json_encode($r2);
        }
        
        public function getConceptFromAui($aui,$json='') {
            /*
             * ["aui":, "cui":, "str":]
             */
            $a_query = DB::select('SELECT terms.aui, concepts.cui, concepts.str FROM terms left join concepts on terms.cui = concepts.cui where homestead.terms.aui = ?',array($aui));
            if(count($a_query)>0){
                $r = array("aui"=>$a_query[0]->aui,"cui"=>$a_query[0]->cui,"str"=>$a_query[0]->str);
            } else {
                $r = array("aui"=>"","cui"=>"","str"=>"");
            }
            
            return $this->responseFacade($r,$json);
        }
        
        /*public function getAscendants($cui) {
            return Response::json(json_decode(medquizlib::getAscendants($cui)));
        }*/
        
        /*public function selectConcept(){
            return View::make('concept.show');
        }*/
  
        public function getAscendantsFromCui($cui,$json='') {
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
            
            $auihier_query_results = DB::select('select auihier from concepts_concepts where cui = ?',array($cui));
            //var_dump($auihier_query_results);
            $aui_list = array();
            foreach($auihier_query_results as $auihier_set) {
                $new_ascendant_chain = array();
                $this_aui_list = explode(".",$auihier_set->auihier);
                foreach($this_aui_list as $aui) {
                    //var_dump($aui);
                    if(key_exists($aui,$aui_list)) {
                        $new_ascendant_chain[] = array('cui'=>$aui_list[$aui]['cui'],'str'=>$aui_list[$aui]['str']);
                    } else {
                        $concept_info = ConceptController::getConceptFromAui($aui);
                        //var_dump($concept_info);
                        $aui_list[$aui] = array('cui'=>$concept_info['cui'], 'str'=>$concept_info['str']);    
                        $new_ascendant_chain[] = array('cui'=>$aui_list[$aui]['cui'],'str'=>$aui_list[$aui]['str']);
                        
                    }
                  
                }
                $r['ascendants'][] = $new_ascendant_chain;
            } 

            return $this->responseFacade($r,$json);
            //return Response::json($auihier_query_results);
            //return Response::json($r);
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
