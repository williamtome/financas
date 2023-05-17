<?php

namespace App\Repository\Presenters;

use App\Repository\Contracts\PaginationInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationPresenter implements PaginationInterface
{
    public function __construct(
        protected LengthAwarePaginator $paginator,
    ) {}

    public function items(): array
    {
        return $this->paginator->items();
    }

    public function total(): int
    {
        return $this->paginator->total() ?? 0;
    }

    public function currentPage(): int
    {
        return $this->paginator->currentPage() ?? 0;
    }

    public function perPage(): int
    {
        return $this->paginator->perPage() ?? 0;
    }

    public function firstPage(): int
    {
        return $this->total() > 0 ? $this->paginator->firstPage() : 1;
    }

    public function lastPage(): int
    {
        return $this->paginator->lastPage() ?? 0;
    }
}