<?php

class UserTableSeeder extends Seeder 
{
    public function run()
    {
        DB::table('users')->delete();
        User::create(array(
           'name' => 'Juan García',
           'username' => 'juangarcia',
           'email' => 'jdrbonis@hotmail.com',
           'password' => Hash::make('mysecret2014')
        ));
        
        User::create(array(
           'name' => 'Pedro Sánchez',
            'username' => 'pedrosanchez',
            'email' => 'drbonis@gmail.com',
            'password' => Hash::make('mysecret2014')
        ));
    }
}

