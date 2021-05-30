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
            'category_id' => 1,
            'detail' => '1960年式のカメラです',
            'search_tag' => 'フィルムカメラ',
            'price' => 200000,
            'imgpath' => 'filmcamera.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'イヤホン',
            'category_id' => 1,
            'detail' => 'ノイズキャンセリングがついてます',
            'search_tag' => 'イヤホン',
            'price' => 20000,
            'imgpath' => 'iyahon.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => '時計',
            'category_id' => 2,
            'detail' => '1980年式の掛け時計です',
            'search_tag' => '時計',
            'price' => 120000,
            'imgpath' => 'clock.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => '地球儀',
            'category_id' => 2,
            'detail' => '珍しい商品です',
            'search_tag' => '地球儀',
            'price' => 120000,
            'imgpath' => 'earth.jpg',
        ]);
 
 
        DB::table('stocks')->insert([
            'name' => '腕時計',
            'category_id' => 3,
            'detail' => 'プレゼントにどうぞ',
            'search_tag' => '腕時計',
            'price' => 9800,
            'imgpath' => 'watch.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'カメラレンズ35mm',
            'category_id' => 3,
            'detail' => '最新式です',
            'search_tag' => 'カメラレンズ35mm',
            'price' => 79800,
            'imgpath' => 'lens.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'シャンパン',
            'category_id' => 1,
            'detail' => 'パーティにどうぞ',
            'search_tag' => 'シャンパン',
            'price' => 800,
            'imgpath' => 'shanpan.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'ビール',
            'category_id' => 1,
            'detail' => '大量生産されたビールです',
            'search_tag' => 'ビール',
            'price' => 200,
            'imgpath' => 'beer.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'やかん',
            'category_id' => 2,
            'detail' => 'かなり珍しいやかんです',
            'search_tag' => 'やかん',
            'price' => 1200,
            'imgpath' => 'yakan.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => '精米',
            'category_id' => 2,
            'detail' => '米30Kgです',
            'search_tag' => '精米',
            'price' => 11200,
            'imgpath' => 'kome.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'パソコン',
            'category_id' => 3,
            'detail' => 'ジャンク品です',
            'search_tag' => 'パソコン',
            'price' => 11200,
            'imgpath' => 'pc.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'アコースティックギター',
            'category_id' => 3,
            'detail' => 'ヤマハ製のエントリーモデルです',
            'search_tag' => 'アコースティックギター',
            'price' => 25600,
            'imgpath' => 'aguiter.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'エレキギター',
            'category_id' => 1,
            'detail' => '初心者向けのエントリーモデルです',
            'search_tag' => 'エレキギター',
            'price' => 15600,
            'imgpath' => 'eguiter.jpg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => '加湿器',
            'category_id' => 1,
            'detail' => '乾燥する季節の必需品',
            'search_tag' => '加湿器',
            'price' => 3200,
            'imgpath' => 'steamer.jpeg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'マウス',
            'category_id' => 2,
            'detail' => 'ゲーミングマウスです',
            'search_tag' => 'マウス',
            'price' => 4200,
            'imgpath' => 'mouse.jpeg',
        ]);
 
        DB::table('stocks')->insert([
            'name' => 'Android Garxy10',
            'category_id' => 2,
            'detail' => '中古美品です',
            'search_tag' => 'Android Garxy10',
            'price' => 84200,
            'imgpath' => 'mobile.jpg',
        ]);
    }
}
