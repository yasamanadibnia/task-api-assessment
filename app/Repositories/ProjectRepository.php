<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Contract\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectRepository
 *
 * Handles data persistence for Project model.
 */
class ProjectRepository implements BaseRepository
{
    /**
     * ProjectRepository constructor.
     */
    public function __construct(
        protected Project $model
    ) {}

    /**
     * Retrieve all projects for authenticated user.
     */
    public function all(): Collection
    {
        return $this->model
            ->where('user_id', auth()->id())
            ->get();
    }

    /**
     * Find a project by id for authenticated user.
     */
    public function find(int $id): ?Model
    {
        return $this->model
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
    }

    /**
     * Create a new project.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing project.
     */
    public function update(int $id, array $data): Model
    {
        $project = $this->model
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $project->update($data);

        return $project;
    }

    /**
     * Delete a project.
     */
    public function delete(int $id): bool
    {
        return (bool) $this->model
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();
    }
}
