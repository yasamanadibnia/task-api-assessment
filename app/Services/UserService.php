<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class TaskService
{
    public function __construct(protected TaskRepository $taskRepository)
    {
    }

    public function index()
    {
        return $this->taskRepository->all();
    }

    public function create(Request $request)
    {
        $attributes = $request->validated();

        return $this->taskRepository->create($attributes);
    }

    public function read($id)
    {
        return $this->taskRepository->find($id);
    }

    public function update(Request $request, $id)
    {
        $attributes = $request->validated();

        return $this->taskRepository->update($id, $attributes);
    }

    public function delete($id)
    {
        return $this->taskRepository->delete($id);
    }
}
