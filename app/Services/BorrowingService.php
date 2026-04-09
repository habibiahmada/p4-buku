<?php

namespace App\Services;

use App\Exceptions\BorrowingBusinessException;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowDetail;
use App\Models\User;
use App\Support\BorrowingRules;
use Illuminate\Support\Facades\DB;

class BorrowingService
{
    public function __construct(private readonly BorrowingRules $rules)
    {
    }

    public function createForUser(User $user, array $validated): Borrow
    {
        return DB::transaction(function () use ($user, $validated) {
            foreach ($validated['books'] as $bookData) {
                $book = Book::query()->lockForUpdate()->findOrFail($bookData['id']);

                if (! $this->rules->hasEnoughStock($book->stock, $bookData['qty'])) {
                    throw new BorrowingBusinessException(
                        "Stok buku '{$book->title}' tidak mencukupi. Stok tersedia: {$book->stock}"
                    );
                }
            }

            $borrow = Borrow::create([
                'user_id' => $user->id,
                'borrowed_date' => $validated['borrowed_date'],
                'due_date' => $validated['due_date'],
                'charge' => 0,
                'status' => 'borrowed',
            ]);

            foreach ($validated['books'] as $bookData) {
                BorrowDetail::create([
                    'borrowing_id' => $borrow->id,
                    'book_id' => $bookData['id'],
                    'qty' => $bookData['qty'],
                ]);

                Book::query()
                    ->lockForUpdate()
                    ->findOrFail($bookData['id'])
                    ->decrement('stock', (int) $bookData['qty']);
            }

            return $borrow->fresh(['user', 'borrowDetails.book']);
        });
    }

    public function returnForUser(User $user, int|string $borrowingId, string $returnedDate): Borrow
    {
        return DB::transaction(function () use ($user, $borrowingId, $returnedDate) {
            $borrow = Borrow::with('borrowDetails')
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->findOrFail($borrowingId);

            if ($borrow->status === 'returned') {
                throw new BorrowingBusinessException('Buku ini sudah dikembalikan sebelumnya');
            }

            $charge = $this->rules->calculateFine($borrow->due_date, $returnedDate);

            $borrow->update([
                'returned_date' => $returnedDate,
                'charge' => $charge,
                'status' => 'returned',
            ]);

            foreach ($borrow->borrowDetails as $detail) {
                Book::query()
                    ->lockForUpdate()
                    ->findOrFail($detail->book_id)
                    ->increment('stock', (int) $detail->qty);
            }

            return $borrow->fresh(['user', 'borrowDetails.book']);
        });
    }
}
