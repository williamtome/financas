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

    public function test_get_all_users(): void
    {
        User::factory()->count(40)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertOk();
        $response->assertJsonCount(15, 'data');
        $response->assertJsonFragment(['total' => 40]);
        $response->assertJsonFragment(['current_page' => 1]);
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

    /**
     * @dataProvider dataProviderPagination
     */
    public function test_paginate(
        int $total,
        int $page = 1,
        int $totalPage = 15
    ): void {
        User::factory()->count($total)->create();

        $response = $this->getJson("{$this->endpoint}?page=$page");

        $response->assertOk();
        $response->assertJsonCount($totalPage, 'data');
        $response->assertJsonFragment(['total' => $total]);
        $response->assertJsonFragment(['current_page' => $page]);
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

    public static function dataProviderPagination(): array
    {
        return [
            '40 users page 1' => ['total' => 40, 'page' => 1, 'totalPage' => 15],
            '40 users page 2' => ['total' => 40, 'page' => 2, 'totalPage' => 15],
            '40 users page 3' => ['total' => 40, 'page' => 3, 'totalPage' => 10],
            '20 users page 1' => ['total' => 20, 'page' => 1, 'totalPage' => 15],
            '20 users page 2' => ['total' => 20, 'page' => 2, 'totalPage' => 5],
            'no users' => ['total' => 0, 'page' => 1, 'totalPage' => 0],
        ];
    }
}
