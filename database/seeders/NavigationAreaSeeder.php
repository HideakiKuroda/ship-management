<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavigationAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('navigation_areas')->insert([
            ['name' => '平水' , 'created_at' => '2023-08-04' ] ,
            ['name' => '沿海' , 'created_at' => '2023-08-04' ] ,
            ['name' => '限定沿海' , 'created_at' => '2023-08-04' ] ,
            ['name' => '近海' , 'created_at' => '2023-08-04' ] ,
            
            ]);
    }
}
