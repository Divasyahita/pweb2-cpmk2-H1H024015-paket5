# Sistem Informasi Bengkel Servis Kendaraan

## Deskripsi Proyek

Sistem Informasi Bengkel Servis Kendaraan merupakan aplikasi berbasis web yang dirancang untuk membantu pengelolaan data operasional bengkel, mulai dari data pelanggan, kendaraan, hingga riwayat servis. Aplikasi ini memudahkan pengelolaan informasi agar data tersimpan secara terstruktur dan proses pencatatan servis menjadi lebih efisien.

Melalui aplikasi ini, pengguna dapat mengelola data pelanggan, mencatat kendaraan yang dimiliki pelanggan, serta mencatat layanan servis yang dilakukan pada setiap kendaraan. Sistem ini juga mendukung pengelolaan status servis dan informasi biaya servis sesuai dengan fitur yang tersedia pada aplikasi.

Proyek ini dikembangkan untuk memenuhi tugas Praktikum Pemrograman Web II dengan menerapkan konsep CRUD (Create, Read, Update, Delete), relasi antartabel, serta pengembangan aplikasi web menggunakan Laravel dan Livewire.

## Identitas

- **Nama:** Diva Syahita Mawarni
- **NIM:** H1H024015
- **Nomor Paket:** 5
- **Mata Kuliah:** Praktikum Pemrograman Web II

## Teknologi

- Laravel
- Livewire
- Blade
- Tailwind CSS
- Database sesuai konfigurasi aplikasi

## Cara Instalasi

1. Clone repository:

   ```bash
   git clone URL_REPOSITORY
   ```

2. Masuk ke direktori proyek:

   ```bash
   cd bengkel-servis-kendaraan-paket5
   ```

3. Instal dependensi PHP:

   ```bash
   composer install
   ```

4. Salin file konfigurasi lingkungan:

   ```bash
   copy .env.example .env
   ```

5. Buat application key:

   ```bash
   php artisan key:generate
   ```

6. Sesuaikan konfigurasi database pada file `.env`.

7. Jalankan migrasi dan seeder:

   ```bash
   php artisan migrate --seed
   ```

8. Instal dependensi frontend:

   ```bash
   npm install
   ```

9. Jalankan aplikasi:

   ```bash
   npm run build
   php artisan serve
   ```

10. Buka `http://127.0.0.1:8000` di browser.

## Daftar Fitur

- Dashboard informasi bengkel.
- CRUD data pelanggan.
- CRUD data kendaraan beserta pemiliknya.
- CRUD transaksi servis.
- Relasi pelanggan, kendaraan, dan transaksi servis.
- Pencarian data pelanggan, kendaraan, dan servis.
- Informasi status servis dan biaya servis.

## Screenshot Aplikasi

### 1. Dashboard

![Dashboard](screenshots/dashboard.png)

### 2. Halaman Pelanggan

![Halaman Pelanggan](screenshots/pelanggan.png)

### 3. Halaman Kendaraan

![Halaman Kendaraan](screenshots/kendaraan.png)

### 4. Halaman Servis

![Halaman Servis](screenshots/servis.png)

## Repository

URL repository: `https://github.com/Divasyahita/pweb2-cpmk2-H1H024015-paket5.git`

## Video Demonstrasi

URL YouTube: `URL_VIDEO_YOUTUBE`
