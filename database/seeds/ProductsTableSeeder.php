<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->truncate(); //2回目実行の際にシーダー情報をクリア
        DB::table('products')->insert([
            'name' => 'フィルムカメラ',
            'name_en' => 'film-camera',
            'admin_id' => 1,
            'genre_id' => 1,
            'detail' => '1960年式のカメラです',
            'search_tag' => 'フィルムカメラ film_camera',
            'price' => '200000',
            'stock' => 3,
            'imgpath' => 'filmcamera.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => 'イヤホン',
            'name_en' => 'iyahon',
            'admin_id' => 1,
            'genre_id' => 1,
            'detail' => 'ノイズキャンセリングがついてます',
            'search_tag' => 'イヤホン',
            'price' => '20000',
            'stock' => 3,
            'imgpath' => 'iyahon.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => '時計',
            'name_en' => 'tokei',
            'admin_id' => 1,
            'genre_id' => 1,
            'detail' => '1980年式の掛け時計です',
            'search_tag' => '時計',
            'price' => '120000',
            'stock' => 3,
            'imgpath' => 'clock.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => '地球儀',
            'name_en' => 'tikyugi',
            'admin_id' => 1,
            'genre_id' => 2,
            'detail' => '珍しい商品です',
            'search_tag' => '地球儀',
            'price' => '120000',
            'stock' => 3,
            'imgpath' => 'earth.jpg',
        ]);
 
 
        DB::table('products')->insert([
            'name' => '腕時計',
            'name_en' => 'udedokei',
            'admin_id' => 1,
            'genre_id' => 2,
            'detail' => 'プレゼントにどうぞ',
            'search_tag' => '腕時計',
            'price' => '9800',
            'stock' => 3,
            'imgpath' => 'watch.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => 'カメラレンズ35mm',
            'name_en' => 'camera-renzu35mm',
            'admin_id' => 1,
            'genre_id' => 3,
            'detail' => '最新式です',
            'search_tag' => 'カメラレンズ35mm',
            'price' => '79800',
            'stock' => 3,
            'imgpath' => 'lens.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => 'シャンパン',
            'name_en' => 'syanpan',
            'admin_id' => 1,
            'genre_id' => 3,
            'detail' => 'パーティにどうぞ',
            'search_tag' => 'シャンパン',
            'price' => '800',
            'stock' => 3,
            'imgpath' => 'shanpan.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => 'ビール',
            'name_en' => 'beer',
            'admin_id' => 1,
            'genre_id' => 4,
            'detail' => '大量生産されたビールです',
            'search_tag' => 'ビール',
            'price' => '200',
            'stock' => 3,
            'imgpath' => 'beer.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => 'やかん',
            'name_en' => 'yakan',
            'admin_id' => 1,
            'genre_id' => 4,
            'detail' => 'かなり珍しいやかんです',
            'search_tag' => 'やかん',
            'price' => '1200',
            'stock' => 3,
            'imgpath' => 'yakan.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => '精米',
            'name_en' => 'seimai',
            'admin_id' => 1,
            'genre_id' => 5,
            'detail' => '米30Kgです',
            'search_tag' => '精米',
            'price' => '11200',
            'stock' => 3,
            'imgpath' => 'kome.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => 'パソコン',
            'name_en' => 'pacokon',
            'admin_id' => 1,
            'genre_id' => 5,
            'detail' => 'ジャンク品です',
            'search_tag' => 'パソコン',
            'price' => '11200',
            'stock' => 3,
            'imgpath' => 'pc.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => 'アコースティックギター',
            'name_en' => 'acostic-guiter',
            'admin_id' => 1,
            'genre_id' => 6,
            'detail' => 'ヤマハ製のエントリーモデルです',
            'search_tag' => 'アコースティックギター',
            'price' => '25600',
            'stock' => 3,
            'imgpath' => 'aguiter.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => 'エレキギター',
            'name_en' => 'eleki-guiter',
            'admin_id' => 1,
            'genre_id' => 6,
            'detail' => '初心者向けのエントリーモデルです',
            'search_tag' => 'エレキギター',
            'price' => '15600',
            'stock' => 3,
            'imgpath' => 'eguiter.jpg',
        ]);
 
        DB::table('products')->insert([
            'name' => '加湿器',
            'name_en' => 'kasituki',
            'admin_id' => 1,
            'genre_id' => 7,
            'detail' => '乾燥する季節の必需品',
            'search_tag' => '加湿器',
            'price' => '3200',
            'stock' => 3,
            'imgpath' => 'steamer.jpeg',
        ]);
 
        DB::table('products')->insert([
            'name' => 'マウス',
            'name_en' => 'mouse',
            'admin_id' => 1,
            'genre_id' => 8,
            'detail' => 'ゲーミングマウスです',
            'search_tag' => 'マウス',
            'price' => '4200',
            'stock' => 3,
            'imgpath' => 'mouse.jpeg',
        ]);
 
        DB::table('products')->insert([
            'name' => 'Android Garxy10',
            'name_en' => 'Android-Garxy10',
            'admin_id' => 1,
            'genre_id' => 9,
            'detail' => '中古美品です',
            'search_tag' => 'Android Garxy10',
            'price' => '84200',
            'stock' => 3,
            'imgpath' => 'mobile.jpg',
        ]);
    }
}
