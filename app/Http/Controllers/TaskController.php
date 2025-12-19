<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

/**
 * Class TaskController
 *
 * Handles HTTP requests related to Tasks.
 */
class TaskController extends Controller
{
    /**
     * TaskController constructor.
     */
    public function __construct(
        protected TaskService $taskService
    )
    {
    }

    /**
     * Display a listing of user tasks.
     */
    public function index(): JsonResponse
    {
        $tasks = Cache::remember('tasks:user:' . auth()->id(), 120, function () {
            return $this->taskService->index();
        });

        return Response::success(
            message: __('fetched_successfully'),
            data: TaskResource::collection($tasks)
        );
    }

    /**
     * Store a newly created task.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->validated());

        return Response::success(
            message: __('created_successfully'),
            data: TaskResource::make($task)
        );
    }

    /**
     * Update the specified task.
     */
    public function update(TaskRequest $request, int $id): JsonResponse
    {
        $task = $this->taskService->update($request->validated(), $id);

        return Response::success(
            message: __('updated_successfully'),
            data: TaskResource::make($task)
        );
    }

    /**
     * Remove the specified task.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->taskService->delete($id);

        return Response::success(__('deleted_successfully'));
    }
}
