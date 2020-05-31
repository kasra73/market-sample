<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['id' => 1, 'title' => 'Fresh Produce'],
            ['id' => 2, 'title' => 'Meat & Seafood'],
            ['id' => 3, 'title' => 'Snack Foods'],
            ['id' => 4, 'title' => 'Dairy, Cheese & Eggs'],
            ['id' => 5, 'title' => 'Deli & Prepared Foods'],
            ['id' => 6, 'title' => 'Pantry Staples'],
            ['id' => 7, 'title' => 'Personal Care'],
            ['id' => 8, 'title' => 'Beverages'],
            ['id' => 9, 'title' => 'Dairy'],
            ['id' => 10, 'title' => 'Fruits and Vegetables'],
            ['id' => 11, 'title' => 'Grain'],
            ['id' => 12, 'title' => 'Meat'],
            ['id' => 13, 'title' => 'Pantry'],
            ['id' => 14, 'title' => 'Snack'],
            ['id' => 15, 'title' => 'Staples & Misc.'],
            ['id' => 16, 'title' => 'Sweets'],
        ]);
    }
}
