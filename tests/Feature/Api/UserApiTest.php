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
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
        $this->assertEquals(0, $response['meta']['total']);
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'first_page',
                'last_page',
                'per_page',
            ]
        ]);
    }

    public function test_get_all_users(): void
    {
        User::factory()->count(40)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $this->assertEquals(40, $response['meta']['total']);
        $this->assertEquals(1, $response['meta']['current_page']);
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'first_page',
                'last_page',
                'per_page',
            ]
        ]);
    }

    public function test_paginate(): void
    {
        User::factory()->count(40)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $this->assertEquals(40, $response['meta']['total']);
        $this->assertEquals(1, $response['meta']['current_page']);
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'first_page',
                'last_page',
                'per_page',
            ]
        ]);
    }

    public function test_page_two(): void
    {
        User::factory()->count(20)->create();

        $response = $this->getJson("{$this->endpoint}?page=2");

        $response->assertOk();
        $response->assertJsonCount(5, 'data');
        $response->assertJsonFragment(['total' => 20]);
        $response->assertJsonFragment(['current_page' => 2]);
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'first_page',
                'last_page',
                'per_page',
            ]
        ]);
    }
}
