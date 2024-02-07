<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TimeLog;
use App\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeLog>
 */
class TimeLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    protected $model = TimeLog::class;
    public function definition(): array
    {   
        // Get a random project_id from the existing projects
        $projectId = Project::inRandomOrder()->value('id') ?? Project::factory()->create()->id;
        //dd($projectId);
        return [
            'start_time' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_time' => $this->faker->dateTimeBetween('now', '+1 month'),
            'description' => $this->faker->sentence,
            'project_id' => $projectId,
        ];
    }
}
