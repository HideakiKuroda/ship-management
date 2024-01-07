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
            ['name' => 'editor','guard_name'=>'web', 'created_at' => '2023-08-04'], //1　編集
            ['name' => 'supervisor','guard_name'=>'web','created_at' => '2023-08-04'],//2 監督者
            ['name' => 'admin','guard_name'=>'web','created_at' => '2023-08-04'],     //3 管理権限       
            ['name' => 'tempuser','guard_name'=>'web','created_at' => '2023-08-04'],  //4 一時ユーザー          
            ['name' => 'referrer','guard_name'=>'web','created_at' => '2023-08-04'],  //5 参照のみ          
        ]);
    }
}
