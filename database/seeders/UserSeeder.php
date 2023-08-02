<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'H Kuroda',
                'email' => 'kurodah@test.com',
                'password' => Hash::make('s8061710'),
                'permission_id' => 6,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test1',
                'email' => 'test1@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 5,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test2',
                'email' => 'test2@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 5,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test3',
                'email' => 'test3@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 4,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test4',
                'email' => 'test4@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 3,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test5',
                'email' => 'test5@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 2,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test6',
                'email' => 'test6@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 1,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test7',
                'email' => 'test7@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 4,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test8',
                'email' => 'test8@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 3,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test9',
                'email' => 'test9@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 2,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test10',
                'email' => 'test10@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 1,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test11',
                'email' => 'test11@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 5,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test12',
                'email' => 'test12@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 5,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test13',
                'email' => 'test13@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 4,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test14',
                'email' => 'test14@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 3,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test15',
                'email' => 'test15@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 2,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test16',
                'email' => 'test16@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 1,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test17',
                'email' => 'test17@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 1,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test18',
                'email' => 'test18@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 1,
                'created_at' => '2023/01/01 11:11:11'                
            ],
            [
                'name' => 'test19',
                'email' => 'test19@test.com',
                'password' => Hash::make('password123'),
                'permission_id' => 1,
                'created_at' => '2023/01/01 11:11:11'                
            ],
        ]);

    }
}
