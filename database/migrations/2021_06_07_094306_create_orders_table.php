<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('注文ID');
            $table->bigInteger('user_id')->index()->comment('ユーザーID');
            $table->string('order_number', 50)->nullable()->unique()->comment('注文番号');
            $table->string('zip', 10)->comment('郵便番号');
            $table->tinyInteger('pref_id')->unsigned()->comment('都道府県ID');
            $table->string('address1')->comment('住所1');
            $table->string('address2')->nullable()->comment('住所2');
            $table->integer('total')->comment('合計金額');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
