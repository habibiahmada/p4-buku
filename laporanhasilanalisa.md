Laporan Hasil Analisis Aplikasi Peminjaman Buku
Uji Kompetensi Keahlian
(UKK)
Tahun Ajaran 2025/2026

 

Disusun Oleh:
Habibi Ahmad Aziz
Kelas 12 RPL 2 
PENDAHULUAN
1.	Latar Belakang
Sistem peminjaman buku secara manual masih menimbulkan berbagai permasalahan operasional, seperti pencatatan yang tidak konsisten, risiko kehilangan data, kesalahan dalam pengelolaan stok buku, serta keterlambatan dalam proses pelaporan peminjaman dan pengembalian. Selain itu, proses manual juga menyulitkan dalam pelacakan riwayat transaksi dan perhitungan denda keterlambatan.
Untuk mengatasi permasalahan tersebut, diperlukan sebuah aplikasi berbasis web yang mampu mengotomasi seluruh proses peminjaman buku secara terstruktur, akurat, dan efisien.
2.	Tujuan dan Manfaat
Tujuan dari pengembangan aplikasi peminjaman buku ini adalah:
•	Mengotomasi proses peminjaman dan pengembalian buku 
•	Mempermudah pengelolaan data buku dan anggota 
•	Menyediakan sistem pencatatan transaksi yang terintegrasi 
•	Menghitung denda keterlambatan secara otomatis 
3.	Manfaat yang diharapkan:
•	Meningkatkan efisiensi operasional perpustakaan 
•	Mengurangi kesalahan pencatatan data 
•	Mempermudah monitoring transaksi dan stok buku 
•	Menyediakan laporan yang akurat dan real-time 
4.	Ruang Lingkup
Aplikasi ini berbasis web dan digunakan oleh dua jenis pengguna:
•	Admin: mengelola data buku, anggota, serta transaksi peminjaman dan pengembalian 
•	Siswa: melakukan peminjaman, pengembalian, dan melihat riwayat transaksi 
5.	Fitur utama meliputi:
•	Manajemen data buku 
•	Manajemen data anggota 
•	Proses peminjaman dan pengembalian buku
•	Riwayat transaksi dan perhitungan denda
 
DAFTAR ISI
PENDAHULUAN	1
1.	Latar Belakang	1
2.	Tujuan dan Manfaat	1
3.	Manfaat yang diharapkan:	1
4.	Ruang Lingkup	1
5.	Fitur utama meliputi:	2
DAFTAR ISI	3
BAB I GAMBARAN UMUM SISTEM / APLIKASI	4
1.	Gambaran Umum	4
2.	Analisis Pengguna	4
3.	Use Case Diagram	4
BAB II Analisis Kebutuhan Sistem	6
1.	Kebutuhan Fungsional	6
2.	Kebutuhan Non-Fungsional	7
3.	Kebutuhan Data	7
BAB III Analisis Proses Sistem	10
1.	Deskripsi Proses Utama	10
2.	Input – Proses – Output (IPO)	11
3.	Activity Diagram	13
BAB III  Perancangan Sistem (Design)	14
1.	ERD (Entity Relationship Diagram)	14
2.	Struktur Data	16
3.	Diagram Alur (Flowchart / Pseudocode)	19
4.	Desain Modul / Fungsi	21
5.	Class Diagram	23
6.	Perancangan Antarmuka (UI)	24
BAB IV Kesimpulan Analisis & Desain	32
1.	Kesimpulan	32
 
