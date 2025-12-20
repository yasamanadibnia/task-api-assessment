<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Contract\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CommentRepository implements BaseRepository
{
    public function __construct(protected Comment $model) {}

    /**
     * Find all resources.
     */
    public function all(): Collection
    {
        return $this->model->where('task_id', request('task_id'))->get();
    }

    /**
     * Find resource.
     */
    public function find(int $commentId): ?Model
    {
        return $this->model->find($commentId);
    }

    /**
     * Create new resource.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update existing resource.
     */
    public function update(int $commentId, array $data): Model
    {
        $comment = $this->model
            ->where('id', $commentId)
            ->where('user_id', auth()->id())
            ->where('task_id', request('task_id'))
            ->firstOrFail();

        $comment->update($data);

        return $comment;
    }

    /**
     * Delete existing resource.
     */
    public function delete(int $commentId): bool
    {
        return $this->model->find($commentId)->delete();
    }
}
