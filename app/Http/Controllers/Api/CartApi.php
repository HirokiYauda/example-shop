<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;
use App\Library\Util;
use RuntimeException;

class CartApi extends Controller
{
    /**
     * カート内商品の数量更新API
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return array
     */
    public function quantityUpdate(Request $request): array
    {
        $res = [
            "result" => false,
            "message" => "",
            "cart" => null,
            "cartInfo" => ["count" => 0, "total" => 0],
            "register_type" => "update"
        ];

        // APIリクエスト先の存在チェックや不正入力をチェック
        try {
            $validatedData = $request->validate([
                'quantity' => 'required|integer|max:' . config("cart.default_max_qty"),
                'row_id' => 'required|string'
            ]);
            // カート内の選択商品を更新
            $cart = Cart::update($validatedData['row_id'], $validatedData['quantity']);
            // ユーザー情報を持っている場合は、カート情報をDBに保管
            Util::registerCart();
    
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
                'total' => Cart::total() ?? 0 // 合計金額(税込)
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
     * @param \Illuminate\Http\Request $request
     * 
     * @return array
     */
    public function deleteItem(Request $request): array
    {
        $res = [
            "result" => false,
            "message" => "",
            "cartInfo" => ["count" => 0, "total" => 0],
            "register_type" => "delete",
            "row_id" => ""
        ];

        // APIリクエスト先の存在チェックや不正入力をチェック
        try {
            $validatedData = $request->validate([
                'row_id' => 'required|string'
            ]);
            $res['row_id'] = $validatedData['row_id'];
        
            // カート内の選択商品を削除
            Cart::remove($validatedData['row_id']);
            // ユーザー情報を持っている場合は、カート情報をDBに保管
            Util::registerCart();

            // カート内の合計商品数・合計金額を更新
            $res["cartInfo"] = [
                'count' => Cart::count() ?? 0, // カート内の合計商品数
                'total' => Cart::total() ?? 0 // 合計金額(税込)
            ];
            
            $res['result'] = true;
        } catch (RuntimeException $e) {
            // $res['message'] = $e;
        }
        
        return $res;
    }
}
