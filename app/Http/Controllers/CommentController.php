<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Class CommentController
 *
 * Handles HTTP requests related to Comments.
 */
class CommentController extends Controller
{
    use ApiResponse;

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

        $data = $comments->map(
            fn ($comment) => (new CommentResource($comment))->toArray(request())
        );

        return $this->respondSuccess(
            message: __('fetched_successfully'),
            data: $data
        );
    }

    /**
     * Store a newly created comment.
     */
    public function store(CommentRequest $request): JsonResponse
    {
        $comment = $this->commentService->create($request->validated());

        return $this->respondSuccess(
            message: __('created_successfully'),
            data: (new CommentResource($comment))->toArray(request())
        );
    }

    /**
     * Update the specified comment.
     */
    public function update(CommentRequest $request, int $commentId): JsonResponse
    {
        $comment = $this->commentService->update($request->validated(), $commentId);

        return $this->respondSuccess(
            message: __('updated_successfully'),
            data: (new CommentResource($comment))->toArray(request())
        );
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(int $commentId): JsonResponse
    {
        $this->commentService->delete($commentId);

        return $this->respondSuccess(
            message: __('deleted_successfully')
        );
    }
}
