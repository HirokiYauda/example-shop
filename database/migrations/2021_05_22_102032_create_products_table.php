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
            $table->bigIncrements('id');
            $table->bigInteger('admin_id')->index();
            $table->bigInteger('genre_id')->index();
            $table->string('name', '100');
            $table->string('name_en', '100')->unique();
            $table->text('detail');
            $table->string('search_tag', '100')->index();
            $table->integer('price');
            $table->integer('stock');
            $table->string('imgpath', '200');
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
