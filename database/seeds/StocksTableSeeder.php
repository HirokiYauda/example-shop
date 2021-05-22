<?php

use Illuminate\Database\Seeder;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stocks')->truncate(); //2回目実行の際にシーダー情報をクリア
        DB::table('stocks')->insert([
            'name' => 'フィルムカメラ',
            'detail' => '1960年式のカメラです',
            'fee' => 200000,
            'imgpath' => 'filmcamera.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'イヤホン',
            'detail' => 'ノイズキャンセリングがついてます',
            'fee' => 20000,
            'imgpath' => 'iyahon.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => '時計',
            'detail' => '1980年式の掛け時計です',
            'fee' => 120000,
            'imgpath' => 'clock.jpg',
        ]);
    }
}
