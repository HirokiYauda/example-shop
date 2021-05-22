<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->truncate(); //2回目実行の際にシーダー情報をクリア
        DB::table('users')->insert([
            'name' => 'Hiroki Yasuda',
            'email' => 'yas.hir512@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
