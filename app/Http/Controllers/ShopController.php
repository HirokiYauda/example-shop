<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;
use App\Models\Category;
use App\Models\Genre;
use Illuminate\Support\Facades\Mail;
use App\Mail\Thanks;

class ShopController extends Controller
{
    /**
     * Top Page
     *
     * @return View
     */
    public function index()
    {
        $stocks = Stock::with('genre.category')->Paginate(6);
        return view('top', compact('stocks'));
    }

    /**
     * Category narrowing down Page
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
        // 必須パラメータのカテゴリーに紐づくジャンルID一覧で絞り込み
        $stocks = Stock::whereIn('genre_id', $genres->pluck('id'))->with('genre.category')->Paginate(6);

        return view('shop', compact('stocks', 'page_name', 'genres', 'category_name_en'));
    }

    /**
     * Genre narrowing down Page
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
        // 必須パラメータのジャンルから、IDで絞り込み
        $stocks = Stock::where('genre_id', $specified_genre->id)->with('genre.category')->Paginate(6);
        return view('shop', compact('stocks', 'page_name', 'genres', 'category_name_en'));
    }

    /**
     * Search Page
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

        // リクエストからストックの条件を指定
        $stocks = Stock::when($genres, function ($query, $genres) {
            return $query->whereIn('genre_id', $genres->pluck('id'));
        })
        ->when($request->free_word, function ($query, $free_word) {
            return $query->whereRaw("match(`search_tag`) against (? IN BOOLEAN MODE)", [$free_word]);
        })
        ->with('genre.category')
        ->Paginate(6);

        return view('shop', compact('stocks', 'page_name', 'genres', 'category_name_en'));
    }

    /**
     * Product detail Page
     *
     * @return View
     */
    public function productDetail($product_name_en)
    {
        // EN商品名から単一商品情報を取得。存在しないときは例外エラー
        $stock = Stock::where('name_en', $product_name_en)->firstOrFail();
        // 単一商品情報のカテゴリから指定ジャンル一覧を取得
        $genres = Genre::where('category_id', $stock->genre->category->id)->get();
        $category_name_en = $stock->genre->category->name_en;

        return view('detail', compact('stock', 'genres', 'category_name_en'));
    }
}
