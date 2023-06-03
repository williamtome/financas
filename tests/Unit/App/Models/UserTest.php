<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPUnit\Framework\TestCase;

class UserTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new User();
    }

    protected function traits(): array
    {
        return [
            HasUuids::class,
            HasApiTokens::class,
            HasFactory::class,
            Notifiable::class,
        ];
    }

    protected function fillable(): array
    {
        return [
            'name',
            'email',
            'password',
        ];
    }

    public function test_incrementing_is_false(): void
    {
        $this->assertFalse($this->model()->incrementing);
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'email_verified_at' => 'datetime',
        ];
    }

    protected function hidden(): array
    {
        return [
            'password',
            'remember_token',
        ];
    }
}
