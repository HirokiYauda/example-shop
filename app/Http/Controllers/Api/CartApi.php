<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartApi extends Controller
{
    /**
     * Quantity update api
     *
     * @return Object
     */
    public function quantityUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|max:' . config("cart.count.max_item"),
            'row_id' => 'required|string'
        ]);
       
        $cart = Cart::update($validatedData['row_id'], $validatedData['quantity']);

        $carts_info = [
            'updateItem' => $cart,
            'count' => Cart::count() ?? 0, // カート内の合計商品数
            'total' => Cart::total() ?? 0 // 合計金額(税込)
        ];
        
        return $carts_info;
    }

    /**
     * Delete Item api
     *
     * @return Object
     */
    public function deleteItem(Request $request)
    {
        $validatedData = $request->validate([
            'row_id' => 'required|string'
        ]);
       
        Cart::remove($validatedData['row_id']);

        $carts_info = [
            'count' => Cart::count() ?? 0, // カート内の合計商品数
            'total' => Cart::total() ?? 0 // 合計金額(税込)
        ];
        
        return $carts_info;
    }
}
