<?php

use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowDetail;
use App\Models\User;

it('reduces book stock when a siswa creates a borrowing transaction', function () {
    $user = User::factory()->create([
        'role' => 'siswa',
    ]);

    $book = Book::create([
        'title' => 'Laskar Pelangi',
        'author' => 'Andrea Hirata',
        'publisher' => 'Bentang',
        'publication_year' => 2005,
        'stock' => 5,
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('siswa.transactions.store'), [
            'borrowed_date' => '2026-04-08',
            'due_date' => '2026-04-15',
            'books' => [
                [
                    'id' => $book->id,
                    'qty' => 2,
                ],
            ],
        ]);

    $response
        ->assertRedirect(route('siswa.transactions.index'))
        ->assertSessionHas('success');

    expect((int) $book->fresh()->stock)->toBe(3);

    $this->assertDatabaseHas('borrowings', [
        'user_id' => $user->id,
        'status' => 'borrowed',
    ]);

    $this->assertDatabaseHas('borrowings_detail', [
        'book_id' => $book->id,
        'qty' => 2,
    ]);
});

it('restores borrowed book stock when a siswa returns the transaction', function () {
    $user = User::factory()->create([
        'role' => 'siswa',
    ]);

    $book = Book::create([
        'title' => 'Bumi Manusia',
        'author' => 'Pramoedya Ananta Toer',
        'publisher' => 'Lentera',
        'publication_year' => 1980,
        'stock' => 1,
    ]);

    $borrow = Borrow::create([
        'user_id' => $user->id,
        'borrowed_date' => '2026-04-01',
        'due_date' => '2026-04-08',
        'charge' => 0,
        'status' => 'borrowed',
    ]);

    BorrowDetail::create([
        'borrowing_id' => $borrow->id,
        'book_id' => $book->id,
        'qty' => 2,
    ]);

    $book->update([
        'stock' => 1,
    ]);

    $response = $this
        ->actingAs($user)
        ->put(route('siswa.transactions.update', $borrow->id), [
            'borrowing_id' => $borrow->id,
            'returned_date' => '2026-04-08',
            'charge' => 0,
        ]);

    $response
        ->assertRedirect(route('siswa.transactions.index'))
        ->assertSessionHas('success');

    expect((int) $book->fresh()->stock)->toBe(3);

    $this->assertDatabaseHas('borrowings', [
        'id' => $borrow->id,
        'status' => 'returned',
        'returned_date' => '2026-04-08',
    ]);
});
