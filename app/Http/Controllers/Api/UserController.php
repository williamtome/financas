<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
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
        $response = $this->repository->paginate();

        return UserResource::collection(collect($response->items()))
            ->additional([
                'meta' => [
                    'total' => $response->total(),
                    'current_page' => $response->currentPage(),
                    'first_page' => $response->firstPage(),
                    'last_page' => $response->lastPage(),
                    'per_page' => $response->perPage(),
                ]
            ]);
    }

    public function store(Request $request): object
    {
        $user = $this->repository->create($request->all());

        return new UserResource($user);
    }
}
