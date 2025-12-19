<?php

namespace Tests\Feature\Controllers;

use App\Models\Project;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    public function test_user_can_view_projects()
    {
        $user = $this->loginUser();

        Project::factory()->count(3)->create(['user_id' => $user]);

        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
    }

    public function test_user_can_create_project()
    {
        $this->loginUser();
        $data = [
            'title' => fake()->title(),
            'description' => fake()->text(),
        ];

        $response = $this->post(route('projects.store'), $data);

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data.title')
                ->has('data.description')
                ->etc()
        );

        $this->assertDatabaseHas('projects', $data);
    }

    public function test_user_can_update_project()
    {
        $user = $this->loginUser();

        $project = Project::factory()->create(['user_id' => $user]);
        $data = [
            'title' => fake()->title(),
            'description' => fake()->text(),
        ];
        $response = $this->put(route('projects.update', $project), $data);

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data.title')
                ->has('data.description')
                ->etc()
        );
        $this->assertDatabaseHas('projects', [
            'title' => $data['title'],
        ]);
    }

    public function test_user_can_delete_project()
    {
        $user = $this->loginUser();

        $project = Project::factory()->create(['user_id' => $user]);

        $response = $this->delete(route('projects.destroy', $project));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }
}
