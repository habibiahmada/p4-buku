# Dokumentasi Testing

Dokumen ini menjelaskan strategi testing, struktur test, cara menjalankan test, dan cakupan utama yang sudah tersedia di proyek Aksara Pustaka.

## 1. Tujuan Testing

Testing dipakai untuk memastikan logika bisnis inti tetap stabil saat aplikasi dikembangkan. Fokus utama test di proyek ini adalah:

- autentikasi pengguna,
- pembatasan akses berdasarkan role,
- proses peminjaman buku,
- sinkronisasi stok,
- proses pengembalian buku,
- perhitungan denda keterlambatan,
- proteksi transaksi milik pengguna.

## 2. Jenis Test yang Dipakai

### Feature test

Feature test mengecek alur aplikasi dari sisi request HTTP, database, session, redirect, dan middleware.

Contoh yang diuji:

- login dan registrasi,
- akses admin dan siswa,
- peminjaman buku,
- pengembalian buku,
- stok berkurang dan bertambah,
- denda dihitung dari server.

### Unit test

Unit test mengecek business rule murni tanpa bergantung pada request HTTP.

Contoh yang diuji:

- hitung hari keterlambatan,
- hitung nominal denda,
- cek kecukupan stok,
- deteksi status overdue.

## 3. Struktur File Test

| Path | Fungsi |
| --- | --- |
| `tests/Feature/Auth/*` | Test autentikasi bawaan Laravel Breeze |
| `tests/Feature/SiswaTransactionStockTest.php` | Test stok dasar saat pinjam dan kembali |
| `tests/Feature/BorrowingWorkflowTest.php` | Test alur bisnis utama peminjaman dan pengembalian |
| `tests/Feature/AccessControlTest.php` | Test akses role admin, siswa, dan guest |
| `tests/Unit/BorrowingRulesTest.php` | Unit test business rule pinjam/kembali |

## 4. Konfigurasi Test Environment

Konfigurasi penting ada di [phpunit.xml](../phpunit.xml):

- `APP_ENV=testing`
- `DB_CONNECTION=sqlite`
- `DB_DATABASE=:memory:`
- `SESSION_DRIVER=array`
- `CACHE_STORE=array`
- `QUEUE_CONNECTION=sync`

Artinya:

- test memakai database SQLite in-memory,
- database test dibangun ulang setiap test feature,
- hasil test tidak mengubah database lokal MySQL Anda.

## 5. Cara Menjalankan Test

### Jalankan semua test

```powershell
php artisan test
```

### Jalankan hanya feature test

```powershell
php artisan test --testsuite=Feature
```

### Jalankan hanya unit test

```powershell
php artisan test --testsuite=Unit
```

### Jalankan satu file test tertentu

```powershell
php artisan test tests/Feature/BorrowingWorkflowTest.php
php artisan test tests/Unit/BorrowingRulesTest.php
```

### Jalankan test berdasarkan nama

```powershell
php artisan test --filter=BorrowingWorkflowTest
php artisan test --filter=calculates
```

## 6. Skenario Utama yang Sudah Dicakup

### Akses dan autentikasi

- user bisa login,
- user diarahkan ke dashboard umum `/dashboard`,
- guest tidak bisa membuka area admin atau siswa,
- siswa tidak bisa membuka area admin,
- admin tidak bisa membuka area siswa.

### Peminjaman

- siswa bisa membuat transaksi dengan lebih dari satu buku,
- stok tiap buku berkurang sesuai jumlah,
- detail transaksi tersimpan,
- transaksi gagal bila ada stok tidak cukup.

### Pengembalian

- siswa bisa mengembalikan transaksi miliknya,
- stok bertambah kembali setelah pengembalian,
- denda dihitung di server,
- manipulasi input `charge` dari client tidak memengaruhi hasil final,
- user tidak bisa mengembalikan pinjaman milik user lain,
- transaksi yang sudah selesai tidak boleh dikembalikan ulang.

### Business rules

- keterlambatan 0 hari menghasilkan denda 0,
- keterlambatan lebih dari 0 hari menghasilkan denda sesuai tarif,
- stok cukup/tidak cukup bisa dievaluasi secara unit test.

## 7. Menambahkan Test Baru

Langkah yang disarankan:

1. Tentukan apakah skenario termasuk `Feature` atau `Unit`.
2. Siapkan data minimal yang benar-benar dibutuhkan.
3. Gunakan `RefreshDatabase` untuk feature test.
4. Fokus pada satu perilaku bisnis per test.
5. Pastikan nama test menjelaskan ekspektasi hasil.

Contoh pola assert yang sering dipakai:

- `assertRedirect()`
- `assertForbidden()`
- `assertNotFound()`
- `assertSessionHasErrors()`
- `assertDatabaseHas()`
- `assertDatabaseMissing()`

## 8. Catatan Penting

- Denda pengembalian tidak lagi dipercaya dari input form client.
- Nilai denda dihitung ulang di server melalui `BorrowingRules` dan `BorrowingService`.
- Tarif denda harian dapat diatur lewat `BORROWING_DAILY_FINE` dan default-nya adalah `10000`.

## 9. Status Verifikasi

Saat dokumentasi ini dibuat, test suite berhasil dijalankan dengan perintah:

```powershell
php artisan test
```
