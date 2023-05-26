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
        $response->assertJsonStructure($this->structure());
    }

    /**
     * @dataProvider dataProviderCreateUser
     */
    public function test_create(
        array $payload,
        int $statusCode,
        array $structure
    ): void {
        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus($statusCode);
        $response->assertJsonStructure($structure);
    }

    /**
     * @dataProvider dataProviderUpdate
     */
    public function test_update(
        array $payload,
        int $statusCode
    ): void {
        $user = User::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$user->email}", $payload);

        $response->assertStatus($statusCode);
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
        $response->assertJsonStructure($this->structure());
    }

    public function test_find(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$user->email}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
            ]
        ]);
    }

    public function test_not_found(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("{$this->endpoint}/fake_email");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
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

    public static function dataProviderUpdate(): array
    {
        return [
            'update ok' => [
                'payload' => [
                    'name' => 'Update name',
                    'password' => 'new password',
                ],
                'statusCode' => Response::HTTP_OK,
            ],
            'update without password' => [
                'payload' => [
                    'name' => 'New Name',
                ],
                'statusCode' => Response::HTTP_OK,
            ],
            'update without name' => [
                'payload' => [
                    'password' => 'new password',
                ],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            'update empty payload' => [
                'payload' => [],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
        ];
    }

    public static function dataProviderCreateUser(): array
    {
        return [
            'test created user' => [
                'payload' => [
                    'name' => 'William',
                    'email' => 'william@teste.com',
                    'password' => '123abc45',
                ],
                'statusCode' => Response::HTTP_CREATED,
                'structure' => [
                    'data' => [
                        'id',
                        'name',
                        'email',
                    ]
                ],
            ],
            'test validation' => [
                'payload' => [],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'structure' => [
                    'message',
                    'errors' => [
                        'name',
                        'email',
                        'password',
                    ],
                ],
            ],
        ];
    }

    protected function structure(): array
    {
        return [
            'meta' => [
                'total',
                'current_page',
                'first_page',
                'last_page',
                'per_page',
            ],
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                ]
            ]
        ];
    }
}
