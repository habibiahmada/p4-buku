# Dokumentasi Aplikasi Aksara Pustaka

Dokumen ini menjelaskan aplikasi berdasarkan dua sumber utama:

- dokumen analisis proyek pada [laporanhasilanalisa.md](../laporanhasilanalisa.md),
- implementasi Laravel yang ada di repo ini.

Tujuannya adalah menjembatani hasil analisis dengan bentuk aplikasi yang sudah dibangun sehingga bisa dipakai untuk presentasi, maintenance, dan pengembangan lanjutan.

## 1. Gambaran Umum

Aksara Pustaka adalah aplikasi perpustakaan digital berbasis web yang dipakai untuk:

- mengelola data buku,
- mengelola data anggota,
- mencatat peminjaman,
- mencatat pengembalian,
- menghitung denda keterlambatan,
- memantau riwayat transaksi.

Aplikasi menggunakan dua aktor utama:

- `admin` sebagai pengelola sistem,
- `siswa` sebagai peminjam buku.

## 2. Tujuan Sistem

Mengacu pada dokumen analisis, aplikasi ini dibangun untuk menggantikan pencatatan manual yang rawan:

- data hilang,
- stok tidak sinkron,
- keterlambatan pelaporan,
- kesalahan pencatatan transaksi,
- kesulitan menelusuri riwayat peminjaman.

Implementasi saat ini sudah merealisasikan tujuan itu melalui transaksi digital, update stok otomatis, dan pemisahan dashboard per role.

## 3. Pemetaan Analisis ke Implementasi

| Kebutuhan dari analisis | Implementasi di aplikasi |
| --- | --- |
| Login admin dan anggota | Tersedia melalui Laravel Breeze dan `AuthenticatedSessionController` |
| Dashboard admin dan anggota | Tersedia di route `admin.dashboard` dan `siswa.dashboard` |
| Manajemen buku | Tersedia pada `Admin\\BookController` dan view `pages/admin/books/*` |
| Manajemen anggota | Tersedia pada `Admin\\UserController` dan view `pages/admin/users/*` |
| Peminjaman buku | Tersedia pada `Siswa\\TransactionController::store()` dengan dukungan multi-buku |
| Pengembalian buku | Tersedia pada `Siswa\\TransactionController::update()` |
| Perhitungan denda | Dihitung oleh business rule di server saat pengembalian disimpan |
| Riwayat transaksi | Tersedia untuk siswa dan admin dengan filter tanggal/status |
| Role-based access | Dialirkan melalui dashboard per role dan middleware `is_admin` / `is_siswa` |

## 4. Arsitektur Singkat

### Frontend

- Blade dipakai untuk templating.
- Tailwind CSS dipakai untuk styling antarmuka.
- Alpine.js dipakai untuk interaksi ringan seperti pencarian buku dan kalkulasi ringkasan transaksi.

### Backend

- Laravel 13 dipakai sebagai framework utama.
- Controller dipisah berdasarkan area `Admin`, `Siswa`, `Auth`, dan `Profile`.
- Model Eloquent dipakai untuk relasi data buku, user, transaksi, dan detail transaksi.

### Database

- MySQL dipakai sebagai database utama.
- Migrasi tersedia di folder `database/migrations`.
- Seeder tersedia untuk akun awal, buku, transaksi, dan detail transaksi.

## 5. Aktor dan Hak Akses

| Aktor | Hak akses |
| --- | --- |
| Admin | Dashboard admin, CRUD buku, CRUD anggota, monitoring transaksi |
| Siswa | Registrasi, login, dashboard siswa, membuat peminjaman, mengembalikan buku, melihat riwayat, mengelola profil |

## 6. Modul Sistem

### 6.1 Modul autentikasi

Fungsi utama:

- registrasi akun siswa,
- login,
- logout,
- reset password,
- proteksi route dengan middleware auth dan role.

### 6.2 Modul dashboard admin

Fungsi utama:

