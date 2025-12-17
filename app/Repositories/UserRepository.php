<?php

namespace App\Repositories;

use App\Models\Task;

class UserRepository
{

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }
    public function create($attributes)
    {
        return $this->task->create($attributes);
    }

    public function all()
    {
        return $this->task->all();
    }

    public function find($id)
    {
        return $this->task->find($id);
    }

    public function update($id, array $attributes)
    {
        return $this->task->find($id)->update($attributes);
    }

    public function delete($id)
    {
        return $this->task->find($id)->delete();
    }
}
