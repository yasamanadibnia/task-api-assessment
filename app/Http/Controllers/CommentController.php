<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

/**
 * Class CommentController
 *
 * Handles HTTP requests related to Comments.
 */
class CommentController extends Controller
{
    /**
     * CommentController constructor.
     */
    public function __construct(
        protected CommentService $commentService
    ) {}

    /**
     * Display a listing of user comments.
     */
    public function index(): JsonResponse
    {
        $comments = $this->commentService->index();

        return Response::success(
            message: __('fetched_successfully'),
            data: CommentResource::collection($comments)
        );
    }

    /**
     * Store a newly created comment.
     */
    public function store(CommentRequest $request): JsonResponse
    {
        $comment = $this->commentService->create($request->validated());

        return Response::success(
            message: __('created_successfully'),
            data: CommentResource::make($comment)
        );
    }

    /**
     * Update the specified comment.
     */
    public function update(CommentRequest $request, int $id): JsonResponse
    {
        $comment = $this->commentService->update($request->validated(), $id);

        return Response::success(
            message: __('updated_successfully'),
            data: CommentResource::make($comment)
        );
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->commentService->delete($id);

        return Response::success(__('deleted_successfully'));
    }
}
