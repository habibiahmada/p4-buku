<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class BorrowingRules
{
    public function __construct(private readonly ?int $dailyFine = null)
    {
    }

    public function dailyFine(): int
    {
        return $this->dailyFine ?? (int) config('borrowing.daily_fine', 10000);
    }

    public function calculateOverdueDays(mixed $dueDate, mixed $returnedDate): int
    {
        $due = Carbon::parse($dueDate)->startOfDay();
        $returned = Carbon::parse($returnedDate)->startOfDay();

        return max($due->diffInDays($returned, false), 0);
    }

    public function calculateFine(mixed $dueDate, mixed $returnedDate): int
    {
        return $this->calculateOverdueDays($dueDate, $returnedDate) * $this->dailyFine();
    }

    public function hasEnoughStock(int|string $stock, int|string $requestedQty): bool
    {
        return (int) $stock >= (int) $requestedQty;
    }

    public function isOverdue(mixed $dueDate, mixed $returnedDate): bool
    {
        return $this->calculateOverdueDays($dueDate, $returnedDate) > 0;
    }
}
