<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Exceptions\NotFoundException;

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

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(string $email, array $data): object
    {
        $user = $this->model->where('email', $email)->first();

        $user->update($data);
        $user->refresh();

        return $user;
    }

    /**
     * @throws NotFoundException
     */
    public function delete(string $email): bool
    {
        $user = $this->model->where('email', $email)->first();

        if (!$user) {
            throw new NotFoundException('User not found');
        }

        return $user->delete();
    }
}
