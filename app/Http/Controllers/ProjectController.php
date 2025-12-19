<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

/**
 * Class ProjectController
 *
 * Handles HTTP requests related to Projects.
 */
class ProjectController extends Controller
{
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

        return Response::success(
            message: __('fetched_successfully'),
            data: ProjectResource::collection($projects)
        );
    }

    /**
     * Store a newly created project.
     */
    public function store(ProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->create($request->validated());

        return Response::success(
            message: __('created_successfully'),
            data: ProjectResource::make($project)
        );
    }

    /**
     * Update the specified project.
     */
    public function update(ProjectRequest $request, int $id): JsonResponse
    {
        $project = $this->projectService->update($request->validated(), $id);

        return Response::success(
            message: __('updated_successfully'),
            data: ProjectResource::make($project)
        );
    }

    /**
     * Remove the specified project.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->projectService->delete($id);

        return Response::success(__('deleted_successfully'));
    }
}
