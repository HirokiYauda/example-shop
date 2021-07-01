<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Genre;
use App\Library\Util;

class ShopController extends Controller
{
    /**
     * トップ
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return View
     */
    public function index(Request $request): object
    {
        // カート情報をDBから復元
        Util::readCart();

        $products = Util::sort(Product::with('genre.category'), $request->sort);
        return view('top', compact('products'));
    }

    /**
     * 商品一覧(カテゴリ絞り込み)
     *
     * @param \Illuminate\Http\Request $request
     * @param string $category_name_en query parameter
     * 
     * @return View
     */
    public function categoryNarrowingDown(Request $request, string $category_name_en): object
    {
        // カート情報をDBから復元
        Util::readCart();

        // 必須パラメータのカテゴリが存在するとき、指定カテゴリ取得。存在しないときは例外エラー
        $specified_category = Category::where('name_en', $category_name_en)->firstOrFail();
        $page_name = "「{$specified_category->name}」の商品一覧";
        $target_page = "category";
        // 必須パラメータのカテゴリから、指定ジャンル一覧を取得
        $genres = Genre::where('category_id', $specified_category->id)->get();
        // 必須パラメータのカテゴリーに紐づくジャンルID一覧で絞り込みして、商品一覧を取得
        $products = Util::sort(Product::whereIn('genre_id', $genres->pluck('id'))->with('genre.category'), $request->sort);

        return view('shop', compact('products', 'page_name', 'target_page', 'genres', 'category_name_en'));
    }

    /**
     * 商品一覧(ジャンル絞り込み)
     *
     * @param \Illuminate\Http\Request $request
     * @param string $category_name_en query parameter
     * @param string $genre_name_en query parameter
     * 
     * @return View
     */
    public function genreNarrowingDown(Request $request, string $category_name_en, string $genre_name_en): object
    {
        // カート情報をDBから復元
        Util::readCart();

        // 必須パラメータのカテゴリが存在するとき、指定カテゴリ取得。存在しないときは例外エラー
        $specified_category = Category::where('name_en', $category_name_en)->firstOrFail();
        // 必須パラメータのカテゴリから、指定ジャンル一覧を取得
        $genres = Genre::where('category_id', $specified_category->id)->get();
        // 必須パラメータのカテゴリに紐づくジャンルが存在するとき、指定ジャンル取得。存在しないときは例外エラー
        $specified_genre = Genre::where('name_en', $genre_name_en)->where('category_id', $specified_category->id)->firstOrFail();
        $page_name = "「{$specified_genre->name}」の商品一覧";
        $target_page = "genre";
        // 必須パラメータのジャンルから、IDで絞り込みして、商品一覧を取得
        $products = Util::sort(Product::where('genre_id', $specified_genre->id)->with('genre.category'), $request->sort);

        return view('shop', compact('products', 'page_name', 'target_page', 'genres', 'category_name_en'));
    }

    /**
     * 商品一覧(検索結果)
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return View
     */
    public function search(Request $request): object
    {
        // カート情報をDBから復元
        Util::readCart();

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
        $target_page = "search";

        // クエリパラメータの状態に応じて、遷移先を変更
        if (!empty($request->category)) {
            if (empty($genres)) {
                return redirect()->route('top');
            }
        }

        if (empty($request->free_word)) {
            if (!empty($genres)) {
                return redirect()->route('category_narrowing_down', ['category' => $category_name_en]);
            }
            return redirect()->route('top');
        }

        // リクエストから商品の条件を指定
        $products_query = Product::when($genres, function ($query, $genres) {
            return $query->whereIn('genre_id', $genres->pluck('id'));
        })
        ->when($request->free_word, function ($query, $free_word) {
            return $query->whereRaw("match(`search_tag`) against (? IN BOOLEAN MODE)", [$free_word]);
        })
        ->with('genre.category');
        $products = Util::sort($products_query, $request->sort);

        return view('shop', compact('products', 'page_name', 'target_page', 'genres', 'category_name_en'));
    }

    /**
     * 商品詳細
     *
     * @param string $product_name_en query parameter
     * 
     * @return View
     */
    public function productDetail(string $product_name_en): object
    {
        // カート情報をDBから復元
        Util::readCart();

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
