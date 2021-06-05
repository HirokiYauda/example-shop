<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'ShopController@index')->name('top');
Route::get('/search', 'ShopController@search')->name('search');
Route::get('/detail/{product}/', 'ShopController@productDetail')->name('product_detail');
Route::get('/cart', 'CartController@index')->name('cart_index');
Route::post('/cart', 'CartController@addCart')->name('add_cart');

// セッションを使用するため、web側でルート定義
Route::put('/api/quantity_update', 'APi\CartApi@quantityUpdate')->name('api_quantity_update');
Route::put('/api/delete_item', 'APi\CartApi@deleteItem')->name('api_delete_item');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/order', 'CartController@order')->name('order');
    Route::get('/myage/edit', 'MypageController@myCart')->name('mypage_edit');
});

Auth::routes();

Route::get('/{category}', 'ShopController@categoryNarrowingDown')->name('category_narrowing_down');
Route::get('/{category}/{genre}', 'ShopController@genreNarrowingDown')->name('genre_narrowing_down');