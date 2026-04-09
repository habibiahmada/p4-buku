# Dokumentasi Fungsi dan Prosedur

Dokumen ini merangkum fungsi inti aplikasi dan prosedur operasional yang berjalan di sistem. Fokus utamanya adalah fungsi backend, relasi data, dan langkah penggunaan modul utama.

## 1. Modul Autentikasi dan Profil

| Lokasi | Fungsi / metode | Deskripsi | Input utama | Output / efek |
| --- | --- | --- | --- | --- |
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | `create()` | Menampilkan halaman login | - | View `auth.login` |
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | `store(LoginRequest $request)` | Menangani login, regenerasi sesi, dan redirect ke dashboard sesuai role | `email`, `password`, `remember` | User login dan redirect |
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | `destroy(Request $request)` | Logout user dan menghapus sesi aktif | sesi login | Redirect ke landing page |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | `create()` | Menampilkan halaman registrasi | - | View `auth.register` |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | `store(Request $request)` | Membuat akun siswa baru | `name`, `email`, `password` | User baru dengan role `siswa` |
| `app/Http/Controllers/ProfileController.php` | `edit(Request $request)` | Menampilkan halaman profil | user aktif | View edit profil |
| `app/Http/Controllers/ProfileController.php` | `update(ProfileUpdateRequest $request)` | Memperbarui nama dan email profil | `name`, `email` | Data profil diperbarui |
| `app/Http/Controllers/ProfileController.php` | `destroy(Request $request)` | Menghapus akun sendiri setelah verifikasi password | `password` | Akun dihapus dan sesi logout |
| `app/Http/Requests/Auth/LoginRequest.php` | `rules()` | Aturan validasi form login | `email`, `password` | Rule validasi |
| `app/Http/Requests/Auth/LoginRequest.php` | `authenticate()` | Melakukan autentikasi dengan rate limit | `email`, `password`, `remember` | Login berhasil atau exception |
| `app/Http/Requests/Auth/LoginRequest.php` | `ensureIsNotRateLimited()` | Membatasi percobaan login berulang | email + IP | Menolak login berlebih |
| `app/Http/Requests/ProfileUpdateRequest.php` | `rules()` | Validasi update profil | `name`, `email` | Rule validasi |

## 2. Modul Admin

| Lokasi | Fungsi / metode | Deskripsi | Input utama | Output / efek |
| --- | --- | --- | --- | --- |
| `app/Http/Controllers/Admin/DashboardController.php` | `index()` | Mengambil statistik utama admin dashboard | data user, book, borrow, borrow detail | Ringkasan dan grafik dashboard |
| `app/Http/Controllers/Admin/BookController.php` | `index()` | Menampilkan daftar buku dengan pencarian, filter, dan statistik | query `search`, `publisher` | Daftar buku terfilter |
| `app/Http/Controllers/Admin/BookController.php` | `create()` | Menampilkan form tambah buku | - | View create buku |
| `app/Http/Controllers/Admin/BookController.php` | `store(Request $request)` | Menyimpan buku baru | `title`, `author`, `publisher`, `publication_year`, `stock` | Buku baru tersimpan |
| `app/Http/Controllers/Admin/BookController.php` | `edit(string $id)` | Menampilkan form edit buku | `id` buku | View edit buku |
| `app/Http/Controllers/Admin/BookController.php` | `update(Request $request, string $id)` | Memperbarui data buku | data buku + `id` | Buku diperbarui |
| `app/Http/Controllers/Admin/BookController.php` | `destroy(string $id)` | Menghapus buku | `id` buku | Buku terhapus |
| `app/Http/Controllers/Admin/UserController.php` | `index(Request $request)` | Menampilkan daftar user dengan filter dan statistik role | query `search`, `role` | Daftar user terfilter |
| `app/Http/Controllers/Admin/UserController.php` | `create()` | Menampilkan form tambah pengguna | - | View create user |
| `app/Http/Controllers/Admin/UserController.php` | `store(Request $request)` | Menyimpan pengguna baru | `name`, `email`, `password`, `password_confirmation`, `role` | User baru tersimpan |
| `app/Http/Controllers/Admin/UserController.php` | `edit(string $id)` | Menampilkan form edit user | `id` user | View edit user |
| `app/Http/Controllers/Admin/UserController.php` | `update(Request $request, string $id)` | Memperbarui data pengguna | data user + `id` | User diperbarui |
| `app/Http/Controllers/Admin/UserController.php` | `destroy(string $id)` | Menghapus pengguna | `id` user | User terhapus |
| `app/Http/Controllers/Admin/TransactionController.php` | `index(Request $request)` | Monitoring seluruh transaksi dengan filter nama, tanggal, dan status | `name`, `from_date`, `to_date`, `status` | Daftar transaksi dan statistik |

