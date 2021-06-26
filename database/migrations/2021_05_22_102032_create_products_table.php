<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('商品ID');
            $table->bigInteger('admin_id')->index()->comment('管理者ID');
            $table->bigInteger('genre_id')->index()->comment('ジャンルID');
            $table->string('name', '100')->comment('商品名');
            $table->string('name_en', '100')->unique()->comment('商品名(EN)');
            $table->text('detail')->comment('詳細メッセージ');
            $table->string('search_tag', '100')->index()->comment('検索用タグ');
            $table->integer('price')->comment('価格');
            $table->integer('stock')->comment('在庫数');
            $table->string('imgpath', '200')->comment('画像パス');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement('ALTER TABLE products ADD FULLTEXT index search_tag (`search_tag`) with parser ngram');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
