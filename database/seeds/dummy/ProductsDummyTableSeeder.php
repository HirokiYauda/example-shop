<?php

namespace Database\Seeds\Dummy;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductsDummyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->truncate(); //2回目実行の際にシーダー情報をクリア
        for ($i = 0; $i < 300; $i++) {
            factory(Product::class, 1000)->create();
        }
    }
}
