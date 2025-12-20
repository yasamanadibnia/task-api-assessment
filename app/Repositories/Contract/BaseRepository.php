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
    public function find(int $modeId): ?Model;

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
    public function update(int $modeId, array $data): Model;

    /**
     * Delete existing resource.
     */
    public function delete(int $modeId): bool;
}
