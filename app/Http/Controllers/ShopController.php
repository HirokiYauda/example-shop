<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Genre;
use Util;

class ShopController extends Controller
{
    /**
     * トップ
     *
     * @return View
     */
    public function index()
    {
        $products = Product::with('genre.category')->Paginate(6);
        return view('top', compact('products'));
    }

    /**
     * 商品一覧(カテゴリ絞り込み)
     *
     * @return View
     */
    public function categoryNarrowingDown($category_name_en)
    {
        // 必須パラメータのカテゴリが存在するとき、指定カテゴリ取得。存在しないときは例外エラー
        $specified_category = Category::where('name_en', $category_name_en)->firstOrFail();
        $page_name = "「{$specified_category->name}」の商品一覧";
        // 必須パラメータのカテゴリから、指定ジャンル一覧を取得
        $genres = Genre::where('category_id', $specified_category->id)->get();
        // 必須パラメータのカテゴリーに紐づくジャンルID一覧で絞り込みして、商品一覧を取得
        $products = Product::whereIn('genre_id', $genres->pluck('id'))->with('genre.category')->Paginate(6);

        return view('shop', compact('products', 'page_name', 'genres', 'category_name_en'));
    }

    /**
     * 商品一覧(ジャンル絞り込み)
     *
     * @return View
     */
    public function genreNarrowingDown($category_name_en, $genre_name_en)
    {
        // 必須パラメータのカテゴリが存在するとき、指定カテゴリ取得。存在しないときは例外エラー
        $specified_category = Category::where('name_en', $category_name_en)->firstOrFail();
        // 必須パラメータのカテゴリから、指定ジャンル一覧を取得
        $genres = Genre::where('category_id', $specified_category->id)->get();
        // 必須パラメータのジャンルが存在するとき、指定ジャンル取得。存在しないときは例外エラー
        $specified_genre = Genre::where('name_en', $genre_name_en)->firstOrFail();
        $page_name = "「{$specified_genre->name}」の商品一覧";
        // 必須パラメータのジャンルから、IDで絞り込みして、商品一覧を取得
        $products = Product::where('genre_id', $specified_genre->id)->with('genre.category')->Paginate(6);
        return view('shop', compact('products', 'page_name', 'genres', 'category_name_en'));
    }

    /**
     * 商品一覧(検索結果)
     *
     * @return View
     */
    public function search(Request $request)
    {
        // リクエストにカテゴリが存在するとき、指定カテゴリ取得
        $specified_category = Category::where('name_en', $request->category)->first();
        // カテゴリが存在するとき、指定ジャンル一覧を取得
        $genres = null;
        $category_name_en = null;
        if (!empty($specified_category)) {
            $category_name_en = $request->category;
            $genres = Genre::where('category_id', $specified_category->id)->get();
        }
        $page_name = "「{$request->free_word}」の検索結果";

        // リクエストから商品の条件を指定
        $products = Product::when($genres, function ($query, $genres) {
            return $query->whereIn('genre_id', $genres->pluck('id'));
        })
        ->when($request->free_word, function ($query, $free_word) {
            return $query->whereRaw("match(`search_tag`) against (? IN BOOLEAN MODE)", [$free_word]);
        })
        ->with('genre.category')
        ->Paginate(6);

        return view('shop', compact('products', 'page_name', 'genres', 'category_name_en'));
    }

    /**
     * 商品詳細
     *
     * @return View
     */
    public function productDetail($product_name_en)
    {
        // EN商品名から単一商品情報を取得。存在しないときは例外エラー
        $product = Product::where('name_en', $product_name_en)->firstOrFail();
        // 単一商品情報のカテゴリから指定ジャンル一覧を取得
        $genres = Genre::where('category_id', $product->genre->category->id)->get();
        $category_name_en = $product->genre->category->name_en;

        // カートに追加可能な数量を取得
        $addQtyInCart = Util::getAddQtyInCart($product->id);

        return view('detail', compact('product', 'genres', 'category_name_en', 'addQtyInCart'));
    }
}
