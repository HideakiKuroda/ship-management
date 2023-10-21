<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use Carbon\Carbon;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $project = Project::inRandomOrder()->first();
        $start_date = Carbon::parse($project->start_date);
        $end_date = Carbon::parse($project->end_date)->subDays(rand(0, 7));
        // $end_date = $project->end_date->subDays(rand(0, 7));
        $deadline = $end_date->copy()->addDays(rand(3, 7));
        $completion = $project->end_date !== null 
            ? $deadline->copy()->addDays(rand(0, 5)) 
            : ($this->faker->numberBetween(1, 100) <= 70 ? null : $deadline->copy()->addDays(rand(0, 5)));

        return [
            'project_id' => $project->id,
            'name' => $this->faker->randomElement([
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
                 ]),
            'start_date' => $this->faker->dateTimeBetween($start_date, $start_date->copy()->addDays(7)),
            'end_date' => $end_date,
            'deadline' => $deadline,
            'completion' => $completion,
        ];
    }
}