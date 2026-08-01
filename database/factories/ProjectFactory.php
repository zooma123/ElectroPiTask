<?php

namespace Database\Factories;

use App\Modules\Auth\Models\User;
use App\Modules\Projects\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->company(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['Active', 'Completed', 'Archived']),
        ];
    }
}
