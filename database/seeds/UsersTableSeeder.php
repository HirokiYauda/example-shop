<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        App\User::create([
            'name' => 'Hiroki Yasuda',
            'email' => 'yas.hir512@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
