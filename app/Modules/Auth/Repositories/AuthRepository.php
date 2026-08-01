<?php

namespace App\Modules\Auth\Repositories;

use App\Modules\Auth\Models\User;

class AuthRepository
{
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }
}
