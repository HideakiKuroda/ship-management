<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'editor','guard_name'=>'web', 'created_at' => '2023-08-04'], 
            ['name' => 'supervisor','guard_name'=>'web','created_at' => '2023-08-04'],
            ['name' => 'admin','guard_name'=>'web','created_at' => '2023-08-04'],             
        ]);
    }
}
