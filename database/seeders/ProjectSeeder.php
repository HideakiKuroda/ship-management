<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects')->insert([
            [ 'ship_id' => '1' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2021-08-29' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '2' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2022-06-07' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '4' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2020-04-13' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '6' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2017-10-31' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '7' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2023-04-28' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '8' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2021-07-30' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '9' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2018-04-16' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '10' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2020-07-27' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '11' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2018-11-20' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '12' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2023-03-16' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '13' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2020-06-08' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '14' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2023-05-19' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '15' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2019-02-25' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '16' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2022-08-01' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '17' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2018-07-31' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '18' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2022-01-25' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '19' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2022-11-08' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '20' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2023-09-22' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '21' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2019-04-25' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '22' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2022-09-30' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '23' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2019-11-25' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '24' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2019-01-16' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '25' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2021-05-19' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '26' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2023-05-30' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '27' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2020-05-14' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '28' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2021-09-09' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
            [ 'ship_id' => '31' , 'name' => '定期検査' , 'pro_category_id' => 1 , 'date_of_issue' => '2021-03-02' ,   'created_at' => '2023/08/10 12:00:00'  ] ,
        ]);
    }
}
