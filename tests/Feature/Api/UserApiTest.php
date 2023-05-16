<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    protected string $endpoint = '/api/users';

    public function test_get_all_empty(): void
    {
        $response = $this->get($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_get_all_users(): void
    {
        User::factory()->count(10)->create();

        $response = $this->getJson($this->endpoint);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(10, 'data');
    }
}
