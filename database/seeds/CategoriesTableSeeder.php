<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate(); //2回目実行の際にシーダー情報をクリア
        DB::table('categories')->insert([
            'id' => 1,
            'name' => "本",
            'name_en' => "book",
        ]);

        DB::table('categories')->insert([
            'id' => 2,
            'name' => "音楽",
            'name_en' => "music",
        ]);

        DB::table('categories')->insert([
            'id' => 3,
            'name' => "ゲーム",
            'name_en' => "game",
        ]);
    }
}
