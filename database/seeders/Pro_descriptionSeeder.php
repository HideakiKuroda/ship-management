<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class Pro_descriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $projects = DB::table('projects')->get();
        $shipToUserMapping = [
            1=>11,2=>11,4=>4,6=>2,7=>4,8=>5,9=>5,10=>5,
            11=>4,12=>5,13=>5,14=>4,15=>4,16=>3,17=>3,18=>3,
            19=>8,20=>8,21=>21,22=>21,23=>22,24=>5,25=>4,26=>5,
            27=>3,28=>3,31=>22,1=>12,2=>12,
        ];

        foreach ($projects as $project) {
            if (isset($shipToUserMapping[$project->ship_id])) {
                $userId = $shipToUserMapping[$project->ship_id];
                $sentences = [$faker->realText(50),$faker->realText(25)];
                $memo = implode("\n", $sentences);

                DB::table('pro_descriptions')->insert([
                    'project_id' => $project->id,
                    'user_id' => $userId,
                    'memo' => $memo,
                    'created_at' => $project->created_at,
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

