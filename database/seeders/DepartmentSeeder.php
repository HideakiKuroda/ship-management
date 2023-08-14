<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name'=>'役員グループ', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'企画管理部', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'営業部', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'船舶部', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'名古屋支店', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'坂出支店', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'広島支店', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'東京支店', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'周南営業所', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'石狩事務所', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'本社内', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'出向者', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'生田＆マリン', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'本船東海地区', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'本船瀬戸内地区', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'栄吉曳船', 'created_at' => '2023/08/15 12:00:00' ],
            ['name'=>'北日本曳船', 'created_at' => '2023/08/15 12:00:00' ],
            
            ]);
    }
}
