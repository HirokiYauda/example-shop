<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use TestingSeeder;


class CartApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * テストメソッド実行時に、ダミーのデータをセット
     */
    public function setUp(): void
    {
        parent::setUp();
        // マスタテーブルをシーダーから生成
        $this->seed(TestingSeeder::class);
        // 20個のダミー商品生成
        factory(Product::class, 20)->create();
        
        // カート初期化
        Cart::destroy();
    }

    /**
     * テストメソッド終了時に、ダミーデータをリフレッシュ
     */
    public function tearDown(): void
    {
        Artisan::call('migrate:refresh');
        Cart::destroy();
        parent::tearDown();
    }

    /** 
     * ステータスコード検証
     * レスポンス検証
     * 状態変化検証
     * @test
     */
    public function quantityUpdate_APIテスト_正常(): void
    {
        $product = Product::first();

        $set_cart = Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->price,
            'weight' => 1,
            'options' => ['name_en'=> $product->name_en, 'imgpath' => $product->imgpath, 'price_including_tax' => $product->price_including_tax]
        ]);

        $qty = $this->faker->numberBetween(1, 5);
        $data = [
            'quantity' => $qty,
            'row_id' => $set_cart->rowId
        ];

        $url = "/api/quantity_update";
        $response = $this->put($url, $data);

        // ステータスコード検証
        $response->assertOk();
        // レスポンス検証
        $response->assertJsonFragment([
            "result" => true,
            "cart" => ["rowId" => $set_cart->rowId, "max_qty_caution_message" => null],
            "cartInfo" => ["count" => Cart::count() ?? 0, "total" => Cart::total() ?? 0],
            "register_type" => "update"
        ]);
        // 状態変化検証 カートの数量が更新されているか
        $cart = Cart::Content();
        $cart->each(function ($item) use ($qty) {
            $this->assertSame($item->qty, $qty);
        });
    }
}
