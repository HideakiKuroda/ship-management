<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('colors')->insert([
            [ 'name' => 'gray-300', 'code_text' => '#D1D5DB', 'created_at' => '2023-08-10' ],
            [ 'name' => 'gray-400', 'code_text' => '#9CA3AF', 'created_at' => '2023-08-10' ],
            [ 'name' => 'gray-500', 'code_text' => '#6B7280', 'created_at' => '2023-08-10' ],
            [ 'name' => 'gray-600', 'code_text' => '#4B5563', 'created_at' => '2023-08-10' ],
            [ 'name' => 'red-300', 'code_text' => '#FCA5A5', 'created_at' => '2023-08-10' ],
            [ 'name' => 'red-400', 'code_text' => '#F87171', 'created_at' => '2023-08-10' ],
            [ 'name' => 'red-500', 'code_text' => '#EF4444', 'created_at' => '2023-08-10' ],
            [ 'name' => 'red-600', 'code_text' => '#DC2626', 'created_at' => '2023-08-10' ],
            [ 'name' => 'yellow-200', 'code_text' => '#FDE68A', 'created_at' => '2023-08-10' ],
            [ 'name' => 'yellow-300', 'code_text' => '#FCD34D', 'created_at' => '2023-08-10' ],
            [ 'name' => 'yellow-400', 'code_text' => '#FBBF24', 'created_at' => '2023-08-10' ],
            [ 'name' => 'yellow-500', 'code_text' => '#F59E0B', 'created_at' => '2023-08-10' ],
            [ 'name' => 'green-300', 'code_text' => '#6EE7B7', 'created_at' => '2023-08-10' ],
            [ 'name' => 'green-400', 'code_text' => '#34D399', 'created_at' => '2023-08-10' ],
            [ 'name' => 'green-500', 'code_text' => '#10B981', 'created_at' => '2023-08-10' ],
            [ 'name' => 'green-600', 'code_text' => '#059669', 'created_at' => '2023-08-10' ],
            [ 'name' => 'blue-300', 'code_text' => '#93C5FD', 'created_at' => '2023-08-10' ],
            [ 'name' => 'blue-400', 'code_text' => '#60A5FA', 'created_at' => '2023-08-10' ],
            [ 'name' => 'blue-500', 'code_text' => '#3B82F6', 'created_at' => '2023-08-10' ],
            [ 'name' => 'blue-600', 'code_text' => '#2563EB', 'created_at' => '2023-08-10' ],
            [ 'name' => 'indigo-300', 'code_text' => '#A5B4FC', 'created_at' => '2023-08-10' ],
            [ 'name' => 'indigo-400', 'code_text' => '#818CF8', 'created_at' => '2023-08-10' ],
            [ 'name' => 'indigo-500', 'code_text' => '#6366F1', 'created_at' => '2023-08-10' ],
            [ 'name' => 'indigo-600', 'code_text' => '#4F46E5', 'created_at' => '2023-08-10' ],
            [ 'name' => 'pink-300', 'code_text' => '#F9A8D4', 'created_at' => '2023-08-10' ],
            [ 'name' => 'pink-400', 'code_text' => '#F472B6', 'created_at' => '2023-08-10' ],
            [ 'name' => 'pink-500', 'code_text' => '#EC4899', 'created_at' => '2023-08-10' ],
            [ 'name' => 'pink-200', 'code_text' => '#FBCFE8', 'created_at' => '2023-08-10' ],
            
            ]);
    }
}
