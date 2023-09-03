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
            ['name' => 'can-view-posts','guard_name'=>'web', 'created_at' => '2023-08-04'], 
            ['name' => 'can-edit-posts','guard_name'=>'web','created_at' => '2023-08-04'], 
            ['name' => 'can-publish-posts','guard_name'=>'web','created_at' => '2023-08-04'], 
            ['name' => 'can-deleted-posts','guard_name'=>'web','created_at' => '2023-08-04'],
            ['name' => 'can-view-activity','guard_name'=>'web','created_at' => '2023-08-04'],
            ['name' => 'can-export-activity','guard_name'=>'web','created_at' => '2023-08-04'],
        ]);
    }
}
