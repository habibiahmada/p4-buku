<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BorrowDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $borrows = Borrow::all();
        $books = Book::all();

        foreach ($borrows as $borrow) {
            $numDetails = $faker->numberBetween(1, 5);
            $selectedBooks = $books->random(min($numDetails, $books->count()));

            foreach ($selectedBooks as $book) {
                BorrowDetail::create([
                    'borrowing_id' => $borrow->id,
                    'book_id' => $book->id,
                    'qty' => $faker->numberBetween(1, 3),
                ]);
            }
        }
    }
}
