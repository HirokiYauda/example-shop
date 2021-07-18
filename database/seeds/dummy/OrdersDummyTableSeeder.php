<?php

namespace Database\Seeds\Dummy;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\User;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use Illuminate\Support\Str;

class OrdersDummyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '1024M');
        DB::table('orders')->truncate(); //2回目実行の際にシーダー情報をクリア
        DB::table('order_details')->truncate(); //2回目実行の際にシーダー情報をクリア
        $faker = Factory::create('ja_JP');
        $order_data = [];
        $order_detail_data = [];
        $user_limit_id = 100000; // 注文データに登録するユーザーIDの上限
        $cart_limit_count = 5; // カートに入る商品数の上限
        $product_limit_id = 300000; // 注文する商品IDの上限
        $qty_limit = 5; // 商品1点の最大数量
        $register_count = 100000; // 何件登録するか | 1000単位でbulk insert
 
        for ($i=0; $i < $register_count; $i++) { 
            $num = $i + 1;
            $user = User::find($faker->numberBetween(1, $user_limit_id));

            // 注文詳細データ生成
            $cart_count = $faker->numberBetween(1, $cart_limit_count);
            $productIdInCart = [];
            for ($j=0; $j < $cart_count; $j++) {
                $productIdInCart[] = $faker->numberBetween(1, $product_limit_id);
            }
            $products = Product::find($productIdInCart);
            $totalPrice = 0;
            foreach ($products as $product) {
                $order_detail_data[] = [
                    'order_id' => $num,
                    'product_id' => $product->id,
                    'price_including_tax' => $product->price_including_tax,
                    'qty' => $faker->numberBetween(1, $qty_limit),
                ];
                $totalPrice += $product->price_including_tax;
            }

            // 注文データ生成
            $order_data[] = [ 
                'user_id' => $user->id,
                'order_number' => Str::random(8) . sprintf('%08d', $user->id . $num),
                'zip' => $user->zip,
                'pref_id' => $user->pref_id,
                'address1' => $user->address1,
                'address2' => $user->address2,
                'total' => $totalPrice,
                'created_at' => $faker->dateTimeThisDecade,
            ];
    
            if (count($order_data) >= 1000) {
                Order::insert($order_data);
                OrderDetail::insert($order_detail_data);
                $order_data = [];
                $order_detail_data = [];
            }
        }
    }
}
