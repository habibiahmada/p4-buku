# Dokumentasi Debugging

Dokumen ini berisi panduan praktis untuk menelusuri error aplikasi dan kegagalan test di proyek Aksara Pustaka.

## 1. Prinsip Debugging

Gunakan urutan berikut saat ada masalah:

1. reproduksi masalah secara konsisten,
2. cek pesan error paling awal,
3. identifikasi layer yang gagal,
4. perbaiki penyebab utama,
5. jalankan test ulang.

Layer yang paling sering terlibat:

- route dan middleware,
- controller,
- validasi request,
- business rule,
- database/migration,
- view atau javascript ringan,
- test environment.

## 2. Perintah Debugging yang Sering Dipakai

### Cek daftar route

```powershell
php artisan route:list
```

### Bersihkan cache aplikasi

```powershell
php artisan optimize:clear
```

### Jalankan test tertentu

```powershell
php artisan test --filter=BorrowingWorkflowTest
php artisan test tests/Feature/AccessControlTest.php
```

### Jalankan semua test

```powershell
php artisan test
```

## 3. Titik Debugging Utama di Aplikasi

### A. Login dan dashboard

Jika user tidak masuk ke halaman yang benar:

- cek [routes/web.php](../routes/web.php),
- cek [AuthenticatedSessionController.php](../app/Http/Controllers/Auth/AuthenticatedSessionController.php),
- cek role user di database,
- cek route `dashboard`, `admin.dashboard`, dan `siswa.dashboard`.

### B. Akses admin atau siswa

Jika user mendapat `403` atau bisa masuk ke area yang salah:

- cek middleware `is_admin` dan `is_siswa`,
- cek grouping middleware pada route admin dan siswa,
- cek nilai `role` di tabel `users`.

File penting:

- [IsAdmin.php](../app/Http/Middleware/IsAdmin.php)
- [IsSiswa.php](../app/Http/Middleware/IsSiswa.php)
- [routes/web.php](../routes/web.php)

### C. Peminjaman gagal

Jika transaksi pinjam tidak tersimpan:

- cek validasi `borrowed_date`, `due_date`, dan `books`,
- cek stok buku yang dipilih,
- cek apakah ada qty melebihi stok,
- cek error session pada form,
- cek logic di `BorrowingService`.

File penting:

- [TransactionController.php](../app/Http/Controllers/Siswa/TransactionController.php)
- [BorrowingService.php](../app/Services/BorrowingService.php)
- [BorrowingRules.php](../app/Support/BorrowingRules.php)

### D. Denda pengembalian salah

Jika nominal denda tidak sesuai:

- cek nilai `due_date`,
- cek `returned_date`,
- cek `BORROWING_DAILY_FINE`,
- cek hasil `calculateOverdueDays()` dan `calculateFine()`.

File penting:

- [BorrowingRules.php](../app/Support/BorrowingRules.php)
- [config/borrowing.php](../config/borrowing.php)
- [edit.blade.php](../resources/views/pages/siswa/transactions/edit.blade.php)

### E. Stok tidak sinkron

Jika stok buku tidak berkurang atau tidak kembali:

- cek apakah transaksi `borrowings_detail` tersimpan,
- cek qty di tiap detail transaksi,
- cek proses `decrement()` saat pinjam,
- cek proses `increment()` saat kembali,
- jalankan test stok.

File penting:

- [BorrowingService.php](../app/Services/BorrowingService.php)
- [SiswaTransactionStockTest.php](../tests/Feature/SiswaTransactionStockTest.php)
- [BorrowingWorkflowTest.php](../tests/Feature/BorrowingWorkflowTest.php)

## 4. Pola Error yang Umum

### Error validasi form

Gejala:

- kembali ke halaman sebelumnya,
- ada pesan error di session,
- data tidak tersimpan.

Langkah cek:

- pastikan semua field wajib terisi,
- pastikan format tanggal benar,
- pastikan `books[][id]` dan `books[][qty]` terkirim.

### Error akses `403 Forbidden`

Gejala:

- user login tapi tidak bisa masuk ke route tertentu.

Langkah cek:

- pastikan role user benar,
- pastikan route memang diperuntukkan untuk role tersebut,
- cek middleware pada route group.

### Error `404 Not Found` saat pengembalian

Gejala:

- update pengembalian gagal saat user mencoba mengembalikan transaksi.

Langkah cek:

- pastikan transaksi milik user yang sedang login,
- pastikan ID transaksi benar,
- pastikan transaksi masih aktif.

### Error test database

Gejala:

- test gagal karena tabel tidak ada,
- migration test tidak berjalan.

Langkah cek:

- cek `phpunit.xml`,
- pastikan feature test memakai `RefreshDatabase`,
- jalankan ulang `php artisan test`.

## 5. Teknik Logging yang Aman

Saat butuh menelusuri alur runtime, gunakan logging sementara:

```php
logger()->info('Borrowing payload', $validated);
logger()->info('Calculated fine', ['charge' => $charge]);
```

Setelah masalah selesai:

- hapus logging sementara yang tidak diperlukan,
- jangan commit `dd()` atau `dump()` ke branch final.

## 6. Cara Membaca Failure pada Test

Mulai dari bagian paling atas failure karena biasanya itu sumber masalah utama.

Perhatikan:

- expected vs actual redirect,
- expected vs actual database value,
- session errors yang muncul,
- status code yang didapat `302`, `403`, `404`, atau `500`.

Contoh pembacaan:

- bila expected charge `40000` tapi actual `0`, fokus ke business rule denda,
- bila expected `403` tapi actual `200`, fokus ke middleware,
- bila expected redirect ke `/dashboard` tapi actual ke route lain, fokus ke auth redirect.

## 7. Checklist Saat Selesai Debugging

- masalah bisa direproduksi dan sudah hilang,
- test terkait lulus,
- seluruh test suite tetap lulus,
- tidak ada `dd()` tertinggal,
- dokumentasi tetap sesuai implementasi.
