<?php

namespace Tests\Feature\Controllers;

use App\Enums\NotificationType;
use App\Models\Task;
use App\Notifications\TaskNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    public function test_task_assigned_notification_is_created()
    {
        $user = $this->loginUser();

        Notification::fake();

        $task = Task::factory()->create(['user_id' => $user->id]);

        $user->notify(
            new TaskNotification(NotificationType::TASK_ASSIGNED, $task->id, 'task_assigned')
        );

        $user->refresh();

        Notification::assertSentTo($user, TaskNotification::class);
    }

    public function test_fetch_unseen_notifications()
    {
        $user = $this->loginUser();

        $user->notify(new TaskNotification(NotificationType::TASK_ASSIGNED, 1, 'task_assigned'));

        $response = $this->getJson(route('notifications.unseen'));

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }
}
