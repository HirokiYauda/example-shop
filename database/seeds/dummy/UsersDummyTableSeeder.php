<?php

namespace Database\Seeds\Dummy;

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersDummyTableSeeder extends Seeder
{
    public function run()
    {
        ini_set('memory_limit', '1024M');
        DB::table('users')->truncate(); //2回目実行の際にシーダー情報をクリア
        $faker = Factory::create('ja_JP');
        $params = [];
        $register_count = 50000; // 何件登録するか | 1000単位でbulk insert
 
        for ($i=0; $i < $register_count; $i++) { 
            $addresies = explode('  ', $faker->address);
            $params[] = [ 
                'name' => $faker->name,
                'email' => "test$i@example.com",
                'zip' => $addresies[0],
                'pref_id' => $faker->numberBetween(1, 47),
                'address1' => $addresies[1],
                'address2' => null,
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'remember_token' => Str::random(10),
            ];
    
            if (count($params) >= 1000) {
                User::insert($params);
                $params = [];
            }
        }
    }
}
