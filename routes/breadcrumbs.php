<?php
// TOP
Breadcrumbs::for('top', function ($trail) {
    $trail->push('ホーム', route('top'));
});

// 商品詳細
Breadcrumbs::for('detail', function ($trail, $product) {
    $trail->parent('top');
    $trail->push($product->genre->category->name, route('category_narrowing_down', $product->genre->category->name_en));
    $trail->push($product->genre->name, route('genre_narrowing_down', [$product->genre->category->name_en, $product->genre->name_en]));
    $trail->push($product->name);
});