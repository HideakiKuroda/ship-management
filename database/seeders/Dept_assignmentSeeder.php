<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Dept_assignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dept_assignments')->insert([
            [ 'user_description_id'=>'1' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'2' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'3' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'4' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'5' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'6' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'7' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'8' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'9' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'10' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'11' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'12' , 'department_id'=>'4' ],
            [ 'user_description_id'=>'13' , 'department_id'=>'2' ],
            [ 'user_description_id'=>'14' , 'department_id'=>'2' ],
            [ 'user_description_id'=>'15' , 'department_id'=>'2' ],
            [ 'user_description_id'=>'16' , 'department_id'=>'2' ],
            [ 'user_description_id'=>'17' , 'department_id'=>'2' ],
            [ 'user_description_id'=>'18' , 'department_id'=>'2' ],
            [ 'user_description_id'=>'19' , 'department_id'=>'1' ],
            [ 'user_description_id'=>'20' , 'department_id'=>'1' ],
            [ 'user_description_id'=>'1' , 'department_id'=>'1' ],
            [ 'user_description_id'=>'13' , 'department_id'=>'1' ],
            [ 'user_description_id'=>'11' , 'department_id'=>'10' ],
            [ 'user_description_id'=>'12' , 'department_id'=>'10' ],
            [ 'user_description_id'=>'22' , 'department_id'=>'16' ],
            [ 'user_description_id'=>'21' , 'department_id'=>'16' ],
            [ 'user_description_id'=>'3' , 'department_id'=>'18' ],
            [ 'user_description_id'=>'4' , 'department_id'=>'18' ],
            [ 'user_description_id'=>'5' , 'department_id'=>'18' ],
            [ 'user_description_id'=>'8' , 'department_id'=>'18' ],
            [ 'user_description_id'=>'11' , 'department_id'=>'18' ],
            [ 'user_description_id'=>'12' , 'department_id'=>'18' ],
            [ 'user_description_id'=>'22' , 'department_id'=>'18' ],
            [ 'user_description_id'=>'21' , 'department_id'=>'18' ],
            [ 'user_description_id'=>'23' , 'department_id'=>'4' ],
               
        ]);
    }
}
