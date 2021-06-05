<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use Gloudemans\Shoppingcart\Facades\Cart;
use Auth;

class CartController extends Controller
{
    /**
     * Cart list Page
     *
     * @return View
     */
    public function index()
    {
        $carts = Cart::content();
        $carts_info = [
            'count' => Cart::count() ?? 0, // カート内の合計商品数
            'total' => Cart::total() ?? 0, // 合計金額(税込)
            'update_error_message' => config('cart.update_error_message')
        ];
        $is_login = Auth::check();
        
        return view('cart', compact('carts', 'carts_info', 'is_login'));
    }

    /**
     * Order Page
     *
     * @return View
     */
    public function order()
    {
        $carts = Cart::content();
        $carts_info = [
            'count' => Cart::count() ?? 0, // カート内の合計商品数
            'total' => Cart::total() ?? 0, // 合計金額(税込)
        ];
        
        return view('order', compact('carts', 'carts_info'));
    }



    /**
     * Add to cart
     *
     * @return Redirect
     */
    public function addCart(Request $request)
    {
        $stock = Stock::findOrFail($request->stock_id);
        // 商品を購入可能な状態かチェック
        $qty = $request->qty ?? 1;
        $check = $this->cartCheck($stock->id, $qty);
        // 問題があればリダイレクト
        if (empty($check['result'])) {
            return redirect()->route('cart_index')->with("caution_message", $check['message']);
        }

        Cart::add([
            'id' => $stock->id,
            'name' => $stock->name,
            'qty' => $request->qty ?? 1,
            'price' => $stock->price,
            'weight' => $request->weight ?? 1,
            'options' => ['name_en'=> $stock->name_en, 'imgpath' => $stock->imgpath]
        ]);

        return redirect()->route('cart_index');
    }

    /**
     * 商品を購入可能な状態かチェック
     *
     * @return Array
     */
    public function cartCheck($stockId, $qty)
    {
        $res = [
            "result" => true,
            "message" => ""
        ];

        $carts = Cart::content();
        // 既にカートに商品が入っているとき
        if (!empty($carts)) {
            $updateItem = $carts->firstWhere('id', $stockId);
            // カートに存在する商品に更新が入るとき
            if (!empty($updateItem)) {;
                // 1商品の最大上限数を超過する場合、リダイレクト
                if (($updateItem->qty + $qty) > config("cart.count.max_item")) {
                    $res["result"] = false;
                    $res["message"] = "1度のお買い物につき、1つの商品は、最大" . config("cart.count.max_item") . "点までとなります。";
                }
            }

            // 1カートの最大商品数を超過する場合、リダイレクト
            if (Cart::countItems() > config("cart.count.max_cart")) {
                $res["result"] = false;
                $res["message"] = "1度のお買い物につき、カートに入れられる商品は、最大" . config("cart.count.max_cart") . "点までとなります。";
            }
        }
        
        return $res;
    }

    /**
     * カート内で、指定商品の購入可能な数量を取得
     *
     * @return Array
     */
    public function itemMaxQuantity($stockId)
    {
        $maxQuantity = config("cart.count.max_item");
        $designatedItemsInCart = Cart::content()->firstWhere('id', $stockId);
        if (!empty($designatedItemsInCart)) {
            $maxQuantity = config("cart.count.max_item") - $designatedItemsInCart->qty;
        }

        return $maxQuantity;
    }
}
