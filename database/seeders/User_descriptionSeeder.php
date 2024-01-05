<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class User_descriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_descriptions')->insert([
            [ 'user_id'=> '1', 'name2' => '太田',  'name1' => '正紀' , 'phone'=>'080-2505-3769', 'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '2',  'name2' => '坂倉',  'name1' => '繁行' , 'phone'=>'090-2703-0778',  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '3',  'name2' => '髙松',  'name1' => '一憲' , 'phone'=>'090-3651-3058',  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '4', 'name2' => '二ツ石',  'name1' => '聖示', 'phone'=>'080-2412-2817',   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '5', 'name2' => '三宅',  'name1' => '陸平', 'phone'=>'090-5365-7747' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '6', 'name2' => '宮本',  'name1' => '強', 'phone'=>'080-8946-9550' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '7', 'name2' => '植田',  'name1' => '廣幸', 'phone'=>'080-1522-6740' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '8', 'name2' => '黒田',  'name1' => '秀明', 'phone'=>'070-4462-3467' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '9', 'name2' => '吉田',  'name1' => '美弥', 'phone'=>'null' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '10', 'name2' => '岩本',  'name1' => '香利', 'phone'=>'null' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '11', 'name2' => '若狭',  'name1' => '吉晴', 'phone'=>'090-6999-9728' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '12', 'name2' => '荒川',  'name1' => '純一', 'phone'=>'090-3897-4524' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '13',  'name2' => '青木',  'name1' => '宏之', 'phone'=>'080-8515-4114' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '14',  'name2' => '堀',  'name1' => '容子', 'phone'=>'080-2542-4872' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '15',  'name2' => '村知',  'name1' => '一世', 'phone'=>'null' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '16',  'name2' => '井上',  'name1' => '佳代', 'phone'=>'090-1712-2229' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '17',  'name2' => '柄谷',  'name1' => '仁美', 'phone'=>'null' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '18',  'name2' => '小俣',  'name1' => '真美', 'phone'=>'null' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '19',  'name2' => '西尾',  'name1' => '哲郎', 'phone'=>'null' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '20',  'name2' => '田村',  'name1' => '啓造', 'phone'=>'090-1682-9414' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '21',  'name2' => '清水',  'name1' => '斉', 'phone'=>'090-2021-0330' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '22',  'name2' => '増田',  'name1' => '数一', 'phone'=>'090-4972-6989' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'user_id'=> '23',  'name2' => '大平',  'name1' => '芳生', 'phone'=>'080-2544-9399' ,  'created_at' => '2023/08/10 12:00:00'  ] ,
          
            ]);
    }
}
