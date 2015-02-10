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
                //$db_output = DB::table('terms')->where("str","=",$search_str)->take(1)->get();
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
            foreach($r as $term) {
                $children = DB::select('select distinct concepts.cui as cui, concepts.str as str from concepts left join concepts_concepts ON concepts.cui = concepts_concepts.cui where concepts_concepts.auihier like ? group by concepts_concepts.cui;',array("%".$term->aui."%"));
                foreach($children as $child) {
                    if(!in_array($child->cui, $children_cuis)) {
                        $children_cuis[] = array("cui"=>$child->cui, "str"=>$child->str);
                    }
                }
            }
            return json_encode($children_cuis);
        }
        
        static function getQuestions($cui) {
            /*given a cui returns the list of questions (question_id) that 
             includes this cui or its descendants*/
            $final_list = array();
            $this_list = DB::select('select distinct question_id from concepts_questions where cui =?',array($cui));
            foreach ($this_list as $this_element) {
                if(!in_array($this_element->question_id, $final_list)) {
                    $final_list[] = $this_element->question_id;
                }
            }
            $descendants = json_decode(medquizlib::getDescendants($cui));
            foreach($descendants as $descendant) {
                $this_list = DB::select('select distinct question_id from concepts_questions where cui =?',array($descendant->cui));
                foreach ($this_list as $this_element) {
                    if(!in_array($this_element->question_id, $final_list)) {
                        $final_list[] = $this_element->question_id;
                    }
                }
            }
            return json_encode($final_list);
        }
        
        
    
}