BAB I
GAMBARAN UMUM SISTEM / APLIKASI
1.	Gambaran Umum
Aplikasi peminjaman buku merupakan sistem berbasis web yang dirancang untuk mengelola seluruh aktivitas perpustakaan secara digital. Sistem ini menggunakan mekanisme autentikasi berbasis role untuk membedakan hak akses antara admin dan anggota.
Setiap pengguna harus melakukan login untuk mengakses sistem sesuai dengan perannya masing-masing.
2.	Analisis Pengguna
a.	Admin
Admin memiliki kontrol penuh terhadap sistem, dengan tanggung jawab:
•	Mengelola data buku (tambah, ubah, hapus, lihat) 
•	Mengelola data anggota 
•	Memproses peminjaman dan pengembalian buku 
•	Memantau status transaksi dan stok buku 
b.	Siswa
Anggota merupakan pengguna yang memanfaatkan layanan perpustakaan, dengan hak akses:
•	Melakukan peminjaman buku 
•	Melakukan pengembalian buku 
•	Melihat riwayat transaksi peminjaman
3.	Use Case Diagram
 
 
BAB II
Analisis Kebutuhan Sistem
1.	Kebutuhan Fungsional
Sistem harus menyediakan fitur-fitur berikut:
a.	Autentikasi dan Otorisasi 
o	Login untuk admin dan anggota 
o	Pembagian akses berdasarkan role 
b.	Dashboard 
o	Dashboard admin untuk manajemen sistem 
o	Dashboard anggota untuk aktivitas peminjaman 
c.	Manajemen Buku (Admin) 
o	Tambah data buku 
o	Edit data buku 
o	Hapus data buku 
o	Lihat daftar buku 
d.	Manajemen Anggota (Admin) 
o	Tambah data anggota 
o	Edit data anggota 
o	Hapus data anggota 
o	Lihat daftar anggota 
e.	Peminjaman Buku 
o	Anggota dapat memilih buku dan melakukan peminjaman 
o	Sistem mencatat tanggal peminjaman 
f.	Pengembalian Buku 
o	Anggota mengembalikan buku 
o	Sistem mencatat tanggal pengembalian
o	Sistem menghitung denda pengembalian 
o	Sistem memperbarui status transaksi 
g.	Perhitungan Denda 
o	Sistem menghitung denda jika terjadi keterlambatan pengembalian 
h.	Riwayat Transaksi 
o	Anggota dapat melihat riwayat peminjaman 
o	Admin dapat memonitor seluruh transaksi
2.	Kebutuhan Non-Fungsional
a.	Keamanan 
o	Sistem menggunakan autentikasi login 
o	Hak akses dibatasi berdasarkan role pengguna 
b.	Performa 
o	Waktu respon sistem maksimal < 3 detik 
o	Query database harus efisien 
c.	Teknologi 
o	Backend: PHP 
o	Framework: Laravel 
o	Database: MySQL 
o	Frontend: Web-based (Laravel 13, blade UI icon, Tailwindcss) 
d.	Kompatibilitas 
o	Mendukung browser modern seperti Chrome, Firefox, dan Safari
3.	Kebutuhan Data
Sistem membutuhkan beberapa entitas utama sebagai berikut:
a.	Users 
o	id 
o	name 
o	email 
o	password 
o	role 
o	timestamps 
b.	Books 
o	id 
o	title 
o	author 
o	publisher 
o	year 
o	stock 
o	timestamps 
c.	Borrowings
o	id 
o	user_id 
o	borrow_date 
o	return_date 
o	status 
o	timestamps 
d.	Borrowing_Details
o	id 
o	borrowing_id 
o	book_id 
o	quantity
 
BAB III
Analisis Proses Sistem
1.	Deskripsi Proses Utama
a.	Proses Login
Proses login merupakan tahap awal untuk mengakses sistem. Pengguna memasukkan email/username dan password, kemudian sistem melakukan validasi ke database. Jika data valid, pengguna diarahkan ke dashboard sesuai dengan role (admin atau anggota). Jika tidak valid, sistem menampilkan pesan kesalahan.
b.	Proses Peminjaman Buku
Proses ini dilakukan oleh anggota(siswa) untuk meminjam buku yang tersedia.
Alur proses:
•	Sistem mengecek dan menampilkan buku yang tersedia beserta dengan jumlahnya
•	Anggota memilih buku yang ingin dipinjam 
•	sistem mencatat transaksi peminjaman 
•	Sistem mengurangi jumlah stok buku 
•	Status peminjaman disimpan sebagai “dipinjam” 
Jika stok tidak tersedia, sistem akan menolak proses peminjaman.
c.	Proses Pengembalian Buku
Proses ini dilakukan saat anggota mengembalikan buku.
Alur proses:
•	Anggota memilih data peminjaman yang akan dikembalikan 
•	Sistem mencatat tanggal pengembalian 
•	Sistem membandingkan tanggal kembali dengan tanggal jatuh tempo 
•	Jika terlambat, sistem menghitung denda 
•	Sistem memperbarui status menjadi “dikembalikan” 
•	Sistem menambahkan kembali stok buku 
d.	Proses Manajemen Buku (Admin)
Admin dapat mengelola data buku yang tersedia dalam sistem.
Alur proses:
•	Admin menambah, mengubah, atau menghapus data buku 
•	Sistem menyimpan perubahan ke database 
•	Data buku diperbarui secara real-time 
e.	Proses Manajemen Anggota (Admin)
Admin bertanggung jawab dalam mengelola data anggota.
Alur proses:
•	Admin menambahkan atau memperbarui data anggota 
•	Sistem menyimpan data ke database 
•	Data anggota dapat digunakan untuk proses login dan transaksi 
f.	Proses Riwayat Transaksi
Digunakan untuk melihat data peminjaman yang telah dilakukan.
Alur proses:
•	Pengguna memilih menu riwayat 
•	Sistem mengambil data transaksi dari database 
•	Sistem menampilkan daftar peminjaman beserta statusnya 
2.	Input – Proses – Output (IPO)
a.	IPO Proses Login
•	Input: 
o	Email/Username 
o	Password 
•	Proses: 
o	Validasi input 
o	Pencocokan data dengan database 
o	Identifikasi role pengguna 
•	Output: 
o	Berhasil login → masuk ke dashboard 
o	Gagal login → pesan error 
b.	IPO Proses Peminjaman Buku
•	Input: 
o	Judul buku 
o	Jumlah buku 
o	Tanggal peminjaman
o	Tanggal pengembalian
•	Proses: 
o	Validasi data input 
o	Cek ketersediaan stok 
o	Simpan data peminjaman 
o	Update stok buku 
•	Output: 
o	Peminjaman berhasil dicatat
c.	IPO Proses Pengembalian Buku
•	Input: 
o	ID peminjaman 
o	Tanggal pengembalian 
•	Proses: 
o	Ambil data peminjaman 
o	Hitung selisih hari keterlambatan 
o	Hitung denda (jika ada) 
o	Update status peminjaman 
o	Tambah stok buku 
•	Output: 
o	Status berubah menjadi “dikembalikan” 
o	Informasi denda (jika terlambat) 
d.	IPO Manajemen Buku (Admin)
•	Input: 
o	Data buku (judul, penulis, penerbit, tahun, stok) 
•	Proses: 
o	Validasi input 
o	Simpan / update / hapus data di database 
•	Output: 
o	Data buku berhasil diperbarui 
o	Daftar buku terbaru ditampilkan 
e.	IPO Manajemen Anggota (Admin)
•	Input: 
o	Data anggota (nama, email, password, role) 
•	Proses: 
o	Validasi data 
o	Simpan / update / hapus data anggota 
•	Output: 
o	Data anggota tersimpan atau diperbarui 
f.	IPO Riwayat Transaksi
•	Input: 
o	Nama user (opsional filter) 
•	Proses: 
o	Ambil data transaksi dari database 
o	Filter berdasarkan user (jika diperlukan) 
•	Output: 
o	Daftar riwayat peminjaman dan pengembalian
3.	Activity Diagram 
  
