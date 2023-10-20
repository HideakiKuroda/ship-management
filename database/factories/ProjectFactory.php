<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 'created_at'と'start_date'は過去5年から現在までの日付
        $startDate = $this->faker->dateTimeBetween('-1 years');
        // 'end_date'は'start_date'より後の日付
        $endDate = $this->faker->dateTimeBetween($startDate,'+1 years');
        // 'completion'と'date_of_issue'は'start_date'から1週間後までの日付
        $completionDate = $this->faker->optional(0.6)->dateTimeBetween($startDate, '+1 years');
        if ($completionDate) {
            $issueDate = $this->faker->dateTimeBetween((clone $completionDate)->modify('-4 days'), (clone $completionDate)->modify('+4 days'));
        } else {
            $issueDate = null;
        }
        // $issueDate = $this->faker->optional(0.8)->dateTimeBetween((clone $completionDate)
        // ->modify('-4 days'), (clone $completionDate)->modify('+4 days'));  
        $shipTasks = [
            '検査工事',
            '検査工事',
            '検査工事',
            '補償工事',
            '空調機入替工事',
            '事故修繕工事',
            '機関修繕工事',
            '機関修繕工事',
            '機関修繕工事',
            '船体塗装工事',
            '船体塗装工事',
            '船体塗装工事',
            '電気設備工事',
            '船用品備品手配',
            '船用品備品手配',
            '船用品備品手配',
            '船用品備品手配',
            // 他のタスクを追加...
        ];
        $taskName = $this->faker->randomElement($shipTasks);
    
            return [
                'ship_id' => $this->faker->numberBetween(1,31),
                'name' => $taskName,
                'pro_category_id' => $this->faker->numberBetween(1,9),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'completion' => $completionDate,
                'date_of_issue' => $issueDate,
                'created_at' => $startDate,
            ];
    }
}
