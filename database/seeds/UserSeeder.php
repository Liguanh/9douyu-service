<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {

    public function run()
    {
        app('db')->table('oauth_users')->delete();

        $user = app()->make('App\User');
        $hasher = app()->make('hash');

        $user->fill([
            'name' => 'BiBiHub',
            'email' => 'toadgg@bibihub.com',
            'password' => $hasher->make('123456')
        ]);
        $user->save();
    }

}