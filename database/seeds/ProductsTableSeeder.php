<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ini_set('memory_limit', '512M');
        DB::table('products')->truncate(); //2回目実行の際にシーダー情報をクリア

        for ($i = 0; $i < 3; $i++) {
            factory(Product::class, 100)->create();
        }
    }
}
