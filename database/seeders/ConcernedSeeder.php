<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConcernedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           DB::table('concerneds')->insert([
            ['ship_id' => 1, 'operator' => '石狩新港サービス', 'borrower' => '石狩湾新港サービス', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 2, 'operator' => '石狩新港サービス', 'borrower' => '石狩湾新港サービス', 'manager' => '日本栄船', 'crew_arrange' => '石狩湾新港サービス', 'created_at' => '2023-08-10' ],
            ['ship_id' => 3, 'operator' => '北日本曵船', 'borrower' => '北日本曳船', 'manager' => '栄吉曳船', 'crew_arrange' => '栄吉曳船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 4, 'operator' => '北日本曵船', 'borrower' => '北日本曳船', 'manager' => '栄吉曳船', 'crew_arrange' => '栄吉曳船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 5, 'operator' => '金沢港運', 'borrower' => '金沢港運', 'manager' => '金沢港運', 'crew_arrange' => '金沢港運', 'created_at' => '2023-08-10'],
            ['ship_id' => 6, 'operator' => '富山新港管理局', 'borrower' => '', 'manager' => '', 'crew_arrange' => '', 'created_at' => '2023-08-10' ],
            ['ship_id' => 7, 'operator' => '日本栄船', 'borrower' => '', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 8, 'operator' => '日本栄船', 'borrower' => '', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 9, 'operator' => '日本栄船', 'borrower' => '日本栄船', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 10, 'operator' => '日本栄船', 'borrower' => '日本栄船', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 11, 'operator' => '日本栄船', 'borrower' => '', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 12, 'operator' => '日本栄船', 'borrower' => '', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 13, 'operator' => '日本栄船', 'borrower' => '', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 14, 'operator' => '日本栄船', 'borrower' => '', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 15, 'operator' => '日本栄船', 'borrower' => '日本栄船', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 16, 'operator' => '日本栄船', 'borrower' => '', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 17, 'operator' => '神戸曳船', 'borrower' => '神戸曳船', 'manager' => '日本栄船', 'crew_arrange' => '神戸曳船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 18, 'operator' => '神戸曳船', 'borrower' => '神戸曳船', 'manager' => '日本栄船', 'crew_arrange' => '神戸曳船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 19, 'operator' => '日本栄船', 'borrower' => '栄吉曳船', 'manager' => '栄吉曳船', 'crew_arrange' => '栄吉曳船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 20, 'operator' => '日本栄船', 'borrower' => '栄吉曳船', 'manager' => '栄吉曳船', 'crew_arrange' => '栄吉曳船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 21, 'operator' => '栄吉曳船', 'borrower' => '栄吉曳船', 'manager' => '栄吉曳船', 'crew_arrange' => '栄吉曳船', 'created_at' => '2023-08-10'],
            ['ship_id' => 22, 'operator' => '栄吉曳船', 'borrower' => '栄吉曳船', 'manager' => '栄吉曳船', 'crew_arrange' => '栄吉曳船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 23, 'operator' => '栄吉曳船', 'borrower' => '栄吉曳船', 'manager' => '栄吉曳船', 'crew_arrange' => '栄吉曳船', 'created_at' => '2023-08-10'],
            ['ship_id' => 24, 'operator' => '日本栄船', 'borrower' => '日本栄船', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 25, 'operator' => '日本栄船', 'borrower' => '日本栄船', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 26, 'operator' => '日本栄船', 'borrower' => '日本栄船', 'manager' => '日本栄船', 'crew_arrange' => '日本栄船', 'created_at' => '2023-08-10' ],
            ['ship_id' => 27, 'operator' => '日本栄船', 'borrower' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ', 'manager' => '日本栄船', 'crew_arrange' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ', 'created_at' => '2023-08-10' ],
            ['ship_id' => 28, 'operator' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ', 'borrower' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ', 'manager' => '日本栄船', 'crew_arrange' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ', 'created_at' => '2023-08-10'],
            ['ship_id' => 29, 'operator' => '東海曳船', 'borrower' => '', 'manager' => '', 'crew_arrange' => '', 'created_at' => '2023-08-10' ],
            ['ship_id' => 30, 'operator' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ', 'borrower' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ', 'manager' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ', 'crew_arrange' => '宇部ﾎﾟｰﾄｻｰﾋﾞｽ', 'created_at' => '2023-08-10' ],
            ['ship_id' => 31, 'operator' => '中電環境ﾃｸﾉｽ', 'borrower' => '栄吉曳船', 'manager' => '日本栄船', 'crew_arrange' => '栄吉曳船', 'created_at' => '2023-08-10' ],

        ]);
    }
}
