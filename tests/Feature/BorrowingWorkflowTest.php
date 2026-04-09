<?php

use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowDetail;
use App\Models\User;

it('creates a borrowing transaction with multiple books and decreases each stock', function () {
    $user = User::factory()->create([
        'role' => 'siswa',
    ]);

    $bookA = Book::create([
        'title' => 'Atomic Habits',
        'author' => 'James Clear',
        'publisher' => 'Penguin',
        'publication_year' => 2018,
        'stock' => 5,
    ]);

    $bookB = Book::create([
        'title' => 'Filosofi Teras',
        'author' => 'Henry Manampiring',
        'publisher' => 'Kompas',
        'publication_year' => 2019,
        'stock' => 4,
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('siswa.transactions.store'), [
            'borrowed_date' => '2026-04-08',
            'due_date' => '2026-04-15',
            'books' => [
                [
                    'id' => $bookA->id,
                    'qty' => 2,
                ],
                [
                    'id' => $bookB->id,
                    'qty' => 1,
                ],
            ],
        ]);

    $response
        ->assertRedirect(route('siswa.transactions.index'))
        ->assertSessionHas('success');

    $borrow = Borrow::firstOrFail();

    expect((int) $bookA->fresh()->stock)->toBe(3);
    expect((int) $bookB->fresh()->stock)->toBe(3);

    $this->assertDatabaseHas('borrowings', [
        'id' => $borrow->id,
        'user_id' => $user->id,
        'status' => 'borrowed',
    ]);

    $this->assertDatabaseHas('borrowings_detail', [
        'borrowing_id' => $borrow->id,
        'book_id' => $bookA->id,
        'qty' => 2,
    ]);

    $this->assertDatabaseHas('borrowings_detail', [
        'borrowing_id' => $borrow->id,
        'book_id' => $bookB->id,
        'qty' => 1,
    ]);
});

it('rejects a borrowing transaction when one of the selected books has insufficient stock', function () {
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

    $response = $this
        ->from(route('siswa.transactions.create'))
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
        ->assertRedirect(route('siswa.transactions.create'))
        ->assertSessionHasErrors('books');

    expect(Borrow::count())->toBe(0);
    expect(BorrowDetail::count())->toBe(0);
    expect((int) $book->fresh()->stock)->toBe(1);
});

it('calculates the return fine on the server and ignores manipulated client charge', function () {
    $user = User::factory()->create([
        'role' => 'siswa',
    ]);

    $book = Book::create([
        'title' => 'Laut Bercerita',
        'author' => 'Leila S. Chudori',
        'publisher' => 'KPG',
        'publication_year' => 2017,
        'stock' => 2,
    ]);

    $borrow = Borrow::create([
        'user_id' => $user->id,
        'borrowed_date' => '2026-04-01',
        'due_date' => '2026-04-05',
        'charge' => 0,
        'status' => 'borrowed',
    ]);

    BorrowDetail::create([
        'borrowing_id' => $borrow->id,
        'book_id' => $book->id,
        'qty' => 1,
    ]);

    $book->update([
        'stock' => 1,
    ]);

    $response = $this
        ->actingAs($user)
        ->put(route('siswa.transactions.update', $borrow->id), [
            'borrowing_id' => $borrow->id,
            'returned_date' => '2026-04-09',
            'charge' => 0,
        ]);

    $response
        ->assertRedirect(route('siswa.transactions.index'))
        ->assertSessionHas('success', fn (string $message) => str_contains($message, 'Rp40.000'));

    $this->assertDatabaseHas('borrowings', [
        'id' => $borrow->id,
        'status' => 'returned',
        'returned_date' => '2026-04-09',
        'charge' => 40000,
    ]);

    expect((int) $book->fresh()->stock)->toBe(2);
});

it('prevents a user from returning another users borrowing transaction', function () {
    $owner = User::factory()->create([
        'role' => 'siswa',
    ]);

    $otherUser = User::factory()->create([
        'role' => 'siswa',
    ]);

    $book = Book::create([
        'title' => 'Sapiens',
        'author' => 'Yuval Noah Harari',
        'publisher' => 'Harvill Secker',
        'publication_year' => 2011,
        'stock' => 1,
    ]);

    $borrow = Borrow::create([
        'user_id' => $owner->id,
        'borrowed_date' => '2026-04-01',
        'due_date' => '2026-04-05',
        'charge' => 0,
        'status' => 'borrowed',
    ]);

    BorrowDetail::create([
        'borrowing_id' => $borrow->id,
        'book_id' => $book->id,
        'qty' => 1,
    ]);

    $response = $this
        ->actingAs($otherUser)
        ->put(route('siswa.transactions.update', $borrow->id), [
            'borrowing_id' => $borrow->id,
            'returned_date' => '2026-04-09',
        ]);

    expect($response->status())->toBe(404);

    $this->assertDatabaseHas('borrowings', [
        'id' => $borrow->id,
        'status' => 'borrowed',
        'charge' => 0,
        'returned_date' => null,
    ]);
});

it('does not allow a borrowing that is already returned to be processed again', function () {
    $user = User::factory()->create([
        'role' => 'siswa',
    ]);

    $book = Book::create([
        'title' => 'Ayah',
        'author' => 'Andrea Hirata',
        'publisher' => 'Bentang',
        'publication_year' => 2015,
        'stock' => 3,
    ]);

    $borrow = Borrow::create([
        'user_id' => $user->id,
        'borrowed_date' => '2026-04-01',
        'due_date' => '2026-04-05',
        'charge' => 0,
        'returned_date' => '2026-04-05',
        'status' => 'returned',
    ]);

    BorrowDetail::create([
        'borrowing_id' => $borrow->id,
        'book_id' => $book->id,
        'qty' => 1,
    ]);

    $response = $this
        ->from(route('siswa.transactions.return'))
        ->actingAs($user)
        ->put(route('siswa.transactions.update', $borrow->id), [
            'borrowing_id' => $borrow->id,
            'returned_date' => '2026-04-10',
        ]);

    $response
        ->assertRedirect(route('siswa.transactions.return'))
        ->assertSessionHasErrors('borrowing_id');

    $this->assertDatabaseHas('borrowings', [
        'id' => $borrow->id,
        'status' => 'returned',
        'returned_date' => '2026-04-05',
    ]);

    expect((int) $book->fresh()->stock)->toBe(3);
});
