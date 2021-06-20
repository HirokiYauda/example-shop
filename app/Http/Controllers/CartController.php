<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Auth;
use Util;

class CartController extends Controller
{
    /**
     * カート一覧
     *
     * @return View
     */
    public function index()
    {
        // カートの商品情報を拡張して取得
        $carts = Util::getExpansionCartContent();
        // カートに入っている商品が購入可能かチェック
        $isAvailable = Util::checkProductInCartCanBePurchased();

        // 最大で購入可能な数量
        $max_qty = config("cart.default_max_qty");

        // エラーメッセージ表示用配列
        $caution_messages = session('caution_messages') ?? [];

        // テンプレートメッセージ
        $templete_messages = [
            'update_error' => config('cart.update_error_message'),
            'not_exist_product_in_cart' => config('cart.not_exist_product_in_cart_message')
        ];

        $carts_info = [
            'count' => Cart::count() ?? 0, // カート内の合計商品数
            'total' => Cart::subtotal() ?? 0, // 合計金額(税込)
            'is_available' => $isAvailable
        ];
        $is_login = Auth::check();
        
        return view('cart', compact('carts', 'carts_info', 'is_login', 'max_qty', 'caution_messages', 'templete_messages'));
    }


    /**
     * カート追加処理
     *
     * @return Redirect
     */
    public function addCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        // 商品を購入可能な状態かチェック
        $qty = $request->qty ?? 1;
        $check = Util::checkAddProductToCart($product->id, $qty);
        // 問題があればリダイレクト
        if (empty($check['result'])) {
            return redirect()->route('cart_index')->with("caution_messages", $check['messages']);
        }

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->qty ?? 1,
            'price' => $product->price,
            'weight' => $request->weight ?? 1,
            'options' => ['name_en'=> $product->name_en, 'imgpath' => $product->imgpath]
        ]);

        return redirect()->route('cart_index');
    }
}
