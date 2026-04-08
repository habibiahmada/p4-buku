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

        $bookTitles = [
            'Laskar Pelangi',
            'Ayah',
            'Bumi Manusia',
            'Anak Semua Bangsa',
            'Jejak Langkah',
            'Sang Pemimpi',
            'Negeri Senja',
            'Catatan Juang',
            'Perburuan',
            'Krakatau',
            'Rembulan Tenggelam di Wajahmu',
            'Ketika Cinta Bertasbih',
            'Harimau! Harimau!',
            'Belenggu',
            'Pax Romana',
            'Sebuah Seni untuk Bersikap Bodo Amat',
            'Filosofi Teras',
            'Atomic Habits',
            'Sapiens',
            'Pilar-Pilar Kebijaksanaan',
            'Laut Bercerita',
            'Cerita Cinta di Kota Tua',
            'Gadis Kretek',
            'October Light',
            'Cinta dalam Segelas Teh',
            'Manjali dan Citra',
            'Si Bulan dan Si Bintang',
            'Guru Amoeba',
            'Musim Semi Untuk Indonesia',
            'Ekskursi ke Depan',
            'Parahyangan',
            'Malam Terakhir Kesuksesan',
            'Pohon Cemara',
            'Garis Waktu',
            'Bulan Surya',
            'Dialog Para Alim',
            'Pengembara Malam',
            'Menunggu Godot',
            'Sang Fakir',
            'Pangeran Salju',
            'Tarian Terakhir',
            'Esensi Manusia',
            'Melintasi Batas Negeri',
            'Cahaya di Terang',
            'Pasir Dihembus Angin',
            'Revolusi Membaca',
            'Jejak Masa Lalu',
            'Harmoni Jiwa',
            'Taman Harapan',
            'Cahaya Mentari Pagi',
        ];

        for ($i = 0; $i < 400; $i++) {
            $titleIndex = $i % count($bookTitles);
            $title = $bookTitles[$titleIndex];
            if ($i > 0) {
                $title .= ' (' . ($i / count($bookTitles) + 1) . ')';
            }

            Book::create([
                'title' => $title,
                'author' => $faker->name,
                'publisher' => $faker->company,
                'publication_year' => $faker->year,
                'stock' => $faker->numberBetween(1, 100),
            ]);
        }
    }
}
