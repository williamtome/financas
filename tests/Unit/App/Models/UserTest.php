<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function model(): Model
    {
        return new User();
    }

    public function test_traits(): void
    {
        $expectedTraits = [
            HasApiTokens::class,
            HasFactory::class,
            Notifiable::class,
        ];

        $traits = array_keys(class_uses($this->model()));

        $this->assertEquals($expectedTraits, $traits);
    }

    public function test_fillable(): void
    {
        $expectedFillable = [
            'name',
            'email',
            'password',
        ];

        $fillable = $this->model()->getFillable();

        $this->assertEquals($expectedFillable, $fillable);
    }

    public function test_incrementing_is_false(): void
    {
        $this->assertFalse($this->model()->incrementing);
    }

    public function test_has_casts(): void
    {
        $expectedCasts = [
            'id' => 'string',
            'email_verified_at' => 'datetime',
        ];

        $casts = $this->model()->getCasts();

        $this->assertEquals($expectedCasts, $casts);
    }

    public function test_has_hidden(): void
    {
        $expectedHiddenFields = [
            'password',
            'remember_token',
        ];

        $hiddenFields = $this->model()->getHidden();

        $this->assertEquals($expectedHiddenFields, $hiddenFields);
    }
}
