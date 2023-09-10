<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pro_categories')->insert([
            [ 'name' => '定期検査入渠', 'color_id' => 5, 'dock' => TRUE, 'created_at' => '2023-08-10' ],
            [ 'name' => '中間検査入渠', 'color_id' => 10, 'dock' => TRUE, 'created_at' => '2023-08-10' ],
            [ 'name' => '合入渠', 'color_id' => 13, 'dock' => TRUE, 'created_at' => '2023-08-10' ],
            [ 'name' => '補償入渠', 'color_id' => 25, 'dock' => TRUE, 'created_at' => '2023-08-10' ],
            [ 'name' => '新造', 'color_id' => 6, 'dock' => FALSE, 'created_at' => '2023-08-10' ],
            [ 'name' => '修繕', 'color_id' => 11, 'dock' => FALSE, 'created_at' => '2023-08-10' ],
            [ 'name' => '売船', 'color_id' => 21, 'dock' => FALSE, 'created_at' => '2023-08-10' ],
            [ 'name' => '入替', 'color_id' => 17, 'dock' => FALSE, 'created_at' => '2023-08-10' ],
            [ 'name' => '船用品・部品', 'color_id' => 17, 'dock' => FALSE, 'created_at' => '2023-08-10' ],
            
    ]);

    }
}
