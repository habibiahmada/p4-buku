<?php

namespace Database\Seeders;

use App\Models\Borrow;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BorrowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::where('role', 'siswa')->get();

        for ($i = 0; $i < 300; $i++) {
            $borrowedDate = $faker->dateTimeBetween('-1 year', 'now');
            $dueDate = $faker->dateTimeBetween($borrowedDate, '+30 days');
            $returnedDate = $faker->optional(0.7)->dateTimeBetween($borrowedDate, '+60 days'); // 70% dikembalikan
            $status = $returnedDate ? 'returned' : 'borrowed';

            $charge = 0;
            if ($returnedDate && $returnedDate > $dueDate) {
                $overdueDays = $returnedDate->diff($dueDate)->days;
                $charge = $overdueDays * 10000;
            }

            Borrow::create([
                'user_id' => $users->random()->id,
                'borrowed_date' => $borrowedDate,
                'due_date' => $dueDate,
                'charge' => $charge,
                'returned_date' => $returnedDate,
                'status' => $status,
            ]);
        }
    }
}
