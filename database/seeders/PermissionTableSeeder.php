<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            [
                // id = 1
                'name' => '一般',
            ],
            [
                // id = 2
                'name' => '乗組員',
            ],
            [
                // id = 3
                'name' => '日栄',
            ],
            [
                // id = 4
                'name' => '栄吉',
            ],
            [
                // id = 5
                'name' => '船舶部',
            ],
            [
                // id = 6
                'name' => '管理者',
            ],
        ]);
    }
}
