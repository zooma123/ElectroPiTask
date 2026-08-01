<?php

namespace Tests\Feature;

use App\Modules\Auth\Models\User;
use App\Modules\Projects\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    protected function authenticate(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_user_can_create_project()
    {
        $this->authenticate();

        $response = $this->postJson('/api/projects', [
            'name' => 'New Test Project',
            'description' => 'Test Description',
            'status' => 'Active',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'New Test Project');
    }

    public function test_user_can_list_only_their_own_projects()
    {
        $user1 = $this->authenticate();
        Project::factory()->create(['user_id' => $user1->id, 'name' => 'User1 Project']);

        $user2 = User::factory()->create();
        Project::factory()->create(['user_id' => $user2->id, 'name' => 'User2 Project']);

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200);
        
        $projects = $response->json('data');
        $this->assertCount(1, $projects);
        $this->assertEquals('User1 Project', $projects[0]['name']);
    }

    public function test_user_cannot_view_others_project()
    {
        $this->authenticate();

        $otherUser = User::factory()->create();
        $otherProject = Project::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->getJson('/api/projects/' . $otherProject->id);

        $response->assertStatus(404);
    }
}
