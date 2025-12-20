<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contract\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class TaskRepository
 *
 * Handles data persistence for Task model.
 */
class TaskRepository implements BaseRepository
{
    /**
     * TaskRepository constructor.
     */
    public function __construct(
        protected Task $model
    ) {}

    /**
     * Retrieve all tasks for authenticated user.
     */
    public function all(): LengthAwarePaginator
    {
        return $this->model->with(['user','comments'])->filter()->paginate(10);
    }

    /**
     * Find a task by id for authenticated user.
     */
    public function find(int $id): ?Model
    {
        return $this->model
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
    }

    /**
     * Create a new task.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing task.
     */
    public function update(int $id, array $data): Model
    {
        $task = $this->model
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $task->update($data);

        return $task;
    }

    /**
     * Delete a task.
     */
    public function delete(int $id): bool
    {
        return (bool) $this->model
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();
    }
}
