<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class TaskService
 *
 * Handles business logic related to Tasks.
 */
class TaskService
{
    /**
     * TaskService constructor.
     */
    public function __construct(
        protected TaskRepository $taskRepository,
        protected NotificationService $notificationService
    ) {}

    /**
     * Get all tasks for authenticated user.
     */
    public function index(): LengthAwarePaginator
    {
        return $this->taskRepository->all();
    }

    /**
     * Create a new tasks for authenticated user.
     */
    public function create(array $data): Model
    {
        $data['user_id'] = auth()->id();

        $task = $this->taskRepository->create($data);

        $this->notificationService->notifyTask(
            user: $task->user,
            type: NotificationType::TASK_ASSIGNED,
            taskId: $task->id,
            message: "You have been assigned task: {$task->title}"
        );

        return $task;
    }

    /**
     * Update task.
     */
    public function update(array $data, int $id): Model
    {
        $task = $this->taskRepository->update($id, $data);

        $this->notificationService->notifyTask(
            user: $task->user,
            type: NotificationType::TASK_ASSIGNED,
            taskId: $task->id,
            message: "You have been assigned task: {$task->title}"
        );

        return $task;
    }

    /**
     * Delete task.
     */
    public function delete(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }
}
