SISTEM INFORMASI PERPUSTAKAAN DIGITAL

Nama Aplikasi:
Sistem Informasi Perpustakaan Digital Berbasis Laravel 11

=========================================
PERSYARATAN INSTALASI

Sebelum menjalankan aplikasi, pastikan perangkat telah menginstal:

1. PHP 8.2 atau lebih baru
2. Composer
3. Laragon / XAMPP
4. MySQL / MariaDB
5. Node.js dan NPM
6. Git (opsional)

=========================================
LANGKAH INSTALASI

1. Ekstrak file project.

2. Buka terminal pada folder project.

3. Install dependency Laravel:

composer install

4. Salin file environment:

copy .env.example .env

atau

cp .env.example .env

5. Generate application key:

php artisan key:generate

6. Buat database baru pada MySQL.

Contoh:
perpustakaan

7. Atur konfigurasi database pada file .env

DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=

8. Jalankan migrasi database:

php artisan migrate

9. Jika menggunakan data awal:

php artisan db:seed

10. Buat symbolic link storage:

php artisan storage:link

11. Jalankan aplikasi:

php artisan serve

12. Buka browser:

http://127.0.0.1:8000

=========================================
FITUR APLIKASI

1. Login Multi Role

   - Staff
   - Mahasiswa
   - Dosen

2. Registrasi Anggota

3. Manajemen Buku

4. Manajemen Eksemplar Buku

5. Peminjaman Buku

6. Perpanjangan Masa Pinjam

7. Pengembalian Buku

8. Perhitungan Denda Otomatis

9. Pembayaran Denda Online

10. Dashboard Statistik

11. Laporan Keuangan

12. Export PDF

13. Export Excel

14. Reminder WhatsApp Jatuh Tempo

15. Riwayat Peminjaman

16. Biodata Anggota

17. Katalog Buku Publik

=========================================
TATA CARA PENGGUNAAN

A. STAFF

1. Login sebagai Staff.
2. Masuk ke Dashboard Staff.
3. Kelola data buku.
4. Kelola data eksemplar.
5. Lihat data anggota.
6. Melihat laporan keuangan.
7. Export laporan PDF atau Excel.
8. Mengelola biodata anggota.

B. MAHASISWA

1. Registrasi akun.
2. Login sebagai Mahasiswa.
3. Membuka menu Sirkulasi.
4. Meminjam buku menggunakan kode eksemplar.
5. Melihat pinjaman aktif.
6. Melakukan perpanjangan peminjaman.
7. Mengembalikan buku.
8. Membayar denda jika ada.
9. Melihat riwayat peminjaman.

C. DOSEN

1. Login sebagai Dosen.
2. Mengakses dashboard dosen.
3. Melihat informasi perpustakaan.

=========================================
REMINDER WHATSAPP

Sistem akan mengirimkan pesan WhatsApp secara otomatis kepada anggota yang memiliki buku dengan tanggal pengembalian H-1 sebelum jatuh tempo.

Scheduler dijalankan menggunakan:

php artisan schedule:work

=========================================
PERINTAH ARTISAN YANG DIGUNAKAN

1. Menjalankan Server Laravel

php artisan serve

Fungsi:
Menjalankan aplikasi Laravel pada localhost.

Akses:
http://127.0.0.1:8000

=========================================

2. Migrasi Database

php artisan migrate

Fungsi:
Membuat seluruh tabel database sesuai migration.

=========================================

3. Membuat Storage Link

php artisan storage:link

Fungsi:
Menghubungkan folder storage dengan folder public agar gambar buku dapat ditampilkan.

=========================================

4. Membersihkan Cache Konfigurasi

php artisan config:clear

Fungsi:
Menghapus cache konfigurasi Laravel.

=========================================

5. Membersihkan Cache Aplikasi

php artisan cache:clear

Fungsi:
Menghapus cache aplikasi Laravel.

=========================================

6. Membersihkan Route Cache

php artisan route:clear

Fungsi:
Menghapus cache routing Laravel.

=========================================

7. Generate Application Key

php artisan key:generate

Fungsi:
Membuat APP_KEY baru pada file .env.

=========================================

8. Menjalankan Scheduler

php artisan schedule:work

Fungsi:
Menjalankan task otomatis Laravel secara terus-menerus.

Digunakan untuk:

- Reminder WhatsApp jatuh tempo buku.

=========================================

9. Menjalankan Scheduler Sekali

php artisan schedule:run

Fungsi:
Menjalankan semua jadwal yang tersedia satu kali.

Biasanya digunakan untuk pengujian.

=========================================

10. Mengirim Reminder WhatsApp Secara Manual

php artisan app:kirim-reminder-jatuh-tempo

Fungsi:
Mengirim pesan WhatsApp kepada anggota yang memiliki buku dengan tanggal pengembalian H-1 sebelum jatuh tempo.

Digunakan untuk:

- Pengujian fitur reminder.
- Pengiriman manual oleh administrator.

=========================================

11. Melihat Daftar Route

php artisan route:list

Fungsi:
Menampilkan seluruh route yang tersedia dalam aplikasi.

=========================================

12. Optimasi Laravel

php artisan optimize

Fungsi:
Mengoptimalkan performa aplikasi Laravel.

=========================================

13. Menghapus Seluruh Cache

php artisan optimize:clear

Fungsi:
Menghapus seluruh cache aplikasi Laravel.

Digunakan saat terjadi perubahan konfigurasi atau debugging.

=========================================

CATATAN

Untuk penggunaan normal aplikasi, perintah yang wajib dijalankan adalah:

php artisan serve

Apabila fitur reminder WhatsApp ingin digunakan secara otomatis, jalankan juga:

php artisan schedule:work

=========================================
PENGEMBANG

Nama:
Shamil Yassin

Framework:
Laravel 11

Database:
MySQL

Bahasa Pemrograman:
PHP, HTML, CSS, JavaScript

Tahun:
2026