BAB III 
Perancangan Sistem (Design)
1.	ERD (Entity Relationship Diagram)
Berdasarkan hasil analisa, sistem terdiri dari 4 entitas utama:
 
a.	USERS
 
Menyimpan data pengguna sistem (admin dan anggota)
•	Primary Key: id 
•	Atribut: 
o	name 
o	email (unique) 
o	password 
o	role (admin / siswa) 
o	created_at 
o	updated_at 
b.	BOOKS
 
Menyimpan data buku
•	Primary Key: id 
•	Atribut: 
o	title 
o	author 
o	publisher 
o	year 
o	stock 
o	created_at 
o	updated_at 
c.	BORROWINGS
 
Menyimpan data transaksi peminjaman
•	Primary Key: id 
•	Foreign Key: user_id → users.id 
•	Atribut: 
o	borrow_date 
o	due_date 
o	return_date 
o	charge (denda) 
o	status (dipinjam / dikembalikan) 
o	created_at 
o	updated_at 
d.	BORROWING_DETAILS
 
Relasi many-to-many antara borrowing dan books
•	Primary Key: id 
•	Foreign Key: 
o	borrowing_id → borrowings.id 
o	book_id → books.id 
•	Atribut: 
o	quantity 
o	
2.	Struktur Data
Tabel: users
•	id (PK) : id
•	name : Varchar(255)
•	email (unique) : Varchar(100)
•	password : Varchar(255)
•	role : Enum('admin','anggota')
•	created_at : Timestamp
•	updated_at : Timestamp
Tabel: books
•	id (PK) : int
•	title : Varchar(255)
•	author : Varchar(100)
•	publisher : Varchar(100)
•	year : Year
•	stock : int
•	created_at : Timestamp
•	updated_at : Timestamp

Tabel: borrowings
•	id (PK) : int
•	user_id (FK) : int
•	borrow_date : Date
•	due_date : Date
•	return_date : Date
•	charge : int
•	status : Enum('dipinjam','dikembalikan')
•	created_at : Timestamp
•	updated_at : Timestamp

Tabel: borrowing_details
•	id (PK) : int
•	borrowing_id (FK) : int
•	book_id (FK) : int
•	quantity: int
 
3.	Diagram Alur (Flowchart / Pseudocode)
a.	Flowchart Aplikasi
 
b.	Pseudocode Login
START
INPUT email, password
VALIDASI ke database

IF data valid THEN
    CEK role
    IF role = admin THEN
        TAMPILKAN dashboard admin
    ELSE
        TAMPILKAN dashboard anggota
    ENDIF
ELSE
    TAMPILKAN pesan error
ENDIF
END
c.	Pseudocode Peminjaman Buku
START
PILIH buku
INPUT jumlah

