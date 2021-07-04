<?php

use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            \Database\Seeds\Dummy\UsersDummyTableSeeder::class,
            \Database\Seeds\Dummy\ProductsDummyTableSeeder::class,
            \Database\Seeds\Dummy\OrdersDummyTableSeeder::class,
            CategoriesTableSeeder::class,
            GenresTableSeeder::class,
            PrefsTableSeeder::class,
        ]);
    }
}
