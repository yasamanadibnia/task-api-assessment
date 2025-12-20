<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Class ProjectController
 *
 * Handles HTTP requests related to Projects.
 */
class ProjectController extends Controller
{
    use ApiResponse;

    /**
     * ProjectController constructor.
     */
    public function __construct(
        protected ProjectService $projectService
    ) {}

    /**
     * Display a listing of user projects.
     */
    public function index(): JsonResponse
    {
        $projects = $this->projectService->index();

        $data = $projects->map(
            fn ($project) => (new ProjectResource($project))->toArray(request())
        );

        return $this->respondSuccess(
            message: __('fetched_successfully'),
            data: $data
        );
    }

    /**
     * Store a newly created project.
     */
    public function store(ProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->create($request->validated());

        return $this->respondSuccess(
            message: __('created_successfully'),
            data: (new ProjectResource($project))->toArray(request())
        );
    }

    /**
     * Update the specified project.
     */
    public function update(ProjectRequest $request, int $projectId): JsonResponse
    {
        $project = $this->projectService->update($request->validated(), $projectId);

        return $this->respondSuccess(
            message: __('updated_successfully'),
            data: (new ProjectResource($project))->toArray(request())
        );
    }

    /**
     * Remove the specified project.
     */
    public function destroy(int $projectId): JsonResponse
    {
        $this->projectService->delete($projectId);

        return $this->respondSuccess(
            message: __('deleted_successfully')
        );
    }
}
