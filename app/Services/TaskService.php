<?php

namespace App\Services;

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

        return $this->taskRepository->create($data);
    }

    /**
     * Update task.
     */
    public function update(array $data, int $id): Model
    {
        return $this->taskRepository->update($id, $data);
    }

    /**
     * Delete task.
     */
    public function delete(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }
}
