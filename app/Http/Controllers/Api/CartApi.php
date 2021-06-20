<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;

class CartApi extends Controller
{
    /**
     * カート内商品の数量更新API
     *
     * @return Object
     */
    public function quantityUpdate(Request $request)
    {
        $res = [
            "result" => false,
            "message" => "",
            "cart" => null,
            "cartInfo" => ["count" => 0, "total" => 0]
        ];

        // APIリクエスト先の存在チェックや不正入力をチェック
        try {
            $validatedData = $request->validate([
                'quantity' => 'required|integer|max:' . config("cart.default_max_qty"),
                'row_id' => 'required|string'
            ]);
            // カート内の選択商品を更新
            $cart = Cart::update($validatedData['row_id'], $validatedData['quantity']);
    
            // カートに入れている数量が、在庫数を超えていた場合の告知メッセージ更新
            $selectedProductInfo = Product::findOrFail($cart->id); // 選択された商品情報の取得
            $cart->options['max_qty_caution_message'] = null;
            if ($cart->qty > $selectedProductInfo->stock) {
                $cart->options['max_qty_caution_message'] = config("cart.max_qty_caution_message");
            }
            $res["cart"] = $cart;
    
            // カート内の合計商品数・合計金額を更新
            $res["cartInfo"] = [
                'count' => Cart::count() ?? 0, // カート内の合計商品数
                'total' => Cart::subtotal() ?? 0 // 合計金額(税込)
            ];
            $res["result"] = true;
        } catch (RuntimeException $e) {
            // $res['message'] = $e;
        }
        
        return $res;
    }

    /**
     * カート内商品削除API
     *
     * @return Object
     */
    public function deleteItem(Request $request)
    {
        $res = [
            "result" => false,
            "message" => "",
            "carts_info" => null,
        ];

        // APIリクエスト先の存在チェックや不正入力をチェック
        try {
            $validatedData = $request->validate([
                'row_id' => 'required|string'
            ]);
        
            // カート内の選択商品を削除
            Cart::remove($validatedData['row_id']);

            // カート内の合計商品数・合計金額を更新
            $carts_info = [
                'count' => Cart::count() ?? 0, // カート内の合計商品数
                'total' => Cart::subtotal() ?? 0 // 合計金額(税込)
            ];

            $res['carts_info'] = $carts_info;
            $res['result'] = true;
        } catch (RuntimeException $e) {
            // $res['message'] = $e;
        }
        
        return $res;
    }
}
