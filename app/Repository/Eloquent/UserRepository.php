<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\PaginationInterface;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Exceptions\NotFoundException;
use App\Repository\Presenters\PaginationPresenter;

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

    public function paginate(int $page = 1): PaginationInterface
    {
        return new PaginationPresenter(
            $this->model->paginate()
        );
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(string $email, array $data): object
    {
        $user = $this->find($email);

        $user->update($data);
        $user->refresh();

        return $user;
    }

    /**
     * @throws NotFoundException
     */
    public function delete(string $email): bool
    {
        if (!$user = $this->find($email)) {
            throw new NotFoundException('User not found');
        }

        return $user->delete();
    }

    public function find(string $email): ?object
    {
        return $this->model->where('email', $email)->first();
    }
}
