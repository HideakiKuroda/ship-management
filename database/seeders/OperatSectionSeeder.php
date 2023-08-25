<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperatSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('operat_sections')->insert([
            ['section' => '瀬戸内地区' , 'memo' => '泉北・神戸・坂出・広島' , 'created_at' => '2023-08-04' ] ,
            ['section' => '東海地区' , 'memo' => '伊勢湾・三河湾' , 'created_at' => '2023-08-04' ] ,
            ['section' => '神戸' , 'memo' => '神戸曳船' , 'created_at' => '2023-08-04' ] ,
            ['section' => '石狩新港' , 'memo' => '石狩新港サービス' , 'created_at' => '2023-08-04' ] ,
            ['section' => '苫小牧' , 'memo' => '北日本曳船' , 'created_at' => '2023-08-04' ] ,
            ['section' => '金沢港' , 'memo' => '金沢港運' , 'created_at' => '2023-08-04' ] ,
            ['section' => '富山新港' , 'memo' => '富山新港管理局' , 'created_at' => '2023-08-04' ] ,
            ['section' => '清水' , 'memo' => '東海曳船' , 'created_at' => '2023-08-04' ] ,
            ['section' => '宇部ポート' , 'memo' => '宇部ポートサービス' , 'created_at' => '2023-08-04' ] ,
            ['section' => '三隅発電所' , 'memo' => '中電環境テクノス株式会社' , 'created_at' => '2023-08-04' ] ,
            ]); 
    }
}
