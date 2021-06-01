<?php
// TOP
Breadcrumbs::for('top', function ($trail) {
    $trail->push('ホーム', route('top'));
});

// 商品詳細
Breadcrumbs::for('detail', function ($trail, $stock) {
    $trail->parent('top');
    $trail->push($stock->genre->category->name, route('category_narrowing_down', $stock->genre->category->name_en));
    $trail->push($stock->genre->name, route('genre_narrowing_down', [$stock->genre->category->name_en, $stock->genre->name_en]));
    $trail->push($stock->name);
});