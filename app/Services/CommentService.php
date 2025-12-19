<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CommentService
 *
 * Handles business logic related to Comments.
 */
class CommentService
{
    /**
     * CommentService constructor.
     */
    public function __construct(
        protected CommentRepository $commentRepository
    ) {}

    /**
     * Get all comments for authenticated user.
     */
    public function index(): Collection
    {
        return $this->commentRepository->all();
    }

    /**
     * Create a new comments for authenticated user.
     */
    public function create(array $data): Model
    {
        $data['user_id'] = auth()->id();

        return $this->commentRepository->create($data);
    }

    /**
     * Update comment.
     */
    public function update(array $data, int $id): Model
    {
        return $this->commentRepository->update($id, $data);
    }

    /**
     * Delete comment.
     */
    public function delete(int $id): bool
    {
        return $this->commentRepository->delete($id);
    }
}
