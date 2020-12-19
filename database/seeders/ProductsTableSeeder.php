<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        DB::table('products')->insert([
            [
                'user_id' => '2',
                'title' => 'Sample Product 1',
                'description' => 'Sample Product 1 description',
                'price' => 10.56,
            ],
            [
                'user_id' => '2',
                'title' => 'Sample product 2',
                'description' => 'Sample product 2 description',
                'price' => 32.07,
            ],
            [
                'user_id' => '2',
                'title' => 'Sample Product 3',
                'description' => 'Sample Product 3 description',
                'price' => 109.99,
            ],
            [
                'user_id' => '2',
                'title' => 'Sample Product 4',
                'description' => 'Sample Product 4 description',
                'price' => 23.00,
            ],

            [
                'user_id' => '3',
                'title' => 'Sample product 5',
                'description' => 'Sample product 5 description',
                'price' => 2.87,
            ],
            [
                'user_id' => '3',
                'title' => 'Sample Product 6',
                'description' => 'Sample Product 6 description',
                'price' => 19.98,
            ],
            [
                'user_id' => '3',
                'title' => 'Sample Product 7',
                'description' => 'Sample Product 7 description',
                'price' => 11.00,
            ],
        ]);
    }
}
