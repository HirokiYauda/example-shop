<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\Product;
use TestingSeeder;

class CartControllerActionTest extends TestCase
{
    use RefreshDatabase;

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
     * @test
     */
    public function addCart_HTTPテスト_正常(): void
    {
        $product = Product::first();

        $data = [
            'product_id' => $product->id,
            'qty' => 1
        ];
        $url = "/cart";
        $response = $this->post($url, $data);
        $response->assertRedirect("/cart");
    }

    /** 
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
        $response->assertStatus(302);
    }

    /** 
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
        $response->assertStatus(404);
    }
}
