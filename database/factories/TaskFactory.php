<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         // 'created_at'と'start_date'は過去5年から現在までの日付
         $created_at= $this->faker->dateTimeBetween('-2 years');
         $startDate = $this->faker->dateTimeBetween($created_at,'-30 days');
         // 'end_date'は'start_date'より後の日付
         $endDate = $this->faker->dateTimeBetween($startDate,'+2 years');
         // 'completion'と'date_of_issue'は'start_date'から1週間後までの日付
         $deadline = $this->faker->dateTimeBetween($endDate,'+10 days');
         $periodStart = (clone $endDate)->modify('-10 days');
         $periodEnd = (clone $endDate)->modify('+10 days');
         $completionDate = $this->faker->optional(0.6)->dateTimeBetween($periodStart, $periodEnd);
         $shipTasks = [
            '仕様書作成',
            '追加工事手配',
            '日産電気　見積-発注',
            '進光　見積-発注',
            'サンレイ空調　見積-発注',
            '造船所　見積依頼',
            '三鈴Ｍ　見積-発注',
            '神戸Ｅ　見積-発注',
            '生田＆マリン　見積-発注',
            'サノテック　見積-発注',
            '船用品　見積-発注',
            '機関補修部品　見積-発注',
            'キムラ海陸　見積-発注',
            '船用品備手配',
            '船用品手配',
            '機関補修部品手配',
            '船用品備品手配',
            // 他のタスクを追加...
        ];
        $taskName = $this->faker->randomElement($shipTasks);

        return [
            'Project_id' => $this->faker->numberBetween(1,500),
            'name' => $taskName,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'completion' => $completionDate,
            'deadline' => $deadline,
            'created_at' => $created_at,
        ];
    }
}
