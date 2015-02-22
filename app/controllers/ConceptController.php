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
        
        public function getAllAuisFromConcept($cui,$json='') {
            $r = array();
            $a_query = DB::select('select aui from terms where cui = ?',array($cui));
            if(count($a_query)>0) {
                foreach($a_query as $a_query_element) {
                    $r[] = $a_query_element->aui;
                }
            } 
            
            return $this->responseFacade($r, $json);
        }
        
        public function getStrFromCui($cui,$json='') {
            $r = '';
            $query = DB::select('select str from concepts where cui = ?',array($cui));
            if(count($query)>0) {
                $r = $query[0]->str;
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
             * ["cui":$cui,
             *  "str":,
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
            $r = array("cui"=>$cui, "str"=>'',"ascendants"=>array());
            
            $concept_query_results = DB::select('select str from concepts where cui = ?',array($cui));
            
            $r['str'] = $concept_query_results[0]->str;
            
            $auihier_query_results = DB::select('select auihier from concepts_concepts where cui = ?',array($cui));
            //var_dump($auihier_query_results);
            $aui_list = array();
            foreach($auihier_query_results as $auihier_set) {
                $new_ascendant_chain = array();
                $this_aui_list = explode(".",$auihier_set->auihier);
                foreach($this_aui_list as $aui) {
                    if(key_exists($aui,$aui_list)) {
                        $new_ascendant_chain[] = array('cui'=>$aui_list[$aui]['cui'],'str'=>$aui_list[$aui]['str']);
                    } else {
                        $concept_info = ConceptController::getConceptFromAui($aui);
                        $aui_list[$aui] = array('cui'=>$concept_info['cui'], 'str'=>$concept_info['str']);    
                        $new_ascendant_chain[] = array('cui'=>$aui_list[$aui]['cui'],'str'=>$aui_list[$aui]['str']);
                        
                    }
                  
                }
                $r['ascendants'][] = $new_ascendant_chain;
            } 
            return $this->responseFacade($r,$json);
        }
        
        public function getAscendantsFromCuiAll($cui,$json='') {
            $r = array();
            $r['ascendants'] = array();
            $ascendants = $this->getAscendantsFromCui($cui);

            
            $r['cui'] = $ascendants['cui'];
            $r['str'] = $ascendants['str'];
            $new_ascendants = $ascendants['ascendants'];
            foreach($new_ascendants as $ascendants_list) {
                foreach($ascendants_list as $ascendant_element) {
                    if($ascendant_element['cui']!='' && !in_array($ascendant_element['cui'], $r['ascendants'])) {
                        $r['ascendants'][] = $ascendant_element['cui'];
                    }
                }
            }
            return $this->responseFacade($r,$json);
        }
        
        public function getDescendantsFromCuiTree($cui) {
            /*
             * [
             *  "cui":,
             *  "str":,
             *  "descendants":
             *      [
             *          ["cui":,"str":","descendants":[]]
             *          ["cui":,"str":","descendants":[]]
             *      ]
             * ] 
             */
            
            $r = array();
            $aui_ref_array = array();
            $cui_ref_array = array($cui);
            
            //while($depth>0) {

                while(count($cui_ref_array)>0) {
                    $this_auis = $this->getAllAuisFromConcept($cui_ref_array[0]);
                    $cui_descendants = array();
                    foreach($this_auis as $aui) {
                        if(!in_array($aui, $aui_ref_array)) {
                            $query_descendants = DB::select('select distinct concepts_concepts.cui, concepts.str from concepts, concepts_concepts where concepts_concepts.parentaui = ? and concepts.cui = concepts_concepts.cui;',array($aui));
                            if(count($query_descendants) >0) {
                                foreach($query_descendants as $descendant) {
                                    $cui_descendants[] = array("cui"=>$descendant->cui,"str"=>$descendant->str);
                                    $cui_ref_array[] = $descendant->cui;
                                    //array_shift($cui_ref_array);    
                                }
                            } 
                        } //else {
                            //array_shift($cui_ref_array);
                        //}
                        $aui_ref_array[] = $aui;
                    }
                    $r[] = array("cui"=>$cui_ref_array[0], "str"=>$this->getStrFromCui($cui_ref_array[0]), "descendants"=>$cui_descendants);
                    array_shift($cui_ref_array);
                }
                //$depth--;
            //}
            
             
            
            //var_dump($r);
            return json_encode($r);
        } 
        
        public function getDescendantsFromCuiAll($cui) {
            /* [
             *  "cui":,
             *  "str":, 
             *  "descendants":
             *      [
             *          ["cui":,"str":],["cui":,"str":]...
             *      ]
             * ]
             */
            $r = array("cui"=>$cui, "str"=>$this->getStrFromCui($cui), "descendants"=>array());
            
            
            $aui_ref_array = array();
            $cui_ref_array = array($cui);
            $cui_done_array = array();
  
            while(count($cui_ref_array)>0) {
                //var_dump(count($cui_ref_array));
                
                //var_dump(array("cuis_done"=>$cui_done_array));
                //var_dump(array("aui_ref_array" =>$aui_ref_array));
                if(!in_array($cui_ref_array[0],$cui_done_array)){
                    $cui_done_array[] = $cui_ref_array[0];
                    
                    //if(count($cui_done_array)<70) { //for debuging
                    
                        $this_auis = $this->getAllAuisFromConcept($cui_ref_array[0]);
                        
                        //$k = array();
                        //foreach($cui_ref_array as $k_cui) {
                        //    $k[] = array('cui'=>$k_cui, 'str'=>$this->getStrFromCui($k_cui));
                        //}
                        //var_dump(array("cui_ref_array"=>$k,"this_auis"=>$this_auis));

                        foreach($this_auis as $aui) {
                            if(!in_array($aui, $aui_ref_array)) {
                                //var_dump(array("new_aui"=>$aui));
                                $aui_ref_array[] = $aui;
                                $query_descendants = DB::select('select distinct concepts_concepts.cui, concepts.str from concepts, concepts_concepts where concepts_concepts.parentaui = ? and concepts.cui = concepts_concepts.cui;',array($aui));
                                if(count($query_descendants) >0) {
                                    foreach($query_descendants as $descendant) {
                                        $r['descendants'][] = array("cui"=>$descendant->cui,"str"=>$descendant->str);
                                        $cui_ref_array[] = $descendant->cui;
                                    }
                                } 
                            } 
                        }
                    //} //for debuggin (if
                }
                array_shift($cui_ref_array);
            }
            return json_encode($r);
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
            
        public function borrame($cui){
            return json_encode(medquizlib::getAscendantsFromCuiAll($cui));
        }

}