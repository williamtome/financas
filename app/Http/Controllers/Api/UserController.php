<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Eloquent\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    private UserRepository $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(): AnonymousResourceCollection
    {
        $users = $this->repository->paginate();

        return UserResource::collection($users);
    }
}
