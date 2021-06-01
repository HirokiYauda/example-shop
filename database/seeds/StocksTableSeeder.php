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
            'name_en' => 'film-camera',
            'genre_id' => 1,
            'detail' => '1960年式のカメラです',
            'search_tag' => 'フィルムカメラ film_camera',
            'price' => 200000,
            'imgpath' => 'filmcamera.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'イヤホン',
            'name_en' => 'iyahon',
            'genre_id' => 1,
            'detail' => 'ノイズキャンセリングがついてます',
            'search_tag' => 'イヤホン',
            'price' => 20000,
            'imgpath' => 'iyahon.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => '時計',
            'name_en' => 'tokei',
            'genre_id' => 1,
            'detail' => '1980年式の掛け時計です',
            'search_tag' => '時計',
            'price' => 120000,
            'imgpath' => 'clock.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => '地球儀',
            'name_en' => 'tikyugi',
            'genre_id' => 2,
            'detail' => '珍しい商品です',
            'search_tag' => '地球儀',
            'price' => 120000,
            'imgpath' => 'earth.jpg',
        ]);
 
 
        DB::table('stocks')->insert([
            'name' => '腕時計',
            'name_en' => 'udedokei',
            'genre_id' => 2,
            'detail' => 'プレゼントにどうぞ',
            'search_tag' => '腕時計',
            'price' => 9800,
            'imgpath' => 'watch.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'カメラレンズ35mm',
            'name_en' => 'camera-renzu35mm',
            'genre_id' => 3,
            'detail' => '最新式です',
            'search_tag' => 'カメラレンズ35mm',
            'price' => 79800,
            'imgpath' => 'lens.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'シャンパン',
            'name_en' => 'syanpan',
            'genre_id' => 3,
            'detail' => 'パーティにどうぞ',
            'search_tag' => 'シャンパン',
            'price' => 800,
            'imgpath' => 'shanpan.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'ビール',
            'name_en' => 'beer',
            'genre_id' => 4,
            'detail' => '大量生産されたビールです',
            'search_tag' => 'ビール',
            'price' => 200,
            'imgpath' => 'beer.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'やかん',
            'name_en' => 'yakan',
            'genre_id' => 4,
            'detail' => 'かなり珍しいやかんです',
            'search_tag' => 'やかん',
            'price' => 1200,
            'imgpath' => 'yakan.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => '精米',
            'name_en' => 'seimai',
            'genre_id' => 5,
            'detail' => '米30Kgです',
            'search_tag' => '精米',
            'price' => 11200,
            'imgpath' => 'kome.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'パソコン',
            'name_en' => 'pacokon',
            'genre_id' => 5,
            'detail' => 'ジャンク品です',
            'search_tag' => 'パソコン',
            'price' => 11200,
            'imgpath' => 'pc.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'アコースティックギター',
            'name_en' => 'acostic-guiter',
            'genre_id' => 6,
            'detail' => 'ヤマハ製のエントリーモデルです',
            'search_tag' => 'アコースティックギター',
            'price' => 25600,
            'imgpath' => 'aguiter.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'エレキギター',
            'name_en' => 'eleki-guiter',
            'genre_id' => 6,
            'detail' => '初心者向けのエントリーモデルです',
            'search_tag' => 'エレキギター',
            'price' => 15600,
            'imgpath' => 'eguiter.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => '加湿器',
            'name_en' => 'kasituki',
            'genre_id' => 7,
            'detail' => '乾燥する季節の必需品',
            'search_tag' => '加湿器',
            'price' => 3200,
            'imgpath' => 'steamer.jpeg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'マウス',
            'name_en' => 'mouse',
            'genre_id' => 8,
            'detail' => 'ゲーミングマウスです',
            'search_tag' => 'マウス',
            'price' => 4200,
            'imgpath' => 'mouse.jpeg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'Android Garxy10',
            'name_en' => 'Android-Garxy10',
            'genre_id' => 9,
            'detail' => '中古美品です',
            'search_tag' => 'Android Garxy10',
            'price' => 84200,
            'imgpath' => 'mobile.jpg',
        ]);
    }
}
