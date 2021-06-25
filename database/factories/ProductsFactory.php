<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    // 商品名, 商品名EN
    $word = $faker->unique()->word;
    // 検索タグは、商品名を含むダミー配列
    $search_tag = $faker->unique()->words;
    $search_tag[] = $word;
    $search_tag = implode(' ', $search_tag);
    // 文章は、日本語を含むダミー段落
    $paragraphs = $faker->paragraphs();
    $realText = $faker->realText();
    array_unshift($paragraphs, $realText);
    $paragraphs = implode("\n", $paragraphs);
    // 画像は固定
    $imgpath = 'filmcamera.jpg';

    return [
        'name' => $word,
        'name_en' => $word,
        'admin_id' => $faker->numberBetween(1, 6),
        'genre_id' => $faker->numberBetween(1, 9),
        'detail' => $paragraphs,
        'search_tag' => $search_tag,
        'price' => $faker->numberBetween(500, 200000),
        'stock' => $faker->numberBetween(5, 20),
        'imgpath' => $imgpath,
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
    ];
});