CEK stok buku
IF stok >= jumlah THEN
    SIMPAN data ke borrowings
    SIMPAN ke borrowing_details
    KURANGI stok buku
    SET status = "dipinjam"
    TAMPILKAN sukses
ELSE
    TAMPILKAN "stok tidak cukup"
ENDIF
END
d.	Pseudocode Pengembalian Buku
START
INPUT id peminjaman

AMBIL data peminjaman
SET return_date = hari ini

IF return_date > due_date THEN
    HITUNG denda
    SIMPAN charge
ENDIF

UPDATE status = "dikembalikan"
TAMBAH stok buku
TAMPILKAN hasil pengembalian
END

4.	Desain Modul / Fungsi
Untuk implementasi menggunakan bahasa pemrogramam PHP dan menggunakan framework Laravel, sistem dibagi menjadi beberapa modul:
a.	Modul Autentikasi
Fungsi:
•	login() 
•	logout() 
•	checkRole() 
Tujuan:
•	Mengelola autentikasi dan otorisasi user 
b.	Modul User (Admin)
Fungsi:
•	createUser() 
•	updateUser() 
•	deleteUser() 
•	getUsers() 
Tujuan:
•	Manajemen data anggota 
c.	Modul Book
Fungsi:
•	createBook() 
•	updateBook() 
•	deleteBook() 
•	getBooks() 
•	updateStock() 
Tujuan:
•	Mengelola data buku dan stok 
d.	Modul Borrowing
Fungsi:
•	createBorrowing() 
•	addBorrowingDetail() 
•	updateStatus() 
•	getBorrowings() 
Tujuan:
•	Mengelola transaksi peminjaman 
e.	Modul Return
Fungsi:
•	processReturn() 
•	calculateFine() 
Tujuan:
•	Mengelola pengembalian dan denda 
f.	Modul History
Fungsi:
•	getUserHistory() 
•	getAllTransactions() 
Tujuan:
•	Menampilkan riwayat transaksi
5.	Class Diagram
 
6.	Perancangan Antarmuka (UI)
a.	Landing Page
 
b.	Daftar
 
c.	Login
 
d.	Siswa
i.	Dashboard
 
ii.	Peminjaman
 
iii.	Buat Peminjaman
 
iv.	Pengembalian
 
e.	Admin
i.	Dashboard
 

 
ii.	Kelola Anggota 
1.	Tambah/buat Anggota
 
2.	Edit Anggota
 
iii.	Kelola Buku 
1.	Tambah/buat Buku 
2.	Edit Buku
 
iv.	Pantau Peminjaman
  
BAB IV
Kesimpulan Analisis & Desain
1.	Kesimpulan
Berdasarkan hasil analisis dan perancangan yang telah dilakukan, dapat disimpulkan bahwa aplikasi peminjaman buku yang dirancang telah memenuhi kebutuhan sistem secara menyeluruh, baik dari sisi fungsional maupun non-fungsional.
Dari tahap analisis, sistem telah berhasil mengidentifikasi aktor utama yaitu admin dan anggota, beserta kebutuhan masing-masing. Seluruh proses inti seperti login, peminjaman buku, pengembalian, manajemen data, serta riwayat transaksi telah didefinisikan dengan jelas melalui deskripsi proses dan pendekatan input–proses–output (IPO). Hal ini memastikan bahwa alur bisnis sistem sudah terstruktur dan dapat dipahami sebelum masuk ke tahap implementasi.
Pada tahap desain, perancangan sistem telah dituangkan dalam bentuk ERD, flowchart, pseudocode, serta struktur modul. ERD yang dibuat menunjukkan relasi antar entitas yang konsisten dan ter-normalisasi dengan baik, sehingga mampu mendukung integritas data dan efisiensi pengolahan database. Flowchart dan pseudocode menggambarkan alur logika sistem secara sistematis, sehingga mempermudah proses pengembangan program. Selain itu, pembagian modul/fungsi juga telah dirancang dengan pendekatan terstruktur yang memudahkan implementasi, pemeliharaan, dan pengembangan sistem di masa depan.
Secara keseluruhan, hasil analisis dan desain ini telah membentuk fondasi yang kuat untuk tahap implementasi. Sistem yang dirancang tidak hanya mampu memenuhi kebutuhan pengguna saat ini, tetapi juga memiliki fleksibilitas untuk dikembangkan lebih lanjut, seperti penambahan fitur laporan, notifikasi, maupun integrasi sistem lainnya.
Dengan demikian, desain aplikasi peminjaman buku ini dinyatakan siap untuk diimplementasikan ke dalam tahap pengembangan (coding), pengujian, dan deployment sesuai dengan standar rekayasa perangkat lunak yang baik.
