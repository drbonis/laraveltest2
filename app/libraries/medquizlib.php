<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of medquizlib
 *
 * @author jbonis_fcsai
 */
class medquizlib {
    //put your code here

    
    static function getConceptsFromText($mytext) {
            //gets the list of concepts that are included in the text


            $r = explode(" ",str_replace([",",".","¿","?","¡","!","#","$","&","/","(",")",";",":","*","{","}","<",">","="]," ",$mytext));
            
            //remove repeated spaces
            $r2 = array();
            foreach($r as $word) {
                if($word!=""){
                    $r2[] = $word;
                }
            }
            $r = $r2;

            $word_list = array();            
            $maxsize = count($r)+1;
            $size = min([count($r)+1,3]);
            while ($size>1) {
                $origin = 0;
                while($size+$origin<=$maxsize) {
                    $word_list[] = array_slice($r, $origin, $size-1);
                    $origin++;
                }
                $size--;
            }
            
            $list_to_search = array();

            foreach($word_list as $word_set) {
                $str_to_search = "";
                foreach($word_set as $word) {
                    $str_to_search = $str_to_search." ".$word;
                }
                $str_to_search = trim($str_to_search);
                $list_to_search[] = $str_to_search;
            }
            
            $output = array();
            foreach($list_to_search as $search_str) {
                $db_output = DB::select("SELECT terms.id AS term_id, terms.cui AS cui, terms.aui AS aui, terms.meshcode AS meshcode, concepts.id AS concept_id FROM homestead.terms, homestead.concepts where terms.str = ? AND terms.cui = concepts.cui",array($search_str));
                if(count($db_output)>0) {
                    $output[] = $db_output[0];
                };
            }
            return json_encode($output);
    }
    
    static function getDescendants($cui) {
        /*given a cui returns a list of array(cui,str) that are descendants of cui*/
        $r = DB::select('select distinct aui from terms where cui = ?', array($cui));
        $children_cuis = array();
        $children_cuis_ref = array();
        foreach($r as $term) {
            $children = DB::select('select distinct concepts.cui as cui, concepts.str as str from concepts left join concepts_concepts ON concepts.cui = concepts_concepts.cui where concepts_concepts.auihier like ? group by concepts_concepts.cui;',array("%".$term->aui."%"));
            foreach($children as $child) {
                if(!in_array($child->cui, $children_cuis_ref)) {
                    $children_cuis_ref[] = $child->cui;
                    $children_cuis[] = array("cui"=>$child->cui, "str"=>$child->str);
                }
            }
        }
        return json_encode($children_cuis);
    }
    
    static function getAscendants($cui) {
        /*given a cui returns a list of all its ascendants*/
        $rel_list = DB::select('select auihier from concepts_concepts where cui = ? ',array($cui));
        $aui_parents_list = array();
        foreach ($rel_list as $rel) {
            $explode_auihier = explode(".", $rel->auihier);
            foreach ($explode_auihier as $new_aui) {
                if(!in_array($new_aui, $aui_parents_list)) {
                    $aui_parents_list[] = $new_aui;
                }
            }
        }

        $ascendants_cuis = array();
        $ascendants_cuis_ref = array();
        foreach($aui_parents_list as $new_aui) {
            $new_cui = DB::select('select cui, str from terms where aui = ?',array($new_aui));

            if(count($new_cui)>0) {
                if(!in_array($new_cui[0]->cui, $ascendants_cuis_ref)) {
                    $ascendants_cuis_ref[] = $new_cui[0]->cui;
                    $ascendants_cuis[] = array("cui"=>$new_cui[0]->cui, "str"=>$new_cui[0]->str);
                }
            }
        }
        return json_encode($ascendants_cuis);
        
    }
        

        
    static function getChildren($cui) {
        /*from a cui returns the list of first degree descendants (children)*/
        $r = DB::select('select count(t.aui) as numaui, con.cui, con.str from terms as t right join concepts_concepts as c on t.aui = c.parentaui right join concepts as con on c.cui = con.cui where t.cui = ? group by con.cui, con.str order by con.str;',array($cui));
        return json_encode($r);
    }
        
