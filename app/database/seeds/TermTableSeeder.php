<?php

class TermTableSeeder extends Seeder 
{
    public function run()
    {
        DB::table('terms')->delete();
        
        $filenames = array("./app/database/seeds/umls/2008AB/MRCONSO.RRF.aa.mshspa","./app/database/seeds/umls/2008AB/MRCONSO.RRF.ab.mshspa");

        foreach($filenames as $filename){
            $rows = file($filename);
            foreach($rows as $row) {
                $columns = explode("|", $row);
                Term::create(array(
                    'cui'=>$columns[0],
                    'aui'=>$columns[7],
                    'meshcode'=>$columns[10],
                    'str'=>$columns[14]));
            }
        }  
    }
}

