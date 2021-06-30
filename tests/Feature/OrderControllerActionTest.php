<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\Product;
use App\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use TestingSeeder;
use app\Mail\ThanksMail;
use app\Mail\OrderMail;


class OrderControllerActionTest extends TestCase
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
     * @test
     */
    public function order_HTTPテスト_正常(): void
    {
        $product = Product::first();
        $user = factory(User::class)->state('addAddress')->create();

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->price,
            'weight' => 1,
            'options' => ['name_en'=> $product->name_en, 'imgpath' => $product->imgpath, 'price_including_tax' => $product->price_including_tax]
        ]);
        

        $url = "/order";
        $response = $this->actingAs($user)->get($url);

        // ステータスコード検証
        $response->assertOk();
    }

    /** 
     * レスポンス検証
     * @test
     */
    public function order_HTTPテスト_カート情報なし(): void
    {
        $user = factory(User::class)->state('addAddress')->create();

        $url = "/order";
        $response = $this->actingAs($user)->get($url);

        // レスポンス検証
        $response->assertRedirect('/');
    }

    /** 
     * ステータスコード検証
     * レスポンス検証
     * @test
     */
    public function order_HTTPテスト_住所未登録(): void
    {
        $product = Product::first();
        $user = factory(User::class)->create();

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->price,
            'weight' => 1,
            'options' => ['name_en'=> $product->name_en, 'imgpath' => $product->imgpath, 'price_including_tax' => $product->price_including_tax]
        ]);

        $url = "/order";
        $response = $this->actingAs($user)->get($url);

        // ステータスコード検証
        $response->assertOk();
        // レスポンス検証
        $response->assertSee("住所を登録後に、ご購入ください");
    }

    /** 
     * ステータスコード検証
     * レスポンス検証
     * @test
     */
    public function order_HTTPテスト_カートに入っている商品が購入可能な状態ではない(): void
    {
        $product = Product::first();
        $user = factory(User::class)->create();

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 9999,
            'price' => $product->price,
            'weight' => 1,
            'options' => ['name_en'=> $product->name_en, 'imgpath' => $product->imgpath, 'price_including_tax' => $product->price_including_tax]
        ]);

        $url = "/order";
        $response = $this->actingAs($user)->get($url);

        // ステータスコード検証
        $response->assertOk();
        // レスポンス検証
        $response->assertSee(config("cart.max_qty_caution_message"));
    }

    /** 
     * レスポンス検証
     * メール検証
     * @test
     */
    public function purchase_HTTPテスト_正常(): void
    {
        // 送信されないように設定
        Mail::fake();
        // メール送信されないことを確認
        Mail::assertNothingSent();

        $product = Product::first();
        $user = factory(User::class)->state('addAddress')->create();

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->price,
            'weight' => 1,
            'options' => ['name_en'=> $product->name_en, 'imgpath' => $product->imgpath, 'price_including_tax' => $product->price_including_tax]
        ]);

        $url = "/order";
        $response = $this->actingAs($user)->post($url);

        // メッセージが指定したユーザーに届いたことをアサート
        $email = $user->email;
        Mail::assertSent(ThanksMail::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
        Mail::assertSent(OrderMail::class, function ($mail) use ($email) {
            return $mail->hasTo(env('MAIL_FROM_ADDRESS'));
        });

        // メールがそれぞれ1回送信されたことをアサート
        Mail::assertSent(ThanksMail::class, 1);
        Mail::assertSent(OrderMail::class, 1);

        // レスポンス検証
        $response->assertRedirect("/order/thanks");
    }

    /** 
     * レスポンス検証
     * @test
     */
    public function purchase_HTTPテスト_カート情報なし(): void
    {
        // 送信されないように設定
        Mail::fake();

        $user = factory(User::class)->state('addAddress')->create();

        $url = "/order";
        $response = $this->actingAs($user)->post($url);

        // レスポンス検証
        $response->assertRedirect('/');
    }

    /** 
     * レスポンス検証
     * @test
     */
    public function purchase_HTTPテスト_住所未登録(): void
    {
        // 送信されないように設定
        Mail::fake();

        $product = Product::first();
        $user = factory(User::class)->create();

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->price,
            'weight' => 1,
            'options' => ['name_en'=> $product->name_en, 'imgpath' => $product->imgpath, 'price_including_tax' => $product->price_including_tax]
        ]);

        $url = "/order";
        $response = $this->actingAs($user)->post($url);
        // レスポンス検証
        $response->assertRedirect('order');
        $this->get('/order')->assertSee("住所を登録後に、ご購入ください");
    }

    /** 
     * レスポンス検証
     * @test
     */
    public function purchase_HTTPテスト_カートに入っている商品が購入可能な状態ではない(): void
    {
        // 送信されないように設定
        Mail::fake();

        $product = Product::first();
        $user = factory(User::class)->create();

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 9999,
            'price' => $product->price,
            'weight' => 1,
            'options' => ['name_en'=> $product->name_en, 'imgpath' => $product->imgpath, 'price_including_tax' => $product->price_including_tax]
        ]);

        $url = "/order";
        $response = $this->actingAs($user)->post($url);
        // レスポンス検証
        $response->assertRedirect('order');
        $this->get('/order')->assertSee(config("cart.max_qty_caution_message"));
    }
}
