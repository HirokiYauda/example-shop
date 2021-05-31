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
Route::get('/{category}', 'ShopController@categoryNarrowingDown')->name('category_narrowing_down');
Route::get('/{category}/{genre}', 'ShopController@genreNarrowingDown')->name('genre_narrowing_down');
Route::get('/detail/{product}/{category_id}/{genre_id}', 'ShopController@productDetail')->name('product_detail');

Route::get('/cart', 'ShopController@myCart')->name('cart');
Route::post('/cart', 'ShopController@addMycart')->name('add_cart');

Route::group(['middleware' => ['auth']], function () {
    Route::post('/cartdelete', 'ShopController@deleteCart')->name('delete_cart');
    Route::post('/checkout', 'ShopController@checkout')->name('checkout');
    Route::get('/myage/edit', 'MypageController@myCart')->name('mypage_edit');
});

Auth::routes();
