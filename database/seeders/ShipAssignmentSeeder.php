<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ship_assignments')->insert([
            [ 'ship_id' =>1 , 'user_id'=>11 ],
            [ 'ship_id' =>2 , 'user_id'=>11 ],
            [ 'ship_id' =>3 , 'user_id'=>4 ],
            [ 'ship_id' =>4 , 'user_id'=>4 ],
            [ 'ship_id' =>5 , 'user_id'=>3 ],
            [ 'ship_id' =>7 , 'user_id'=>4 ],
            [ 'ship_id' =>8 , 'user_id'=>5 ],
            [ 'ship_id' =>9 , 'user_id'=>5 ],
            [ 'ship_id' =>10 , 'user_id'=>5 ],
            [ 'ship_id' =>11 , 'user_id'=>4 ],
            [ 'ship_id' =>12 , 'user_id'=>5 ],
            [ 'ship_id' =>13 , 'user_id'=>5 ],
            [ 'ship_id' =>14 , 'user_id'=>4 ],
            [ 'ship_id' =>15 , 'user_id'=>4 ],
            [ 'ship_id' =>16 , 'user_id'=>3 ],
            [ 'ship_id' =>17 , 'user_id'=>3 ],
            [ 'ship_id' =>18 , 'user_id'=>3 ],
            [ 'ship_id' =>19 , 'user_id'=>8 ],
            [ 'ship_id' =>20 , 'user_id'=>8 ],
            [ 'ship_id' =>21 , 'user_id'=>21 ],
            [ 'ship_id' =>22 , 'user_id'=>21 ],
            [ 'ship_id' =>23 , 'user_id'=>22 ],
            [ 'ship_id' =>24 , 'user_id'=>5 ],
            [ 'ship_id' =>25 , 'user_id'=>4 ],
            [ 'ship_id' =>26 , 'user_id'=>5 ],
            [ 'ship_id' =>27 , 'user_id'=>3 ],
            [ 'ship_id' =>28 , 'user_id'=>3 ],
            [ 'ship_id' =>31 , 'user_id'=>22 ],
            [ 'ship_id' =>1 , 'user_id'=>12 ],
            [ 'ship_id' =>2 , 'user_id'=>12 ],
            [ 'ship_id' =>3 , 'user_id'=>11 ],
            [ 'ship_id' =>4 , 'user_id'=>11 ],
        ]); 
    }
}
