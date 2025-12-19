<?php

namespace App\Repositories\Contract;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface BaseRepository
 *
 * Defines basic CRUD operations for repositories.
 */
interface BaseRepository
{
    /**
     * Find resource.
     */
    public function find(int $id): ?Model;

    /**
     * Find all resources.
     */
    public function all(): Collection|LengthAwarePaginator;

    /**
     * Create new resource.
     */
    public function create(array $data): Model;

    /**
     * Update existing resource.
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete existing resource.
     */
    public function delete(int $id): bool;
}