    static function getAnswersFromQuestion($question_id, $user_id = null) {
        if($user_id != null) {
                $r= DB::select('select user_id, question_id, SUM(answered = correct_answer) as num_right, COUNT(answered) as num_answered, SUM(CASE WHEN answered = 0 THEN 1 ELSE 0 END) AS num_blank FROM answers WHERE user_id = ? AND question_id = ? GROUP BY question_id',array($user_id, $question_id));    
        } else {
                $r= DB::select('select question_id, SUM(answered = correct_answer) as num_right, COUNT(answered) as num_answered, SUM(CASE WHEN answered = 0 THEN 1 ELSE 0 END) AS num_blank FROM answers WHERE question_id = ? GROUP BY question_id',array($question_id));
                if(count($r)==0) {$r[0] = new stdClass();};
                $r[0]->user_id = "";
        }
        if(count($r)==0) {
            $r[] = "";
        }
        return $r;    
    }
        
    static function getFreqOfConcept($cui) {
        /* from a given cui calculate the proportion of questions
         * that have this exact concept 
         */

        $questions_with_concept = DB::select('SELECT question_id FROM concepts_questions WHERE cui = ? GROUP BY concepts_questions.question_id ORDER BY question_id', array($cui));
        $questions_with_concept_count = count($questions_with_concept);
        $questions_total = DB::select('SELECT COUNT(*) AS count FROM questions')[0];

        $freq = $questions_with_concept_count / (int) $questions_total->count;
        return json_encode($freq);
    }
    
    static function getQuestionsFromConcept($cui,$option='direct') {
        /* given a cui returns a json:
         * [
         *  'direct': [cui1,cui2...],
         *  'descendants': [cui1, cui2, cui3...]
         * ]
         * 
         * direct includes all the questions that have $cui concept
         * descendants includes all the questions that have any descendant of $cui
         * 
         * if $option = 'direct' only retrieve direct concepts to improve performance
         */

        $direct_list = array();
        $direct_query = DB::select('select distinct question_id from concepts_questions where cui = ? order by question_id;',array($cui));
        if(count($direct_query)>0) {
            foreach($direct_query as $direct_query_row) {
                $direct_list[] = $direct_query_row->question_id;
            }
        }         
        if($option=='desc') {        
            $descendants_list = array();
            $descendants_cui_list = json_decode(medquizlib::getDescendants($cui));
            if(count($descendants_cui_list)>0) {
                foreach($descendants_cui_list as $descendants_cui_list_element) {
                    $descendants_query = DB::select('select distinct question_id from concepts_questions where cui = ? order by question_id;',array($descendants_cui_list_element->cui));
                    if(count($descendants_query)>0) {
                        foreach($descendants_query as $descendants_query_row) {
                            if(!in_array($descendants_query_row->question_id,$descendants_list)){
                                $descendants_list[] = $descendants_query_row->question_id;
                            }

                        }
                    }
                }
            }            
        } else {
            $descendants_list = array();
        }    

        $r = ["direct"=>$direct_list, "descendant"=>$descendants_list];
        //return json_encode($r);
        return json_encode($r);
    }
    
    static function getConceptsFromQuestion($question_id) {
        /*
         * given a question_id gets the list of concepts as json
         * [
         *  'direct':[cui1, cui2...],
         *  'descendants':[cui1, cui2...],
         *  'ascendants':[cui1, cui2...]
         * ]
         *
         */
        $r = array();
        $results_query = DB::select('select distinct cui from concepts_questions where question_id = ? order by cui',array($question_id));
        if(count($results_query)>0) {
            foreach($results_query as $results_query_row) {
                if(!in_array($results_query_row->cui, $r)){
                    $r[] = $results_query_row->cui;
                }
            }
        }
        return json_encode($r);
    }
    
