<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Gloudemans\Shoppingcart\Facades\Cart;
use Auth;
use Illuminate\Support\Str;
use DB;

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
            'total' => Cart::subtotal() ?? 0, // 合計金額(税込)
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
    public function order(Product $product)
    {
        $carts = Cart::content();
        $carts_info = [
            'count' => Cart::count() ?? 0, // カート内の合計商品数
            'total' => Cart::subtotal() ?? 0, // 合計金額(税込)
        ];

        // カートに商品が存在しない場合は、トップへリダイレクト
        if ($carts_info["count"] <= 0) {
            return redirect()->route('top');
        }

        $user = Auth::user();
        $caution_messages = [];
        if (empty($user->full_address)) {
            $caution_messages[] = "住所を登録後に、ご購入ください";
        }
        
        return view('order', compact('carts', 'carts_info', 'user', 'caution_messages', 'product'));
    }

    /**
     * Product purchase process
     *
     * @return Redirect
     */
    public function purchase(Order $order, OrderDetail $orderDetail, Product $product)
    {
        $carts = Cart::content();
        $carts_info = [
            'count' => Cart::count() ?? 0, // カート内の合計商品数
            'total' => Cart::subtotal() ?? 0, // 合計金額(税込)
        ];
        // カートに商品が存在しない場合は、トップへリダイレクト
        if ($carts_info["count"] <= 0) {
            return redirect()->route('top');
        }

        $user = Auth::user();

        $res = $this->updateOrder($carts, $carts_info, $user, $order, $orderDetail, $product);

        // 正常に登録が行うことができたら、カートを削除
        if (!empty($res)) {
            Cart::destroy();
        }
        return redirect()->route('order_thanks');
    }

    /**
     * update Order
     *
     * @return
     */
    public function updateOrder($carts, $carts_info, $user, $order, $orderDetail, $product)
    {
        return DB::transaction(function () use ($carts, $carts_info, $user, $order, $orderDetail, $product) {
            $register_flgs = [];

            // 注文テーブルへ登録
            $order_data = [
                'user_id' => $user->id,
                'zip' => $user->zip,
                'pref_id' => $user->pref_id,
                'address1' => $user->address1,
                'address2' => $user->address2,
                'total' => $carts_info['total'],
            ];
            $register_flgs[] = $order->fill($order_data)->save();
            $orderLastInsertID = $order->id;

            // 注文テーブル登録後、注文番号を生成して、アップデート
            $order_number = Str::random(8) . sprintf('%08d', $user->id . $orderLastInsertID);
            $register_flgs[] = $order->findOrFail($orderLastInsertID)->fill(['order_number' => $order_number])->save();

            // 注文詳細テーブルへ登録 | 商品の在庫数を減算
            foreach ($carts as $cart) {
                $order_detail_data = [
                    'order_id' => $orderLastInsertID,
                    'product_id' => $cart->id,
                    'price' => $cart->price,
                    'qty' => $cart->qty,
                ];
                $register_flgs[] = $orderDetail->fill($order_detail_data)->save();
                // 商品の在庫数を減算
                $selectProduct = $product->findOrFail($cart->id);
                $subtraction_number = ($selectProduct->stock >= $cart->qty) ? ($selectProduct->stock - $cart->qty) : 0;
                $selectProduct->stock - $cart->qty;
                $register_flgs[] = $selectProduct->fill(['stock' => $subtraction_number])->save();
            }

            // 全てのテーブルが正常に登録できたかチェック
            $register_flgs_collection = collect($register_flgs);
            $res = $register_flgs_collection->every(function ($value) {
                return $value === true;
            });

            return $res;
        });
    }

    /**
     * Purchase completed
     *
     * @return View
     */
    public function orderThanks()
    {
        return view('order.thanks');
    }

    /**
     * Add to cart
     *
     * @return Redirect
     */
    public function addCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        // 商品を購入可能な状態かチェック
        $qty = $request->qty ?? 1;
        $check = $this->cartCheck($product->id, $qty);
        // 問題があればリダイレクト
        if (empty($check['result'])) {
            return redirect()->route('cart_index')->with("caution_message", $check['message']);
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

    /**
     * 商品を購入可能な状態かチェック
     *
     * @return Array
     */
    public function cartCheck($productId, $qty)
    {
        $res = [
            "result" => true,
            "message" => ""
        ];

        $carts = Cart::content();
        // 既にカートに商品が入っているとき
        if (!empty($carts)) {
            $updateItem = $carts->firstWhere('id', $productId);
            // カートに存在する商品に更新が入るとき
            if (!empty($updateItem)) {
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
    public function itemMaxQuantity($productId)
    {
        $maxQuantity = config("cart.count.max_item");
        $designatedItemsInCart = Cart::content()->firstWhere('id', $productId);
        if (!empty($designatedItemsInCart)) {
            $maxQuantity = config("cart.count.max_item") - $designatedItemsInCart->qty;
        }

        return $maxQuantity;
    }
}
