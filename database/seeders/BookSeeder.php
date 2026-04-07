<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 400; $i++) {
            Book::create([
                'title' => $faker->sentence(3), // Judul unik
                'author' => $faker->name,
                'publisher' => $faker->company,
                'publication_year' => $faker->year,
                'stock' => $faker->numberBetween(1, 100),
            ]);
        }
    }
}
