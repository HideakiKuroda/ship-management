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
            [ 'name' => '太田 正紀' , 'email' => 'masanori.ota@nihon-eisen.com ' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '坂倉 繁行' , 'email' => 'shigeyuki.sakakura@nihon-eisen.com ' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '髙松 一憲' , 'email' => 'kazunori.takamatsu@nihon-eisen.com ' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '二ツ石 聖示' , 'email' => 'seiji.futatsuishi@nihon-eisen.com ' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '三宅 陸平' , 'email' => 'rikuhei.miyake@nihon-eisen.com ' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '宮本 強' , 'email' => 'tsuyoshi.miyamoto@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '植田 廣幸' , 'email' => 'hiroyuki.ueda@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '黒田 秀明' , 'email' => 'hideaki.kuroda@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '吉田 美弥' , 'email' => 'miya.yoshida@nihon-eisen.com ' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '岩本 香利' , 'email' => 'kaori.iwamoto@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '若狭 吉晴' , 'email' => 'yoshiharu.wakasa@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '荒川 純一' , 'email' => 'junichi.arakawa@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '青木 宏之' , 'email' => 'hiroyuki.aoki@nihon-eisen.com  ' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '堀 容子' , 'email' => 'yoko.hori@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '村知 一世' , 'email' => 'kazuyo.murachi@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '井上 佳代' , 'email' => 'kayo.inoue@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '柄谷 仁美' , 'email' => 'hitomi.karatani@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '小俣 真美' , 'email' => 'mami.omata@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '西尾 哲郎' , 'email' => 'tetsuro.nishio@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '田村 啓造' , 'email' => 'keizo.tamura@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '清水 斉' , 'email' => 'h-shimizu@eikichikaiun.co.jp' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '増田 数一' , 'email' => 'k-masuda@eikichikaiun.co.jp' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => '大平 芳生' , 'email' => 'yoshio.ohira@nihon-eisen.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'name' => 'Hideaki Kuroda' , 'email' => 'kurodah@gmail.com' ,  'password' => Hash::make('password123'),   'created_at' => '2023/08/10 12:00:00'  ] ,
        ]);

    }
}
