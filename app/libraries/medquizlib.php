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
    static function holamundo() {
        return "hola mundo";
    }
    
    static function getConceptsFromText($mytext) {
            //gets the list of concepts that are included in the text
            function getCUI($text){
                $r = DB::table('terms')->where("str","=",$text)->take(1)->get();
                $rcui = $r[0]->cui;
                return $rcui;
                
            }

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
            $size = min([count($r)+1,10]);
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
                $db_output = DB::table('terms')->where("str","=",$search_str)->take(1)->get();
                if(count($db_output)>0) {
                    $output[] = $db_output;
                };
            }
            return json_encode($output);
        }
    
    
    
}
