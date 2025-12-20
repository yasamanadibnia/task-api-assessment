<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;

class AuthRepository
{
    public function __construct(
        private readonly User $model,
        protected Hasher $hasher
    ) {}

    public function create(array $data): User
    {
        return $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->hasher->make($data['password']),
        ]);
    }

    public function findByEmail(string $email): User
    {
        return $this->model->where('email', $email)->firstOrFail();
    }
}
