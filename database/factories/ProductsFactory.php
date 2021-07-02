<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    // 商品名, 商品名EN
    $word = $faker->word;
    // 検索タグは、商品名を含むダミー配列
    $search_tag = $faker->words;
    $search_tag[] = $word;
    $search_tag = implode(' ', $search_tag);
    // 画像は固定
    $imgpath = 'filmcamera.jpg';

    return [
        'name' => $word,
        'name_en' => $word,
        'admin_id' => $faker->numberBetween(1, 6),
        'genre_id' => $faker->numberBetween(1, 9),
        'detail' => $faker->realText(),
        'search_tag' => $search_tag,
        'price' => $faker->numberBetween(500, 10000),
        'stock' => $faker->numberBetween(5, 20),
        'imgpath' => $imgpath,
        'created_at' => $faker->dateTimeThisDecade,
        'updated_at' => null,
    ];
});
