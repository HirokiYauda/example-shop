<?php

namespace App\Library;

use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;

class Util
{
    /**
     * カートに追加可能な数量を取得
     *
     *
     * @param Int $productId
     * @return Int
     */
    public static function getAddQtyInCart($productId)
    {
        // ECサイト上で設定しているカートに入れられる最大数量(デフォルト数量)
        $res = config("cart.default_max_qty");

        // カートに入っている選択された商品情報を取得
        $selectedProductInfoInCart = Cart::content()->firstWhere('id', $productId);
        // カートに選択された商品がはいっているとき
        if (!empty($selectedProductInfoInCart)) {
            // カート内の商品数量を減算して、最大数量を設定
            $res = $res - $selectedProductInfoInCart->qty;
        }

        return $res;
    }

    /**
     * カートに商品追加できるかチェック
     */
    public static function checkAddProductToCart($productId, $qty = 1)
    {
        $res = [
            "result" => true,
            "messages" => []
        ];

        // カートに追加できる数量か判定
        $addQtyInCart = self::getAddQtyInCart($productId); // カートに追加可能な数量を取得
        if ($addQtyInCart <= 0 || $addQtyInCart < $qty) {
            $res["result"] = false;
            $res["messages"][] = config("cart.max_qty_caution_message");
        }

        // カートに入れられる上限数を超えていないか判定
        $selectedProductInfoInCart = Cart::content()->firstWhere('id', $productId); // カートに入っている選択商品を取得
        $cartCount = Cart::countItems();
        // 存在しない場合、カートの商品数を加算
        if (empty($selectedProductInfoInCart)) {
            $cartCount = $cartCount + 1;
        }

        // カートに入れられる数と、カートの商品数を比較
        if ($cartCount > config('cart.default_cart_count')) {
            $res["result"] = false;
            $res["messages"][] = config("cart.cart_count_caution_message");
        }
        
        return $res;
    }

    /**
     * カートに入っている商品情報を拡張して返却
     */
    public static function getExpansionCartContent()
    {
        $carts = Cart::content();
        foreach ($carts as $key => $cart) {
            $cart->options['isAvailable'] = true;
            $cart->options['stock_info_message'] = "";
            $cart->options['max_qty_caution_message'] = "";

            $selectedProductInfo = Product::findOrFail($cart->id); // 選択された商品情報の取得
            // 在庫0か、もしくは指定数以下の場合、残在庫数を告知
            if ($selectedProductInfo->stock === 0) {
                $cart->options['stock_info_message'] = config("cart.no_stock_caution_message");
            } elseif ($selectedProductInfo->stock < config('cart.display_remaining_inventory_count')) {
                $cart->options['stock_info_message'] = "残りの在庫数は、" . $selectedProductInfo->stock . "点です。";
            }

            // カートに入れている数量が、在庫数を超えていた場合、告知
            if ($cart->qty > $selectedProductInfo->stock) {
                $cart->options['max_qty_caution_message'] = config("cart.max_qty_caution_message");
            }
            
            // 購入可能かどうか
            if ($selectedProductInfo->stock === 0 || $cart->qty > $selectedProductInfo->stock) {
                $cart->options['isAvailable'] = false;
            }
        }
        
        return $carts;
    }

    /**
     * カートに入っている商品が購入可能かチェック
     */
    public static function checkProductInCartCanBePurchased()
    {
        $res = [
            "result" => true,
            "not_available_messages" => []
        ];

        $carts = Cart::content();
        if (!empty($carts)) {
            foreach ($carts as $cart) {
                $selectedProductInfo = Product::findOrFail($cart->id); // 選択された商品情報の取得
                // 在庫なし
                if ($selectedProductInfo->stock === 0) {
                    $res["result"] = false;
                    $res["not_available_messages"][] = $selectedProductInfo->name . "は、" . config("cart.no_stock_caution_message");
                // 購入数が在庫数を超えていた場合
                } elseif($selectedProductInfo->stock < $cart->qty) {
                    $res["result"] = false;
                    $res["not_available_messages"][] = $selectedProductInfo->name . "は、" . config("cart.max_qty_caution_message");
                }
            }

            // カートに入れられる上限数を超えていないか判定
            $cartCount = Cart::countItems();
            // カートに入れられる数と、カートの商品数を比較
            if ($cartCount > config('cart.default_cart_count')) {
                $res["result"] = false;
                $res["not_available_messages"][] = config("cart.cart_count_caution_message");
            }
        } else {
            $res["result"] = false;
            $res["not_available_messages"][] = config("cart.not_exist_product_in_cart_message");
        }

        return $res;
    }
}
