<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Gloudemans\Shoppingcart\Facades\Cart;
use Auth;
use Illuminate\Support\Str;
use DB;
use Util;

class OrderController extends Controller
{
    /**
     * 注文確認
     *
     * @return View
     */
    public function order(Product $product)
    {
        // カート情報をDBから復元
        Util::readCart();

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
        // 住所が未登録の場合、告知
        if (empty($user->full_address)) {
            $caution_messages[] = "住所を登録後に、ご購入ください";
        } else {
            // カートに入っている商品が購入可能かチェック
            $isAvailable = Util::checkProductInCartCanBePurchased();
            if (empty($isAvailable['result'])) {
                $caution_messages = $isAvailable['not_available_messages'];
            }
        }
        
        return view('order', compact('carts', 'carts_info', 'user', 'caution_messages', 'product'));
    }

    /**
     * 注文履歴
     *
     * @return View
     */
    public function history()
    {
        
        return view('history');
    }

    /**
     * 商品購入
     *
     * @return Redirect
     */
    public function purchase(Order $order, Product $product)
    {
        $carts = Cart::content();
        $carts_info = [
            'count' => Cart::count() ?? 0, // カート内の合計商品数
            'total' => Cart::subtotal() ?? 0, // 合計金額(税込)
            'registration_total' => Cart::subtotal(0, "", "") ?? 0
        ];
        // カートに商品が存在しない場合は、トップへリダイレクト
        if ($carts_info["count"] <= 0) {
            return redirect()->route('top');
        }

        $user = Auth::user();
        // 住所が登録済みかチェック
        if (empty($user->full_address)) {
            return redirect()->route('order');
        }

        // カートに入っている商品が購入可能かチェック
        $isAvailable = Util::checkProductInCartCanBePurchased();
        if (empty($isAvailable['result'])) {
            return redirect()->route('order');
        }

        // 商品購入時のDB更新処理
        $this->updateOrder($carts, $carts_info, $user, $order, $product);

        // カートを削除
        Cart::destroy();
        // DBからカート情報を削除
        Cart::erase($user->id);
        return redirect()->route('order_thanks');
    }

    /**
     * 購入完了
     *
     * @return View
     */
    public function orderThanks()
    {
        return view('order.thanks');
    }

    /**
     * 商品購入時のDB更新処理
     *
     * @return Boolean
     */
    public function updateOrder($carts, $carts_info, $user, $order, $product)
    {
        DB::transaction(function () use ($carts, $carts_info, $user, $order, $product) {
            // 注文テーブルへ登録
            $order_data = [
                'user_id' => $user->id,
                'zip' => $user->zip,
                'pref_id' => $user->pref_id,
                'address1' => $user->address1,
                'address2' => $user->address2,
                'total' => $carts_info['registration_total'],
            ];
            $order->fill($order_data)->save();
            $orderLastInsertID = $order->id;

            // 注文テーブル登録後、注文番号を生成して、アップデート
            $order_number = Str::random(8) . sprintf('%08d', $user->id . $orderLastInsertID);
            $order->findOrFail($orderLastInsertID)->fill(['order_number' => $order_number])->save();

            // 注文詳細テーブルへ登録 | 商品テーブルの在庫数を減算
            foreach ($carts as $cart) {
                $order_detail_data = [
                    'order_id' => $orderLastInsertID,
                    'product_id' => $cart->id,
                    'price_including_tax' => $cart->price,
                    'qty' => $cart->qty,
                ];
                // 注文詳細テーブルへ登録
                $orderDetail = new OrderDetail;
                $orderDetail->fill($order_detail_data)->save();
                
                // 商品テーブルの在庫数を減算
                $selectProduct = $product->findOrFail($cart->id);
                $subtraction_number = ($selectProduct->stock >= $cart->qty) ? ($selectProduct->stock - $cart->qty) : 0;
                $selectProduct->fill(['stock' => $subtraction_number])->save();
            }
        });
    }
}