- menampilkan jumlah anggota dan admin,
- menampilkan jumlah judul dan stok buku,
- menampilkan transaksi aktif, selesai, dan terlambat,
- menampilkan grafik peminjaman 6 bulan terakhir,
- menampilkan daftar peminjaman terbaru.

### 6.3 Modul manajemen buku

Fungsi utama:

- tambah buku,
- ubah buku,
- hapus buku,
- pencarian berdasarkan judul atau penulis,
- filter berdasarkan penerbit.

### 6.4 Modul manajemen anggota

Fungsi utama:

- tambah pengguna admin/siswa,
- ubah pengguna,
- hapus pengguna,
- pencarian berdasarkan nama atau email,
- filter berdasarkan role.

### 6.5 Modul transaksi siswa

Fungsi utama:

- membuat transaksi peminjaman,
- memilih lebih dari satu buku dalam satu transaksi,
- validasi stok sebelum transaksi disimpan,
- pengurangan stok otomatis saat peminjaman,
- pengembalian buku,
- penambahan stok otomatis saat pengembalian,
- penyimpanan denda keterlambatan.

### 6.6 Modul monitoring transaksi admin

Fungsi utama:

- melihat seluruh transaksi,
- filter berdasarkan nama anggota,
- filter berdasarkan tanggal,
- filter status aktif, selesai, atau terlambat,
- melihat detail lengkap transaksi tertentu termasuk daftar buku yang dipinjam.
- melihat total buku per transaksi dan denda.

## 7. Struktur Data

### 7.1 Tabel `users`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `name` | string | Nama pengguna |
| `email` | string, unique | Email login |
| `role` | enum | `siswa` atau `admin` |
| `email_verified_at` | timestamp nullable | Waktu verifikasi email |
| `password` | string | Password hash |
| `remember_token` | string nullable | Token remember me |
| `created_at`, `updated_at` | timestamp | Audit waktu |

### 7.2 Tabel `books`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `title` | string | Judul buku |
| `author` | string | Penulis |
| `publisher` | string | Penerbit |
| `publication_year` | year | Tahun terbit |
| `stock` | string | Jumlah stok yang tersedia |
| `created_at`, `updated_at` | timestamp | Audit waktu |

### 7.3 Tabel `borrowings`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `user_id` | foreignId | Relasi ke `users.id` |
| `borrowed_date` | date | Tanggal pinjam |
| `due_date` | date | Tanggal jatuh tempo |
| `charge` | decimal(10,2) nullable | Denda |
| `returned_date` | date nullable | Tanggal kembali |
| `status` | enum | `borrowed` atau `returned` |
| `created_at`, `updated_at` | timestamp | Audit waktu |

### 7.4 Tabel `borrowings_detail`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `borrowing_id` | foreignId | Relasi ke `borrowings.id` |
| `book_id` | foreignId | Relasi ke `books.id` |
| `qty` | integer | Jumlah buku per item |
| `created_at`, `updated_at` | timestamp | Audit waktu |

### 7.5 Relasi

- Satu `user` memiliki banyak `borrowings`.
- Satu `borrowing` memiliki banyak `borrowings_detail`.
- Satu `book` dapat muncul di banyak `borrowings_detail`.

## 8. Route Utama Aplikasi

| Route | Nama | Kegunaan |
| --- | --- | --- |
| `/` | `home` | Landing page |
| `/dashboard` | `dashboard` | Redirect dashboard sesuai role |
| `/login` | `login` | Form login |
| `/register` | `register` | Form registrasi siswa |
| `/profile` | `profile.*` | Edit profil pengguna |
| `/admin` | `admin.dashboard` | Dashboard admin |
| `/admin/books` | `admin.books.*` | CRUD buku |
| `/admin/users` | `admin.users.*` | CRUD anggota/admin |
| `/admin/transactions` | `admin.transactions.*` | Monitoring transaksi |
| `/siswa` | `siswa.dashboard` | Dashboard siswa |
| `/siswa/transactions` | `siswa.transactions.*` | Riwayat, peminjaman, dan pengembalian siswa |
| `/siswa/transactions/return` | `siswa.transactions.return` | Form pengembalian umum |

