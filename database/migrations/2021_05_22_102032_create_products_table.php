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
            $table->bigInteger('admin_id');
            $table->bigInteger('genre_id');
            $table->string('name', '100');
            $table->string('name_en', '100')->unique();
            $table->string('detail', '500');
            $table->string('search_tag', '100');
            $table->string('price');
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
