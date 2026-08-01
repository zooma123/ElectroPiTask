<?php

namespace Database\Factories;

use App\Modules\Projects\Models\Project;
use App\Modules\Tasks\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement(['Low', 'Medium', 'High']),
            'status' => fake()->randomElement(['Todo', 'In Progress', 'Done']),
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
