<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Pro_assignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
        {
        $shipToUserMapping = [
            1=>11,2=>11,4=>4,6=>2,7=>4,8=>5,9=>5,10=>5,
            11=>4,12=>5,13=>5,14=>4,15=>4,16=>3,17=>3,18=>3,
            19=>8,20=>8,21=>21,22=>21,23=>22,24=>5,25=>4,26=>5,
            27=>3,28=>3,31=>22,1=>12,2=>12,
        ];
        
        $projects = DB::table('projects')->get();

        foreach ($projects as $project) {
            if (isset($shipToUserMapping[$project->ship_id])) {
                $userId = $shipToUserMapping[$project->ship_id];
             DB::table('pro_assignments')->insert([
                'project_id' => $project->id,
                'user_id' => $userId,
                'created_at' => $project->created_at,
                'updated_at' => now(),
            ]
        );
        }

        // DB::table('pro_assignments')->insert([
        //     ['user_id' =>1,'user_id'=>11],
        //     ['user_id' =>2,'user_id'=>11],
        //     ['user_id' =>3,'user_id'=>4],
        //     ['user_id' =>4,'user_id'=>4],
        //     ['user_id' =>5,'user_id'=>4],
        //     ['user_id' =>6,'user_id'=>5],
        //     ['user_id' =>7,'user_id'=>5],
        //     ['user_id' =>8,'user_id'=>5],
        //     ['user_id' =>9,'user_id'=>4],
        //     ['user_id' =>10,'user_id'=>5],
        //     ['user_id' =>11,'user_id'=>5],
        //     ['user_id' =>12,'user_id'=>4],
        //     ['user_id' =>13,'user_id'=>4],
        //     ['user_id' =>14,'user_id'=>3],
        //     ['user_id' =>15,'user_id'=>3],
        //     ['user_id' =>16,'user_id'=>3],
        //     ['user_id' =>17,'user_id'=>8],
        //     ['user_id' =>18,'user_id'=>8],
        //     ['user_id' =>19,'user_id'=>21],
        //     ['user_id' =>20,'user_id'=>21],
        //     ['user_id' =>21,'user_id'=>22],
        //     ['user_id' =>22,'user_id'=>5],
        //     ['user_id' =>23,'user_id'=>4],
        //     ['user_id' =>24,'user_id'=>5],
        //     ['user_id' =>25,'user_id'=>3],
        //     ['user_id' =>26,'user_id'=>3],
        //     ['user_id' =>27,'user_id'=>22],
        //     ['user_id' =>1,'user_id'=>12],
        //     ['user_id' =>2,'user_id'=>12],
        //     ['user_id' =>3,'user_id'=>11],

            

   
        }
}
}