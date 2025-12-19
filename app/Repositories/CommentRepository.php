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
     *
     * @param  int  $id
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
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
    public function update(int $id, array $data): Model
    {
        $comment = $this->model
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->where('task_id', request('task_id'))
            ->firstOrFail();

        $comment->update($data);

        return $comment;
    }

    /**
     * Delete existing resource.
     *
     * @param  int  $id
     */
    public function delete($id): bool
    {
        return $this->model->find($id)->delete();
    }
}
