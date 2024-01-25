<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            ['name' => 'create_project','guard_name'=>'web', 'created_at' => '2024-01-25'],//1プロジェクト作成
            ['name' => 'edit_project','guard_name'=>'web','created_at' => '2024-01-25'],//2プロジェクト編集
            ['name' => 'assign_project','guard_name'=>'web','created_at' => '2024-01-25'],//3プロジェクト割り当て
            ['name' => 'view_project','guard_name'=>'web','created_at' => '2024-01-25'],//4プロジェクト参照
            ['name' => 'create_task','guard_name'=>'web','created_at' => '2024-01-25'],//5
            ['name' => 'edit_task','guard_name'=>'web','created_at' => '2024-01-25'],//6
            ['name' => 'assign_task','guard_name'=>'web','created_at' => '2024-01-25'],//7
            ['name' => 'view_task','guard_name'=>'web','created_at' => '2024-01-25'],//8
            ['name' => 'create_ship','guard_name'=>'web','created_at' => '2024-01-25'],//9
            ['name' => 'edit_ship','guard_name'=>'web','created_at' => '2024-01-25'],//10
            ['name' => 'assign_ship','guard_name'=>'web','created_at' => '2024-01-25'],//11
            ['name' => 'view_ship','guard_name'=>'web','created_at' => '2024-01-25'],//12
            ['name' => 'create_user','guard_name'=>'web','created_at' => '2024-01-25'],//9
            ['name' => 'edit_user','guard_name'=>'web','created_at' => '2024-01-25'],//10
            ['name' => 'view_user','guard_name'=>'web','created_at' => '2024-01-25'],//12
        ]);
    }
}
