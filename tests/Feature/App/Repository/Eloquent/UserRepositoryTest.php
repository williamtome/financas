<?php

namespace Tests\Feature\App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Exceptions\NotFoundException;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new UserRepository(new User());

        parent::setUp();
    }

    public function test_implements_interface(): void
    {
        $this->assertInstanceOf(
            UserRepositoryInterface::class,
            $this->repository
        );
    }
    public function test_find_all_empty(): void
    {
        $response = $this->repository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(0, $response);
    }

    public function test_find_all(): void
    {
        User::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertCount(10, $response);
    }

    public function test_create(): void
    {
        $data = [
            'name' => 'William Tomé',
            'email' => 'william@teste.com',
            'password' => bcrypt('12345678'),
        ];

        $response = $this->repository->create($data);

        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'email' => 'william@teste.com',
        ]);
    }

    public function test_throw_exception(): void
    {
        $this->expectException(QueryException::class);

        $data = [
            'name' => 'William Tomé',
            'password' => bcrypt('12345678'),
        ];

        $this->repository->create($data);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'new name',
        ];

        $response = $this->repository->update($user->email, $data);

        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'name' => 'new name',
        ]);
    }

    public function test_delete(): void
    {
        $user = User::factory()->create();

        $response = $this->repository->delete($user->email);

        $this->assertTrue($response);
        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
        ]);
    }

    public function test_user_not_found(): void
    {
        $this->expectException(NotFoundException::class);
        $this->repository->delete('fake_email');
    }
}
