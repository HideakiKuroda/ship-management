<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ship_owners')->insert([
            [ 'ship_id' => 1, 'owner_name' => '日本栄船', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 2, 'owner_name' => '日本栄船', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 3, 'owner_name' => '日本栄船', 'ratio' => '40', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 4, 'owner_name' => '日本栄船', 'ratio' => '22.5', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 5, 'owner_name' => '神戸曳船', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 6, 'owner_name' => '日本栄船', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 7, 'owner_name' => '日本栄船', 'ratio' => '60', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 8, 'owner_name' => '日本栄船', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 9, 'owner_name' => '協伸商会', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 10, 'owner_name' => '日本栄船', 'ratio' => '70', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 11, 'owner_name' => '日本栄船', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 12, 'owner_name' => '日本栄船', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 13, 'owner_name' => '日本栄船', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 14, 'owner_name' => '日本栄船', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 15, 'owner_name' => '商船三井', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 16, 'owner_name' => '日本栄船', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 17, 'owner_name' => '神戸曳船', 'ratio' => '100', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 18, 'owner_name' => '日本栄船', 'ratio' => '50', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 19, 'owner_name' => '日本栄船', 'ratio' => '50', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 20, 'owner_name' => '日本栄船', 'ratio' => '37.5', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 21, 'owner_name' => '日本栄船', 'ratio' => '50', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 22, 'owner_name' => '日本栄船', 'ratio' => '50', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 23, 'owner_name' => '日本栄船', 'ratio' => '75', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 24, 'owner_name' => '日本栄船', 'ratio' => '75', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 25, 'owner_name' => '日本栄船', 'ratio' => '90', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 26, 'owner_name' => '日本栄船', 'ratio' => '65', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 27, 'owner_name' => '日本栄船', 'ratio' => '80', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 28, 'owner_name' => '日本栄船', 'ratio' => '50', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 29, 'owner_name' => '宇部ﾎﾞｰﾄｻｰﾋﾞｽ', 'ratio' => '85', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 30, 'owner_name' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ:', 'ratio' => '85', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 31, 'owner_name' => '日本栄船', 'ratio' => '25', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 3, 'owner_name' => '栄吉海運', 'ratio' => '30', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 4, 'owner_name' => '北日本曵船', 'ratio' => '30', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 7, 'owner_name' => '駿河湾曳船', 'ratio' => '40', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 10, 'owner_name' => '生田ｱﾝﾄﾞﾏﾘﾝ:', 'ratio' => '30', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 18, 'owner_name' => '神戸曳船', 'ratio' => '50', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 19, 'owner_name' => '内海曳船', 'ratio' => '50', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 20, 'owner_name' => '内海曳船', 'ratio' => '37.5', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 21, 'owner_name' => '栄吉海運', 'ratio' => '50', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 22, 'owner_name' => '栄吉海運', 'ratio' => '50', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 23, 'owner_name' => '栄吉海運', 'ratio' => '25', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 24, 'owner_name' => '中電環境ﾃｸﾉｽ', 'ratio' => '25', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 25, 'owner_name' => '生田ｱﾝﾄﾞﾏﾘﾝ', 'ratio' => '10', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 26, 'owner_name' => '中電環境ﾃｸﾉｽ', 'ratio' => '35', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 27, 'owner_name' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ', 'ratio' => '20', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 28, 'owner_name' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ', 'ratio' => '50', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 29, 'owner_name' => '宇部興産海運㈱', 'ratio' => '15', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 30, 'owner_name' => '宇部興産海運', 'ratio' => '15', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 31, 'owner_name' => '中電環境ﾃｸﾉｽ', 'ratio' => '75', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 3, 'owner_name' => '北日本曳船', 'ratio' => '30', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 4, 'owner_name' => '栄吉海運', 'ratio' => '30', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 20, 'owner_name' => '早駒運輸', 'ratio' => '25', 'created_at' => '2023-08-10' ],
            [ 'ship_id' => 4, 'owner_name' => '東栄汽船', 'ratio' => '17.5', 'created_at' => '2023-08-10' ],
        ]); 

    }
}
