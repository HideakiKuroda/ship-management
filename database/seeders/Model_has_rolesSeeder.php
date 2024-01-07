<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Model_has_rolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('model_has_roles')->insert([
            ['model_id'=> 8, 'model_type' => 'App\Models\User', 'role_id' => 3],
            ['model_id'=> 3, 'model_type' => 'App\Models\User', 'role_id' => 1],
            ['model_id'=> 4, 'model_type' => 'App\Models\User', 'role_id' => 1],
            ['model_id'=> 5, 'model_type' => 'App\Models\User', 'role_id' => 1],
            ['model_id'=> 12, 'model_type' => 'App\Models\User', 'role_id' => 1],
            ['model_id'=> 11, 'model_type' => 'App\Models\User', 'role_id' => 1],
            ['model_id'=> 21, 'model_type' => 'App\Models\User', 'role_id' => 1],
            ['model_id'=> 22, 'model_type' => 'App\Models\User', 'role_id' => 1],
            ['model_id'=> 23, 'model_type' => 'App\Models\User', 'role_id' => 3],
        ]);

    }
}
