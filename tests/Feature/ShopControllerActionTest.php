<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\Product;
use TestingSeeder;

class ShopControllerActionTest extends TestCase
{
    use RefreshDatabase;
    public $queryString = [];

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

        // クエリパラメータセット
        $this->queryString['category'] = "book";
        $this->queryString['genre'] = "comic";
        $this->queryString['other_genre'] = "rock";
        $this->queryString['free_word'] = "フィルム";
        $this->queryString['no_exist'] = "no_exist";
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
    public function categoryNarrowingDown_HTTPテスト_カテゴリあり(): void
    {
        $category = $this->queryString["category"];
        $url = "/$category";
        $response = $this->get($url);
        $response->assertOk();
    }

    /** 
     * @test
     */
    public function categoryNarrowingDown_HTTPテスト_存在しないカテゴリ(): void
    {
        $category = $this->queryString["no_exist"];
        $url = "/$category";
        $response = $this->get($url);
        $response->assertStatus(404);
    }

    /** 
     * @test
     */
    public function genreNarrowingDown_HTTPテスト_カテゴリあり_ジャンルあり(): void
    {
        $category = $this->queryString["category"];
        $genre = $this->queryString["genre"];
        $url = "/$category/$genre";
        $response = $this->get($url);
        $response->assertOk();
    }

    /** 
     * @test
     */
    public function genreNarrowingDown_HTTPテスト_存在しないカテゴリ_ジャンルあり(): void
    {
        $category = $this->queryString["no_exist"];
        $genre = $this->queryString["genre"];
        $url = "/$category/$genre";
        $response = $this->get($url);
        $response->assertStatus(404);
    }

    /** 
     * @test
     */
    public function genreNarrowingDown_HTTPテスト_カテゴリあり_存在しないジャンル(): void
    {
        $category = $this->queryString["category"];
        $genre = $this->queryString["no_exist"];
        $url = "/$category/$genre";
        $response = $this->get($url);
        $response->assertStatus(404);
    }

    /** 
     * @test
     */
    public function genreNarrowingDown_HTTPテスト_カテゴリあり_カテゴリに紐づかない存在するジャンル(): void
    {
        $category = $this->queryString["category"];
        $genre = $this->queryString["other_genre"];
        $url = "/$category/$genre";
        $response = $this->get($url);
        $response->assertStatus(404);
    }

    /** 
     * @test
     */
    public function genreNarrowingDown_HTTPテスト_存在しないカテゴリ_存在しないジャンル(): void
    {
        $category = $this->queryString["no_exist"];
        $genre = $this->queryString["no_exist"];
        $url = "/$category/$genre";
        $response = $this->get($url);
        $response->assertStatus(404);
    }

    /** 
     * @test
     */
    public function search_HTTPテスト_フリーワードあり_カテゴリあり(): void
    {
        $category = $this->queryString["category"];
        $freeWord = $this->queryString["free_word"];
        $url = "/search?category=$category&free_word=$freeWord";
        $response = $this->get($url);
        $response->assertOk();
    }

    /** 
     * @test
     */
    public function search_HTTPテスト_フリーワードあり_カテゴリなし(): void
    {
        $category = "";
        $freeWord = $this->queryString["free_word"];
        $url = "/search?category=$category&free_word=$freeWord";
        $response = $this->get($url);
        $response->assertOk();
    }

    /** 
     * @test
     */
    public function search_HTTPテスト_フリーワードなし_カテゴリあり(): void
    {
        $category = $this->queryString["category"];
        $freeWord = "";
        $url = "/search?category=$category&free_word=$freeWord";
        $response = $this->get($url);
        $response->assertRedirect("/$category");
    }

    /** 
     * @test
     */
    public function search_HTTPテスト_存在しないカテゴリ_フリーワードあり(): void
    {
        $category = $this->queryString["no_exist"];
        $freeWord = $this->queryString["free_word"];
        $url = "/search?category=$category&free_word=$freeWord";
        $response = $this->get($url);
        $response->assertRedirect("/");
    }

    /** 
     * @test
     */
    public function search_HTTPテスト_存在しないカテゴリ_フリーワードなし(): void
    {
        $category = $this->queryString["no_exist"];
        $freeWord = "";
        $url = "/search?category=$category&free_word=$freeWord";
        $response = $this->get($url);
        $response->assertRedirect("/");
    }

    /** 
     * @test
     */
    public function search_HTTPテスト_存在しないフリーワード_カテゴリあり(): void
    {
        $category = $this->queryString["category"];
        $freeWord = $this->queryString["no_exist"];
        $url = "/search?category=$category&free_word=$freeWord";
        $response = $this->get($url);
        $response->assertOk();
    }

    /** 
     * @test
     */
    public function search_HTTPテスト_存在しないフリーワード_カテゴリなし(): void
    {
        $category = "";
        $freeWord = $this->queryString["no_exist"];
        $url = "/search?category=$category&free_word=$freeWord";
        $response = $this->get($url);
        $response->assertOk();
    }

    /** 
     * @test
     */
    public function search_HTTPテスト_存在しないフリーワード_存在しないカテゴリ(): void
    {
        $category = $this->queryString["no_exist"];
        $freeWord = $this->queryString["no_exist"];
        $url = "/search?category=$category&free_word=$freeWord";
        $response = $this->get($url);
        $response->assertRedirect("/");
    }

    /** 
     * @test
     */
    public function productDetail_HTTPテスト_商品名あり(): void
    {
        $product = Product::first()->name_en;
        $url = "/detail/$product";
        $response = $this->get($url);
        $response->assertOk();
    }

    /** 
     * @test
     */
    public function productDetail_HTTPテスト_存在しない商品名(): void
    {
        $product = $this->queryString["no_exist"];
        $url = "/detail/$product";
        $response = $this->get($url);
        $response->assertStatus(404);
    }
}