    static function getSimilarQuestions($question_id) {
        /*given a question_id return a list of similar questions
         * based on their list of direct concepts
         */
        
        //first get the list of concepts for the question_id
        $concepts_array = json_decode(medquizlib::getConceptsFromQuestion($question_id));
        
        //now get the proportion of questions that have each concept and
        //select the X less frequent concepts (to improve efficiency of the algorithm)

        $concepts_associated_array = array();
        
        
        foreach($concepts_array as $concepts_array_element) {
            
            $questions_to_compare = json_decode(medquizlib::getQuestionsFromConcept($concepts_array_element));

            $concepts_associated_array[$concepts_array_element] = json_decode(medquizlib::getFreqOfConcept($concepts_array_element));
        }
        arsort($concepts_associated_array);

        $concepts_associated_array = array_slice($concepts_associated_array,0,9999); //set X

        //get the whole list of questions that hava any of the 10 concepts selected
        
        $questions_to_compare_array = array();
        
        foreach($concepts_associated_array as $concept_cui => $concept_freq) {
            
            $questions_to_compare = json_decode(medquizlib::getQuestionsFromConcept($concept_cui));
            
            foreach($questions_to_compare->direct as $questions_to_compare_element){
                if(!in_array($questions_to_compare_element,$questions_to_compare_array)) {
                    $questions_to_compare_array[] = $questions_to_compare_element;
                }
            }
        }
        
        
        //foreach question get its direct concepts, and check how
        //many of them are common with reference question
        //ponderated with the inverse of the frequency
        $questions_result_associated = array();
        
        foreach($questions_to_compare_array as $question_to_compare) {
            $similarity_score = 0;
            $question_to_compare_concepts = json_decode(medquizlib::getConceptsFromQuestion($question_to_compare));
            foreach($question_to_compare_concepts as $question_to_compare_concepts_element){
                if(array_key_exists($question_to_compare_concepts_element, $concepts_associated_array)) {
                    $similarity_score += 1 / $concepts_associated_array[$question_to_compare_concepts_element];
                }
            }
            $questions_result_associated[$question_to_compare] = $similarity_score;
        }
        arsort($questions_result_associated);
        return json_encode($questions_result_associated);
        
        
        

        
    }
    
    static function getSimilarQuestionsBeta($question_id) {
        /*given a question_id return a list of similar questions
         * based on their list of concepts
         */
        
        //first get the list of concepts for the question_id
        
        $r = array();
        $r['question_id'] = $question_id;
        $concepts_query = DB::select('select concepts.cui as cui, concepts.str as str FROM concepts_questions, concepts WHERE concepts_questions.question_id = ? AND concepts_questions.concept_id = concepts.id GROUP BY concepts.id',array($question_id));
        
        $concepts_list = array();
        $concepts_list_ref = array();
        
        foreach($concepts_query as $concept_results_query) {
            if(!in_array($concept_results_query->cui,$concepts_list_ref)) {
                $concepts_list_ref[] = $concept_results_query->cui;
                $concepts_list[] = array('cui'=>$concept_results_query->cui, 'str'=>$concept_results_query->str);
                $this_ascendants = json_decode(medquizlib::getAscendants($concept_results_query->cui));
                //var_dump("ASCENDANTS");
                //var_dump($this_ascendants);
                
                if(count($this_ascendants)>0) {
                    foreach($this_ascendants as $ascendant) {
                        if(!in_array($ascendant->cui,$concepts_list_ref)) {
                            $concepts_list_ref[] = $ascendant->cui;
                            $concepts_list[] = array('cui'=>$ascendant->cui, 'str'=>$ascendant->str);
                        }
                    }
                }
        
                $this_descendants = json_decode(medquizlib::getDescendants($concept_results_query->cui));
                if(count($this_descendants)>0) {
                    foreach($this_descendants as $descendant) {
                        if(!in_array($descendant->cui,$concepts_list_ref)) {
                            $concepts_list_ref[] = $descendant->cui;
                            $concepts_list[] = array('cui'=>$descendant->cui, 'str'=>$descendant->str);
                        }
                    }
                }
                
            }

            
        }
        foreach($concepts_list as $key=>$concept) {
            $concepts_list[$key]['freq'] = medquizlib::getFreqOfConcept($concept['cui']);
        }
        var_dump($concepts_list);
        
        
    }
        
}
