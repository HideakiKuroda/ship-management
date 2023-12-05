<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schedules')->insert([
            [ 'ship_id' => 1,],
            [ 'ship_id' => 2,],
            [ 'ship_id' => 3,],
            [ 'ship_id' => 4,],
            [ 'ship_id' => 5,],
            [ 'ship_id' => 6,],
            [ 'ship_id' => 7,],
            [ 'ship_id' => 8,],
            [ 'ship_id' => 9,],
            [ 'ship_id' => 10,],
            [ 'ship_id' => 11,],
            [ 'ship_id' => 12,],
            [ 'ship_id' => 13,],
            [ 'ship_id' => 14,],
            [ 'ship_id' => 15,],
            [ 'ship_id' => 16,],
            [ 'ship_id' => 17,],
            [ 'ship_id' => 18,],
            [ 'ship_id' => 19,],
            [ 'ship_id' => 20,],
            [ 'ship_id' => 22,],
            [ 'ship_id' => 23,],
            [ 'ship_id' => 24,],
            [ 'ship_id' => 25,],
            [ 'ship_id' => 26,],
            [ 'ship_id' => 27,],
            [ 'ship_id' => 28,],
            [ 'ship_id' => 29,],
            [ 'ship_id' => 30,],
            [ 'ship_id' => 31,],
        ]);
    }
}
