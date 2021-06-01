<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Cart;
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myCart(Cart $cart)
    {
        $data = $cart->showCart();
        exit;
        return view('cart', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addMycart(Request $request, Cart $cart)
    {
        //カートに追加の処理
        $stock_id=$request->stock_id;
        $message = $cart->addCart($stock_id);

        //追加後の情報を取得
        $data = $cart->showCart();

        return view('cart', $data)->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCart(Request $request, Cart $cart)
    {
        //カートから削除の処理
        $stock_id=$request->stock_id;
        $message = $cart->deleteCart($stock_id);

        //追加後の情報を取得
        $data = $cart->showCart();

        return view('cart', $data)->with('message', $message);
    }

    /**
     * Develop Comment
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request, Cart $cart)
    {
        $user = Auth::user();
        $mail_data['user']=$user->name; //追記
        $mail_data['checkout_items']=$cart->checkoutCart(); //編集
        Mail::to($user->email)->send(new Thanks($mail_data));//編集
        return view('checkout');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
