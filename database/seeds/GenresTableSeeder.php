<?php

use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->truncate(); //2回目実行の際にシーダー情報をクリア
        DB::table('genres')->insert([
            'id' => 1,
            'category_id' => 1,
            'name' => "コミック",
            'name_en' => "comic",
        ]);

        DB::table('genres')->insert([
            'id' => 2,
            'category_id' => 1,
            'name' => "雑誌",
            'name_en' => "zassi",
        ]);

        DB::table('genres')->insert([
            'id' => 3,
            'category_id' => 1,
            'name' => "絵本",
            'name_en' => "ehon",
        ]);

        DB::table('genres')->insert([
            'id' => 4,
            'category_id' => 2,
            'name' => "クラシック",
            'name_en' => "clasic",
        ]);

        DB::table('genres')->insert([
            'id' => 5,
            'category_id' => 2,
            'name' => "ジャズ",
            'name_en' => "jaz",
        ]);

        DB::table('genres')->insert([
            'id' => 6,
            'category_id' => 2,
            'name' => "ロック",
            'name_en' => "rock",
        ]);

        DB::table('genres')->insert([
            'id' => 7,
            'category_id' => 3,
            'name' => "アクション",
            'name_en' => "action",
        ]);

        DB::table('genres')->insert([
            'id' => 8,
            'category_id' => 3,
            'name' => "ロールプレイング",
            'name_en' => "rpg",
        ]);

        DB::table('genres')->insert([
            'id' => 9,
            'category_id' => 3,
            'name' => "シューティング",
            'name_en' => "shooting",
        ]);
    }
}
