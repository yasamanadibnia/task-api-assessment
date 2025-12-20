<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Http\JsonResponse;

/**
 * Class TaskController
 *
 * Handles HTTP requests related to Tasks.
 */
class TaskController extends Controller
{
    use ApiResponse;

    /**
     * TaskController constructor.
     */
    public function __construct(
        protected TaskService $taskService,
        protected CacheRepository $cache
    ) {}

    /**
     * Display a listing of user tasks.
     */
    public function index(): JsonResponse
    {
        $userId = auth()->id();

        $tasks = $this->cache->remember(
            "tasks:user:{$userId}",
            120,
            fn () => $this->taskService->index()
        );

        $data = $tasks->map(
            fn ($task) => (new TaskResource($task))->toArray(request())
        );

        return $this->respondSuccess(
            message: __('fetched_successfully'),
            data: $data
        );
    }

    /**
     * Store a newly created task.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->validated());

        return $this->respondSuccess(
            message: __('created_successfully'),
            data: (new TaskResource($task))->toArray(request())
        );
    }

    /**
     * Update the specified task.
     */
    public function update(TaskRequest $request, int $taskId): JsonResponse
    {
        $task = $this->taskService->update($request->validated(), $taskId);

        return $this->respondSuccess(
            message: __('updated_successfully'),
            data: (new TaskResource($task))->toArray(request())
        );
    }

    /**
     * Remove the specified task.
     */
    public function destroy(int $taskId): JsonResponse
    {
        $this->taskService->delete($taskId);

        return $this->respondSuccess(
            message: __('deleted_successfully')
        );
    }
}
