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
Route::get('/detail/{product}', 'ShopController@productDetail')->name('product_detail');
Route::get('/cart', 'CartController@index')->name('cart_index');
Route::post('/cart', 'CartController@addCart')->name('add_cart');

// セッションを使用するため、web側でルート定義
Route::put('/api/quantity_update', 'Api\CartApi@quantityUpdate')->name('api_quantity_update');
Route::put('/api/delete_item', 'Api\CartApi@deleteItem')->name('api_delete_item');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/order', 'OrderController@order')->name('order');
    Route::get('/order/history', 'OrderController@history')->name('order_history');
    Route::post('/order', 'OrderController@purchase')->name('purchase');
    Route::get('/order/thanks', 'OrderController@orderThanks')->name('order_thanks');
    Route::get('/mypage/edit', 'MypageController@edit')->name('mypage_edit');
    Route::get('/mypage/change_address', 'MypageController@changeAddress')->name('change_address');
    Route::put('/mypage/full_update', 'MypageController@fullUpdate')->name('mypage_full_update');
    Route::put('/mypage/address_update', 'MypageController@addressUpdate')->name('mypage_address_update');
});

Auth::routes();

Route::get('/{category}', 'ShopController@categoryNarrowingDown')->name('category_narrowing_down');
Route::get('/{category}/{genre}', 'ShopController@genreNarrowingDown')->name('genre_narrowing_down');