# Aksara Pustaka

Aksara Pustaka adalah aplikasi perpustakaan berbasis web untuk mengelola data buku, anggota, peminjaman, pengembalian, stok, dan riwayat transaksi dalam satu sistem. Proyek ini dibuat dengan Laravel 13 dan disusun dengan mengacu pada dokumen analisis proyek yang sudah tersedia di repo.

<img width="1900" height="944" alt="image" src="https://github.com/user-attachments/assets/d734c331-e809-41d9-97d3-43cc7cfa9db0" />

## Referensi Analisis

- [laporanhasilanalisa.md](laporanhasilanalisa.md)
- [Laporan hasil analisa aplikasi peminjaman buku.pdf](Laporan%20hasil%20analisa%20aplikasi%20peminjaman%20buku.pdf)

## Fitur Utama

- Landing page aplikasi perpustakaan digital.
- Registrasi dan login pengguna.
- Redirect dashboard berdasarkan role `admin` dan `siswa`.
- Dashboard admin untuk ringkasan anggota, buku, stok, dan transaksi.
- Manajemen buku: tambah, ubah, hapus, cari, dan filter penerbit.
- Manajemen anggota: tambah, ubah, hapus, cari, dan filter role.
- Peminjaman multi-buku dalam satu transaksi.
- Pengembalian buku dengan perhitungan denda keterlambatan.
- Monitoring transaksi dan filter status/tanggal.
- Riwayat peminjaman pribadi untuk siswa.
- Pengelolaan profil pengguna.

## Peran Pengguna

| Role | Hak akses utama |
| --- | --- |
| `admin` | Melihat dashboard admin, mengelola buku, mengelola anggota, dan memantau seluruh transaksi |
| `siswa` | Registrasi akun, login, membuat peminjaman, mengembalikan buku, melihat riwayat transaksi, dan mengubah profil |

## Stack Teknologi

- Backend: PHP 8.3, Laravel 13
- Frontend: Blade, Tailwind CSS, Alpine.js
- Database: MySQL
- UI icons: `blade-ui-kit/blade-heroicons`
- Testing: Pest + Laravel testing utilities

## Modul Sistem

- `Autentikasi`
  Login, registrasi, logout, verifikasi email, reset password, dan proteksi sesi.
- `Dashboard Admin`
  Ringkasan anggota, buku, stok aktif, grafik peminjaman 6 bulan terakhir, dan peminjaman terbaru.
- `Manajemen Buku`
  CRUD buku beserta pencarian judul/penulis dan filter penerbit.
- `Manajemen Anggota`
  CRUD pengguna admin/siswa, filter role, dan pencarian nama/email.
- `Transaksi Siswa`
  Pembuatan peminjaman multi-buku, update stok otomatis, pengembalian, dan denda.
- `Monitoring Transaksi Admin`
  Daftar seluruh peminjaman, status aktif/selesai/terlambat, filter nama dan tanggal.

## Struktur Data Inti

| Tabel | Fungsi |
| --- | --- |
| `users` | Menyimpan data akun dan role pengguna |
| `books` | Menyimpan katalog buku dan stok |
| `borrowings` | Menyimpan header transaksi peminjaman/pengembalian |
| `borrowings_detail` | Menyimpan detail buku per transaksi |

Catatan implementasi:

- Role yang digunakan di database adalah `admin` dan `siswa`.
- Status transaksi yang dipakai di kode adalah `borrowed` dan `returned`.
- Kolom tanggal transaksi yang dipakai implementasi adalah `borrowed_date`, `due_date`, dan `returned_date`.

## Instalasi Lokal

### Opsi cepat

```powershell
composer run setup
```

Perintah di atas akan:

- menginstal dependency PHP,
- menyalin `.env` bila belum ada,
- membuat application key,
- menjalankan migrasi,
- menginstal dependency frontend,
- membangun asset produksi.

### Opsi manual

```powershell
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
```

Lalu sesuaikan konfigurasi database di `.env`, kemudian jalankan:

```powershell
php artisan migrate --seed
```

## Menjalankan Aplikasi

### Mode pengembangan terpadu

```powershell
composer run dev
```

### Menjalankan terpisah

```powershell
php artisan serve
npm run dev
```

## Akun Seeder Default

Seeder membuat dua akun awal untuk pengujian lokal:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@example.com` | `password` |
| Siswa | `siswa@example.com` | `password` |

Selain itu, seeder juga menambahkan data buku, data siswa dummy, transaksi peminjaman, dan detail peminjaman.

## Pengujian

```powershell
php artisan test
```

Test utama yang sudah tersedia mencakup:

- autentikasi bawaan Laravel Breeze,
- update stok saat peminjaman,
- pengembalian stok saat buku dikembalikan.

## Struktur Folder Penting

| Path | Isi |
| --- | --- |
| `app/Http/Controllers` | Controller admin, siswa, auth, dan profile |
| `app/Models` | Model `User`, `Book`, `Borrow`, `BorrowDetail` |
| `app/Http/Middleware` | Middleware role `is_admin` dan `is_siswa` |
| `database/migrations` | Skema tabel aplikasi |
| `database/seeders` | Seeder akun, buku, transaksi, dan detail transaksi |
| `resources/views/pages` | Halaman admin dan siswa |
| `routes/web.php` | Route utama aplikasi |
| `tests/Feature` | Skenario uji fitur aplikasi |

## Dokumentasi Tambahan

- [Dokumentasi aplikasi](docs/dokumentasi-aplikasi.md)
- [Dokumentasi fungsi dan prosedur](docs/fungsi-dan-prosedur.md)
- [Dokumentasi testing](docs/testing.md)
- [Dokumentasi debugging](docs/debugging.md)

## Ringkasan Alur Sistem

1. Pengguna membuka landing page lalu login atau registrasi.
2. Sistem mengarahkan pengguna ke dashboard sesuai role.
3. Admin mengelola buku, anggota, dan memantau transaksi.
4. Siswa memilih buku, menentukan tanggal pinjam dan jatuh tempo, lalu membuat transaksi.
5. Sistem menyimpan detail transaksi dan mengurangi stok buku.
6. Saat pengembalian, sistem menghitung denda berdasarkan keterlambatan dan menambah kembali stok buku.

## Lisensi

Kode dasar proyek menggunakan ekosistem Laravel. Silakan sesuaikan lisensi proyek akhir sesuai kebutuhan distribusi dan sekolah/instansi Anda.