## 3. Modul Siswa

| Lokasi | Fungsi / metode | Deskripsi | Input utama | Output / efek |
| --- | --- | --- | --- | --- |
| `app/Http/Controllers/Siswa/DashboardController.php` | `index()` | Menampilkan dashboard siswa | - | View dashboard siswa |
| `app/Http/Controllers/Siswa/TransactionController.php` | `index()` | Menampilkan riwayat transaksi siswa | user aktif | View riwayat transaksi |
| `app/Http/Controllers/Siswa/TransactionController.php` | `create()` | Menampilkan form peminjaman dan daftar buku stok tersedia | stok buku > 0 | View create transaksi |
| `app/Http/Controllers/Siswa/TransactionController.php` | `store(Request $request)` | Menyimpan transaksi peminjaman multi-buku di dalam transaksi database | `borrowed_date`, `due_date`, `books[][id]`, `books[][qty]` | Borrowing baru, detail baru, stok berkurang |
| `app/Http/Controllers/Siswa/TransactionController.php` | `edit(string $id)` | Menampilkan form pengembalian untuk transaksi tertentu | `id` borrowing | View edit pengembalian |
| `app/Http/Controllers/Siswa/TransactionController.php` | `return()` | Menampilkan form pengembalian umum tanpa transaksi awal | - | View edit pengembalian |
| `app/Http/Controllers/Siswa/TransactionController.php` | `update(Request $request, string $id)` | Menyimpan pengembalian, denda, dan mengembalikan stok | `borrowing_id`, `returned_date`, `charge` | Status menjadi `returned`, stok bertambah |

## 4. Model dan Relasi Data

| Lokasi | Fungsi / metode | Deskripsi |
| --- | --- | --- |
| `app/Models/User.php` | `isAdmin()` | Mengecek apakah user ber-role `admin` |
| `app/Models/User.php` | `isSiswa()` | Mengecek apakah user ber-role `siswa` |
| `app/Models/User.php` | `borrows()` | Relasi one-to-many ke `Borrow` |
| `app/Models/Book.php` | `borrowDetails()` | Relasi one-to-many ke `BorrowDetail` |
| `app/Models/Borrow.php` | `user()` | Relasi transaksi ke user peminjam |
| `app/Models/Borrow.php` | `borrowDetails()` | Relasi transaksi ke detail buku |
| `app/Models/BorrowDetail.php` | `borrow()` | Relasi detail ke header transaksi |
| `app/Models/BorrowDetail.php` | `book()` | Relasi detail ke data buku |

## 5. Middleware

| Lokasi | Fungsi / metode | Deskripsi |
| --- | --- | --- |
| `app/Http/Middleware/IsAdmin.php` | `handle()` | Membatasi akses hanya untuk user `admin` |
| `app/Http/Middleware/IsSiswa.php` | `handle()` | Membatasi akses hanya untuk user `siswa` |

## 6. Prosedur Operasional

### 6.1 Prosedur login

1. Buka halaman `/login`.
2. Isi email dan password.
3. Sistem memvalidasi format input dan rate limit.
4. Jika kredensial benar, sistem login dan membuat sesi baru.
5. Pengguna diarahkan ke `/dashboard`, lalu diteruskan ke dashboard sesuai role.

### 6.2 Prosedur registrasi siswa

1. Buka halaman `/register`.
2. Isi nama, email, password, dan konfirmasi password.
3. Sistem membuat user baru dengan role `siswa`.
4. Sistem login otomatis.
5. Pengguna diarahkan ke dashboard siswa.

### 6.3 Prosedur admin menambah buku

1. Admin masuk ke menu `Kelola Buku`.
2. Klik tombol `Tambah Buku`.
3. Isi judul, penulis, penerbit, tahun terbit, dan stok.
4. Submit form.
5. Sistem memvalidasi data lalu menyimpan buku baru.
6. Admin kembali ke daftar buku dengan notifikasi sukses.

### 6.4 Prosedur admin mengubah atau menghapus buku

1. Admin membuka daftar buku.
2. Gunakan pencarian atau filter penerbit bila diperlukan.
3. Klik `Edit` untuk memperbarui data buku, atau `Hapus` untuk menghapus data.
4. Sistem memperbarui daftar buku setelah aksi selesai.

