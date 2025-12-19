<?php

namespace App\Services;

use App\Repositories\ProjectRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectService
 *
 * Handles business logic related to projects.
 */
class ProjectService
{
    /**
     * ProjectService constructor.
     */
    public function __construct(
        protected ProjectRepository $projectRepository
    ) {}

    /**
     * Get all projects for authenticated user.
     */
    public function index(): Collection
    {
        return $this->projectRepository->all();
    }

    /**
     * Create a new project for authenticated user.
     */
    public function create(array $data): Model
    {
        $data['user_id'] = auth()->id();

        return $this->projectRepository->create($data);
    }

    /**
     * Update project.
     */
    public function update(array $data, int $id): Model
    {
        return $this->projectRepository->update($id, $data);
    }

    /**
     * Delete project.
     */
    public function delete(int $id): bool
    {
        return $this->projectRepository->delete($id);
    }
}
