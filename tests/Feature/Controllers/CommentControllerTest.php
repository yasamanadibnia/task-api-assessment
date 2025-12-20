<?php

namespace Tests\Feature\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    public function test_user_can_create_comment()
    {
        $user = $this->loginUser();
        $task = Task::factory()->create();

        $data = [
            'task_id' => $task->id,
            'body' => fake()->paragraph(),
        ];

        $response = $this->postJson(route('comments.store'), $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['body' => $data['body']]);

        $this->assertDatabaseHas('comments', ['body' => $data['body'], 'user_id' => $user->id]);
    }

    public function test_user_can_list_comments_for_task()
    {
        $user = $this->loginUser();
        $task = Task::factory()->create();
        $comment = Comment::factory()->create(['task_id' => $task->id]);

        $response = $this->getJson(route('comments.index', ['task_id' => $comment->task_id]));

        $response->assertJson([
            'status' => 200,
        ])->assertJsonStructure(['data' => [['id', 'task_id', 'user_id', 'body']]]);
    }

    public function test_user_can_update_own_comment()
    {
        $user = $this->loginUser();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $data = ['body' => fake()->paragraph(), 'task_id' => $comment->task_id];
        $response = $this->putJson(route('comments.update', $comment->id), $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['body' => $data['body']]);
    }

    public function test_user_can_delete_own_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('comments.destroy', $comment->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
