<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Gloudemans\Shoppingcart\Facades\Cart;
use Tests\TestCase;
use App\Models\Product;
use TestingSeeder;

class CartControllerActionTest extends TestCase
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
    }

    /**
     * テストメソッド終了時に、ダミーデータをリフレッシュ
     */
    public function tearDown(): void
    {
        Artisan::call('migrate:refresh');
        parent::tearDown();
    }

    /**
     * レスポンス検証
     * 状態変化検証
     * @test
     */
    public function addCart_HTTPテスト_正常(): void
    {
        $product = Product::first();
        $qty = $this->faker->numberBetween(1, 99);

        $data = [
            'product_id' => $product->id,
            'qty' => $qty
        ];
        $url = "/cart";
        $response = $this->post($url, $data);

        // 状態変化検証 カートに追加されているか
        $cart = Cart::Content();
        $cart->each(function ($item) use ($qty) {
            $this->assertSame($item->qty, $qty);
        });

        // レスポンス検証
        $response->assertRedirect("/cart");
    }

    /**
     * ステータスコード検証
     * 状態変化検証
     * @test
     */
    public function addCart_HTTPテスト_バリデーションエラー(): void
    {
        $product = Product::first();

        $data = [
            'product_id' => $product->id,
            'qty' => 999
        ];
        $url = "/cart";
        $response = $this->post($url, $data);

        // 状態変化検証 カートが空かどうか
        $cart = Cart::Content();
        $is_cart = true;
        if ($cart->isEmpty()) {
            $is_cart = null;
        }
        $this->assertNull($is_cart);

        // ステータスコード検証
        $response->assertStatus(302);
    }

    /**
     * ステータスコード検証
     * 状態変化検証
     * @test
     */
    public function addCart_HTTPテスト_存在しない商品ID(): void
    {
        $data = [
            'product_id' => 9999,
            'qty' => 1
        ];
        $url = "/cart";
        $response = $this->post($url, $data);

        // 状態変化検証 カートが空かどうか
        $cart = Cart::Content();
        $is_cart = true;
        if ($cart->isEmpty()) {
            $is_cart = null;
        }
        $this->assertNull($is_cart);

        // ステータスコード検証
        $response->assertStatus(404);
    }
}
