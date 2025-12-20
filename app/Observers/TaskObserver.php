<?php

namespace App\Observers;

use App\Enums\NotificationType;
use App\Models\Task;
use App\Services\NotificationService;

class TaskObserver
{
    /** * Handle the Task "created" event. */
    public function created(Task $task): void
    {
        (new NotificationService)->notifyTask(user: $task->user, type: NotificationType::TASK_ASSIGNED, taskId: $task->id, message: "You have been assigned task: {$task->title}");
    }

    /** * Handle the Task "updated" event. */
    public function updated(Task $task): void
    {
        (new NotificationService)->notifyTask(user: $task->user, type: NotificationType::TASK_UPDATED, taskId: $task->id, message: "Your task have been updated: {$task->title}");
    }
}
