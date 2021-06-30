<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ThanksMail;
use App\Mail\OrderMail;
use App\Library\Util;
use Carbon\Carbon;


class OrderController extends Controller
{
    /**
     * 注文確認
     *
     * @param \App\Models\Product $product
     * 
     * @return View
     */
    public function order(Product $product): object
    {
        // カート情報をDBから復元
        Util::readCart();

        $carts = Cart::content();
        $carts_info = [
            'count' => Cart::count() ?? 0, // カート内の合計商品数
            'total' => Cart::total() ?? 0, // 合計金額(税込)
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
     * @param \Illuminate\Http\Request $request
     * 
     * @return View
     */
    public function history(Request $request): object
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->get();
        $orderYears = collect();

        if (!empty($orders)) {
            // 注文履歴の作成日から、重複しない年を降順で取得
            $orderYears = $this->getYearByOrderCreatedAt($orders);
                
            // 注文履歴を、年で絞り込んで取得
            $sort_history = $orderYears->max(); // リクエストがないときは、直近の年を取得
            if (!empty($request->sort_history)) {
                $sort_history = $request->sort_history;
            }
            $orders = Order::with('orderDetail.product')->where('user_id', $user->id)->whereYear('created_at', $sort_history)->get();
        }
        
        return view('order.history', compact('orders', 'orderYears'));
    }

    /**
     * 商品購入
     *
     * @param \App\Models\Order $order
     * @param \App\Models\Product $product
     * 
     * @return Redirect
     */
    public function purchase(Order $order, Product $product): object
    {
        $carts = Cart::content();
        $carts_info = [
            'count' => Cart::count() ?? 0, // カート内の合計商品数
            'total' => Cart::total() ?? 0, // 合計金額(税込)
            'registration_total' => Cart::total(0, "", "") ?? 0
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
        $order_number = $this->updateOrder($carts, $carts_info, $user, $order, $product);

        // メールデータ作成
        $mail_data = [
            'user_name' => $user->name,
            'order_number' => $order_number,
            'full_address' => $user->full_address,
            'total' => $carts_info['total'],
            'count' => $carts_info['count']
        ];
        // ユーザーへメール送信
        Mail::to($user->email)->send(new ThanksMail($mail_data));
        // 商品管理者へメール送信
        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new OrderMail($mail_data));
        
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
     * @param \Gloudemans\Shoppingcart\Facades\Cart::content $carts
     * @param array $carts_info
     * @param \Illuminate\Support\Facades\Auth::user $user
     * @param \App\Models\Order $order
     * @param \App\Models\Product $product
     * 
     * @return string
     */
    private function updateOrder(object $carts, array $carts_info, object $user, object $order, object $product): string
    {
        return DB::transaction(function () use ($carts, $carts_info, $user, $order, $product) {
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
                    'price_including_tax' => $cart->options->price_including_tax,
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

            return $order_number;
        });
    }

    /**
     * 注文履歴の作成日から、重複しない年を降順で取得
     *
     * @param \App\Models\Order $order
     * 
     * @return object
     */
    private function getYearByOrderCreatedAt(object $orders): object
    {
        return $orders->pluck('created_at')
            ->mapInto(Carbon::class)
            ->map(function ($item, $key) {
                return $item->format('Y');
            })
            ->unique()
            ->sort(function ($a, $b) {
                if ($a == $b) {
                    return 0;
                }
                return ($a > $b) ? -1 : 1;
            });
    }
}
