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
            ['name' => 'developer','guard_name'=>'web', 'created_at' => '2023-08-04'], //1　開発者
            ['name' => 'projectManager','guard_name'=>'web','created_at' => '2023-08-04'],//2 プロジェクトマネージャー
            ['name' => 'admin','guard_name'=>'web','created_at' => '2023-08-04'],     //3 管理者
            ['name' => 'taskManager','guard_name'=>'web','created_at' => '2023-08-04'],  //4 タスクマネージャー
            ['name' => 'viewer','guard_name'=>'web','created_at' => '2023-08-04'],  //5 閲覧者
            ['name' => 'tempuser','guard_name'=>'web','created_at' => '2023-08-04'],  //4 一時ユーザー
        ]);
    }
}
