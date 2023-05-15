<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;
    abstract protected function traits(): array;
    abstract protected function fillable(): array;
    abstract protected function casts(): array;
    abstract protected function hidden(): array;

    public function test_traits(): void
    {
        $traits = array_keys(class_uses($this->model()));

        $this->assertEquals($this->traits(), $traits);
    }

    public function test_fillable(): void
    {
        $fillable = $this->model()->getFillable();

        $this->assertEquals($this->fillable(), $fillable);
    }

    public function test_casts(): void
    {
        $casts = $this->model()->getCasts();

        $this->assertEquals($this->casts(), $casts);
    }

    public function test_hidden(): void
    {
        $hiddenFields = $this->model()->getHidden();

        $this->assertEquals($this->hidden(), $hiddenFields);
    }
}
