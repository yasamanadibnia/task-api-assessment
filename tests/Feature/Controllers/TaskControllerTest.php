<?php

namespace Tests\Feature\Controllers;

use App\Enums\TaskStatus;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Notifications\TaskNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    public function test_user_can_list_tasks_with_pagination()
    {
        $user = $this->loginUser();

        $tasks = Task::factory()->count(15)->create([
            'user_id' => $user->id,
        ]);

        Comment::factory()->count(10)->create([
            'task_id' => $tasks->first()->id
        ]);
        $response = $this->getJson('/api/tasks');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_user_can_filter_tasks_by_status()
    {
        $user = $this->loginUser();

        Task::factory()->create([
            'user_id' => $user->id,
            'status' => TaskStatus::TODO->value,
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'status' => TaskStatus::DONE->value,
        ]);

        $response = $this->getJson('/api/tasks?status='.TaskStatus::TODO->value);

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->where('data.0.status', TaskStatus::TODO->value)
                ->etc()
        );
    }

    /* ---------------------------------------------------
     | STORE
     |---------------------------------------------------*/

    public function test_user_can_create_task()
    {
        Notification::fake();

        $user = $this->loginUser();

        $payload = [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => TaskStatus::TODO->value,
            'due_date' => now()->addDay()->toDateString(),
            'project_id' => Project::factory()->create()->id,
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => $payload['title']]);

        $this->assertDatabaseHas('tasks', [
            'title' => $payload['title'],
        ]);

        Notification::assertSentTo($user, TaskNotification::class);

    }

    public function test_validation_fails_on_invalid_status()
    {
        $user = $this->loginUser();

        $response = $this->postJson('/api/tasks', [
            'title' => 'Invalid Task',
            'status' => 'invalid-status',
        ]);

        $response->assertStatus(422);
    }

    /* ---------------------------------------------------
     | UPDATE
     |---------------------------------------------------*/

    public function test_user_can_update_own_task()
    {
        Notification::fake();

        $user = $this->loginUser();

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'status' => TaskStatus::TODO->value,
        ]);

        $data = [
            'title' => fake()->sentence(),
            'status' => TaskStatus::IN_PROGRESS->value,
            'description' => $task->description,
            'due_date' => $task->due_date,
            'project_id' => $task->project_id,
        ];

        $response = $this->putJson(
            "/api/tasks/{$task->id}",
            $data
        );

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => $data['title']]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatus::IN_PROGRESS->value,
        ]);

        Notification::assertSentTo($user, TaskNotification::class);

    }

    /* ---------------------------------------------------
     | DELETE
     |---------------------------------------------------*/

    public function test_user_can_delete_own_task()
    {
        $user = $this->loginUser();

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