### 6.5 Prosedur admin menambah anggota

1. Admin membuka menu `Kelola Anggota`.
2. Klik `Tambah Anggota`.
3. Isi nama, email, password, konfirmasi password, dan pilih role.
4. Submit form.
5. Sistem memvalidasi data dan menyimpan user baru.

### 6.6 Prosedur admin memonitor transaksi

1. Admin membuka menu `Kelola Peminjaman`.
2. Gunakan filter nama anggota, rentang tanggal, atau status.
3. Sistem menampilkan hasil transaksi yang sesuai.
4. Admin memantau tanggal pinjam, jatuh tempo, tanggal kembali, total buku, dan denda.

### 6.7 Prosedur siswa membuat peminjaman

1. Siswa membuka halaman `Buat Peminjaman`.
2. Siswa memilih tanggal pinjam dan tanggal jatuh tempo.
3. Siswa mencari buku berdasarkan judul, penulis, atau penerbit.
4. Siswa menambahkan satu atau lebih buku ke daftar pinjam.
5. Siswa mengatur jumlah tiap buku tanpa melebihi stok.
6. Setelah form dikirim, sistem:
   menyimpan header transaksi pada tabel `borrowings`,
   menyimpan detail buku pada tabel `borrowings_detail`,
   mengurangi stok tiap buku.
7. Siswa diarahkan kembali ke daftar transaksi.

### 6.8 Prosedur siswa mengembalikan buku

1. Siswa membuka menu pengembalian.
2. Siswa memilih transaksi aktif.
3. Sistem menampilkan detail pinjaman, tanggal jatuh tempo, total buku, dan daftar buku.
4. Sistem menghitung jumlah hari terlambat.
5. Sistem menghitung denda dengan rumus `hari terlambat x 10000`.
6. Saat form dikirim, sistem:
   memperbarui status transaksi menjadi `returned`,
   menyimpan `returned_date`,
   menyimpan nilai `charge`,
   menambah kembali stok tiap buku.
7. Siswa kembali ke daftar transaksi dengan notifikasi sukses.

## 7. Validasi Penting di Sistem

### Peminjaman

- `borrowed_date` wajib berupa tanggal.
- `due_date` wajib berupa tanggal dan tidak boleh lebih awal dari `borrowed_date`.
- Minimal satu buku harus dipilih.
- Setiap item buku harus punya `id` valid.
- Setiap item buku harus punya `qty` minimal 1.
- Stok diperiksa ulang di database dengan `lockForUpdate()` sebelum transaksi disimpan.

### Pengembalian

- `borrowing_id` wajib ada di tabel `borrowings`.
- `returned_date` wajib berupa tanggal.
- `charge` wajib numerik dan tidak boleh negatif.
- User hanya boleh mengembalikan transaksi miliknya sendiri.
- Transaksi yang sudah `returned` tidak boleh diproses ulang.

### Buku

- Judul, penulis, penerbit wajib diisi.
- `publication_year` wajib integer dan dibatasi sampai tahun berjalan + 1.
- `stock` wajib integer minimum 0.

### Pengguna

- Nama wajib diisi.
- Email wajib valid dan unik.
- Password minimal 8 karakter.
- Role hanya boleh `admin` atau `siswa`.

## 8. Algoritma Inti

### Algoritma peminjaman

1. Validasi request.
2. Mulai transaksi database.
3. Kunci baris buku yang dipilih.
4. Pastikan seluruh stok cukup.
5. Buat record `borrowings`.
6. Buat record `borrowings_detail` untuk setiap buku.
7. Kurangi stok setiap buku.
8. Commit transaksi.

### Algoritma pengembalian

1. Validasi request.
2. Mulai transaksi database.
3. Ambil transaksi milik user yang sedang login.
4. Pastikan transaksi belum pernah dikembalikan.
5. Simpan tanggal kembali, denda, dan status `returned`.
6. Tambahkan stok untuk setiap buku di detail transaksi.
7. Commit transaksi.

## 9. Pengujian yang Sudah Ada

File test yang paling relevan dengan proses bisnis aplikasi adalah `tests/Feature/SiswaTransactionStockTest.php`.

Skenario yang diuji:

- stok berkurang saat siswa membuat transaksi peminjaman,
- stok bertambah kembali saat siswa mengembalikan transaksi.

Selain itu tersedia juga test autentikasi bawaan Laravel Breeze seperti login, registrasi, reset password, dan profile update.
