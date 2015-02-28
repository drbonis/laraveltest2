<?php

class UserTableSeeder extends Seeder 
{
    public function run()
    {
        DB::table('users')->delete();

        for($i=0;$i<20;$i++){
            User::create(array(
               'name' => 'Usuario '.$i,
               'username' => 'user'.$i,
               'email' => 'user'.$i.'@localhost.com',
               'password' => Hash::make('mysecret2014')
            ));
            
        }

        
    }
}

