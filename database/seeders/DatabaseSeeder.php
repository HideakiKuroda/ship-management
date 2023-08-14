<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            OperatSectionSeeder::class,
            NavigationAreaSeeder::class,
            ShipSeeder::class,
            ColorSeeder::class,
            ProCategorySeeder::class,
            SummarySeeder::class,
            ConcernedSeeder::class,
            ShipOwnerSeeder::class,
            Summary2Seeder::class,
            ProjectSeeder::class,
            DepartmentSeeder::class,
            User_descriptionSeeder::class,
            
            ]);
    }
}
