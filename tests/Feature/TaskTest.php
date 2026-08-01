<?php

namespace Tests\Feature;

use App\Modules\Auth\Models\User;
use App\Modules\Projects\Models\Project;
use App\Modules\Tasks\Models\Task;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseTransactions;

    protected function authenticate(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_user_can_create_task_in_their_project()
    {
        $user = $this->authenticate();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson('/api/tasks', [
            'project_id' => $project->id,
            'title' => 'New Task',
            'description' => 'Description',
            'status' => 'Todo',
            'priority' => 'High',
            'due_date' => now()->addDays(5)->toDateString(),
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.title', 'New Task');
    }

    public function test_user_cannot_create_task_in_others_project()
    {
        $this->authenticate();
        
        $otherUser = User::factory()->create();
        $otherProject = Project::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->postJson('/api/tasks', [
            'project_id' => $otherProject->id,
            'title' => 'New Task',
            'status' => 'Todo',
            'priority' => 'High',
        ]);

        $response->assertStatus(404) // service returns 404 when project doesn't belong to user
                 ->assertJsonPath('error', 'Project not found or unauthorized');
    }

    public function test_user_can_filter_tasks_by_status()
    {
        $user = $this->authenticate();
        $project = Project::factory()->create(['user_id' => $user->id]);
        
        Task::factory()->create(['project_id' => $project->id, 'status' => 'Todo']);
        Task::factory()->create(['project_id' => $project->id, 'status' => 'Done']);

        $response = $this->getJson('/api/tasks?status=Done');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Done', $response->json('data.0.status'));
    }
}
