<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private User $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function findAll(): array
    {
        return $this->model->get()->toArray();
    }
}
