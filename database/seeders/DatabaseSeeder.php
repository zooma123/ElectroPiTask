<?php

namespace Database\Seeders;

use App\Modules\Auth\Models\User;
use App\Modules\Projects\Models\Project;
use App\Modules\Tasks\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(5)->create()->each(function ($user) {
            Project::factory(3)->create(['user_id' => $user->id])->each(function ($project) {
                Task::factory(5)->create(['project_id' => $project->id]);
            });
        });
    }
}
