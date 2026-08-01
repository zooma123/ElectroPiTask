<?php

namespace Tests\Feature;

use App\Modules\Auth\Models\User;
use App\Modules\Projects\Models\Project;
use App\Modules\Tasks\Models\Task;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use DatabaseTransactions;

    public function test_dashboard_returns_correct_statistics()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        // Create 2 Projects (1 Active, 1 Completed)
        $activeProject = Project::factory()->create(['user_id' => $user->id, 'status' => 'Active']);
        $completedProject = Project::factory()->create(['user_id' => $user->id, 'status' => 'Completed']);

        // Create Tasks for Active Project
        Task::factory()->create(['project_id' => $activeProject->id, 'status' => 'Todo', 'due_date' => now()->addDays(2)]);
        Task::factory()->create(['project_id' => $activeProject->id, 'status' => 'In Progress', 'due_date' => now()->subDays(2)]); // Overdue
        
        // Create Tasks for Completed Project
        Task::factory()->create(['project_id' => $completedProject->id, 'status' => 'Done', 'due_date' => now()->addDays(5)]);

        $response = $this->getJson('/api/dashboard');

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'total_projects' => 2,
                         'active_projects' => 1,
                         'total_tasks' => 3,
                         'completed_tasks' => 1,
                         'pending_tasks' => 2,
                         'overdue_tasks' => 1,
                     ]
                 ]);
    }
}
