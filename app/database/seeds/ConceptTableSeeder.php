<?php

class ConceptTableSeeder extends Seeder 
{
    public function run()
    {
        DB::table('concepts')->delete();
        
        $filenames = array("./app/database/seeds/umls/2008AB/MRCONSO.RRF.aa.mshspa.mh","./app/database/seeds/umls/2008AB/MRCONSO.RRF.ab.mshspa.mh");

        foreach($filenames as $filename){
            $rows = file($filename);
            foreach($rows as $row) {
                $columns = explode("|", $row);
                Concept::create(array(
                    'cui'=>$columns[0],
                    'aui'=>$columns[7],
                    'meshcode'=>$columns[10],
                    'str'=>$columns[14]));
            }
        }  
        
        DB::table('concepts_concepts')->delete();
        
        $filenames = array("./app/database/seeds/umls/2008AB/MRHIER.RRF.aa.mshspa","./app/database/seeds/umls/2008AB/MRHIER.RRF.ab.mshspa", "./app/database/seeds/umls/2008AB/MRHIER.RRF.ac.mshspa");
        //$time_now = date('Y-m-d H:i:s',time());
        foreach($filenames as $filename){
            echo $filename."\n";
            $rows = file($filename);
            foreach($rows as $row) {
                $columns = explode("|", $row);

                DB::insert('insert into concepts_concepts (cui, aui, parentaui, auihier, meshhier) values (?, ?, ?, ?, ?)', 
                    array($columns[0], $columns[1], $columns[3], $columns[6], $columns[7]));
            }
        }         
    }
}
