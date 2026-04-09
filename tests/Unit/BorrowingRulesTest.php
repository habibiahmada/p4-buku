<?php

use App\Support\BorrowingRules;

it('returns zero overdue days when a book is returned on time', function () {
    $rules = new BorrowingRules(10000);

    expect($rules->calculateOverdueDays('2026-04-10', '2026-04-10'))->toBe(0);
    expect($rules->calculateOverdueDays('2026-04-10', '2026-04-09'))->toBe(0);
});

it('calculates overdue days correctly when a book is returned late', function () {
    $rules = new BorrowingRules(10000);

    expect($rules->calculateOverdueDays('2026-04-05', '2026-04-09'))->toBe(4);
});

it('calculates fines based on overdue days and daily fine rate', function () {
    $rules = new BorrowingRules(10000);

    expect($rules->calculateFine('2026-04-05', '2026-04-09'))->toBe(40000);
    expect($rules->calculateFine('2026-04-05', '2026-04-05'))->toBe(0);
});

it('checks stock sufficiency correctly', function () {
    $rules = new BorrowingRules(10000);

    expect($rules->hasEnoughStock(3, 2))->toBeTrue();
    expect($rules->hasEnoughStock(2, 2))->toBeTrue();
    expect($rules->hasEnoughStock(1, 2))->toBeFalse();
});

it('detects overdue state correctly', function () {
    $rules = new BorrowingRules(10000);

    expect($rules->isOverdue('2026-04-05', '2026-04-09'))->toBeTrue();
    expect($rules->isOverdue('2026-04-05', '2026-04-05'))->toBeFalse();
});