## 9. Alur Bisnis Utama

### 9.1 Login

1. Pengguna memasukkan email dan password.
2. `LoginRequest` memvalidasi format input dan rate limit.
3. Sistem mencoba autentikasi.
4. Jika berhasil, sesi diregenerasi.
5. Pengguna diarahkan ke dashboard sesuai role.

### 9.2 Registrasi siswa

1. Calon pengguna mengisi nama, email, password, dan konfirmasi password.
2. Sistem membuat akun baru dengan role default `siswa`.
3. Pengguna login otomatis setelah registrasi.
4. Pengguna diarahkan ke dashboard.

### 9.3 Peminjaman buku

1. Siswa membuka halaman buat peminjaman.
2. Sistem menampilkan daftar buku dengan stok lebih dari 0.
3. Siswa memilih satu atau lebih buku dan menentukan kuantitas.
4. Siswa mengisi tanggal pinjam dan tanggal jatuh tempo.
5. Sistem memvalidasi stok tiap buku di dalam transaksi database.
6. Sistem membuat data `borrowings`.
7. Sistem membuat data `borrowings_detail`.
8. Sistem mengurangi stok buku sesuai jumlah yang dipinjam.

### 9.4 Pengembalian buku

1. Siswa memilih transaksi aktif yang akan dikembalikan.
2. Sistem menampilkan detail pinjaman dan tanggal jatuh tempo.
3. Form menghitung keterlambatan dan total denda.
4. Saat disubmit, sistem memastikan transaksi milik user yang login.
5. Sistem mengubah status transaksi menjadi `returned`.
6. Sistem mengisi `returned_date` dan `charge`.
7. Sistem menambah kembali stok semua buku di transaksi tersebut.

### 9.5 Manajemen buku

1. Admin membuka modul buku.
2. Admin dapat mencari buku atau memfilter penerbit.
3. Admin dapat menambah data baru.
4. Admin dapat mengedit data lama.
5. Admin dapat menghapus data buku.

### 9.6 Manajemen anggota

1. Admin membuka modul anggota.
2. Admin dapat mencari berdasarkan nama/email dan memfilter role.
3. Admin dapat menambah admin atau siswa.
4. Admin dapat memperbarui data pengguna.
5. Admin dapat menghapus pengguna.

### 9.7 Monitoring transaksi

1. Admin membuka modul transaksi.
2. Admin dapat memfilter berdasarkan nama anggota, tanggal, dan status.
3. Sistem menampilkan status aktif, selesai, atau terlambat.
4. Admin memantau jumlah buku, tanggal kembali, dan denda.
5. Admin dapat melihat detail lengkap transaksi tertentu dengan mengklik link atau tombol detail.

## 10. Catatan Sinkronisasi dengan Dokumen Analisis

Beberapa istilah di implementasi sedikit berbeda dari dokumen analisis awal, tetapi fungsinya tetap sama:

- Dokumen analisis menyebut `borrowing_details`, implementasi memakai tabel `borrowings_detail`.
- Dokumen analisis memakai label status Indonesia seperti `dipinjam` dan `dikembalikan`, implementasi database memakai nilai `borrowed` dan `returned`.
- Dokumen analisis menyebut atribut `year`, implementasi memakai `publication_year`.
- Dokumen analisis menyebut `return_date`, implementasi memakai `returned_date`.
- Denda keterlambatan pada implementasi saat ini dihitung di server menggunakan aturan `BORROWING_DAILY_FINE` dengan default Rp10.000 per hari, lalu nilainya disimpan ke tabel `borrowings`.

## 11. Pengembangan Lanjutan yang Masuk Akal

Beberapa pengembangan berikut cocok jika proyek ingin dilanjutkan:

- laporan cetak atau ekspor PDF/Excel,
- notifikasi jatuh tempo,
- dashboard grafik yang lebih lengkap,
- audit log perubahan data,
- kebijakan denda yang bisa diatur dari admin panel,
- validasi bisnis tambahan seperti batas maksimal buku per siswa.
