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
        $this->queryString['abnormal_category'] = "abnormal";
        $this->queryString['genre'] = "comic";
        $this->queryString['abnormal_genre'] = "abnormal";
        $this->queryString['other_genre'] = "rock";
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
    public function categoryNarrowingDown_HTTPテスト_カテゴリなし(): void
    {
        $category = $this->queryString["abnormal_category"];
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
    public function genreNarrowingDown_HTTPテスト_カテゴリなし_ジャンルあり(): void
    {
        $category = $this->queryString["abnormal_category"];
        $genre = $this->queryString["genre"];
        $url = "/$category/$genre";
        $response = $this->get($url);
        $response->assertStatus(404);
    }

    /** 
     * @test
     */
    public function genreNarrowingDown_HTTPテスト_カテゴリあり_ジャンルなし(): void
    {
        $category = $this->queryString["category"];
        $genre = $this->queryString["abnormal_genre"];
        $url = "/$category/$genre";
        $response = $this->get($url);
        $response->assertStatus(404);
    }

    /** 
     * @test
     */
    public function genreNarrowingDown_HTTPテスト_カテゴリあり_カテゴリに紐づかないジャンルあり(): void
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
    public function genreNarrowingDown_HTTPテスト_カテゴリなし_ジャンルなし(): void
    {
        $category = $this->queryString["abnormal_category"];
        $genre = $this->queryString["abnormal_genre"];
        $url = "/$category/$genre";
        $response = $this->get($url);
        $response->assertStatus(404);
    }
}
