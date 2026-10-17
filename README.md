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

<img width="959" height="475" alt="Screenshot 2026-10-16 055754" src="https://github.com/user-attachments/assets/4edf451e-8666-4368-a055-039e24990e11" />
<img width="959" height="477" alt="image" src="https://github.com/user-attachments/assets/e29db285-28bf-476c-895f-a0de15212bd7" />


### 2. Halaman Pelanggan

<img width="959" height="475" alt="image" src="https://github.com/user-attachments/assets/4899ba68-42ab-4bf4-951a-76d6d6ba43f2" />
<img width="959" height="479" alt="image" src="https://github.com/user-attachments/assets/8c4a9c3e-20a2-4511-a0ec-fa4680c900b8" />


### 3. Halaman Kendaraan
<img width="959" height="474" alt="image" src="https://github.com/user-attachments/assets/e615887b-1919-448e-9997-eadc45cc6f33" />
<img width="959" height="473" alt="image" src="https://github.com/user-attachments/assets/135ab2ab-ab1d-464d-9b9c-640cb5dd02fa" />

### 4. Halaman Servis
<img width="959" height="476" alt="image" src="https://github.com/user-attachments/assets/cc49cc05-b790-4ab6-ae77-3dadf3961b54" />
<img width="959" height="473" alt="image" src="https://github.com/user-attachments/assets/71e3ce49-5014-41ed-a62b-ed1bc71cd0be" />

## Status Pengerjaan Tugas

| Tantangan | Ketentuan | Status |
|---|---|---|
| T1 | Plat nomor otomatis diubah menjadi huruf kapital, dirapikan spasinya, dan divalidasi menggunakan regex format plat nomor Indonesia. | Selesai |
| T2 | Dropdown kendaraan pada form servis bergantung pada pelanggan yang dipilih. Pilihan kendaraan direset saat pelanggan berubah, dan kepemilikan kendaraan divalidasi di server. | Selesai |
| T3 | Total biaya servis dihitung menggunakan accessor. Status servis hanya dapat maju satu tahap secara berurutan melalui tombol aksi, dan tabel dapat diurutkan berdasarkan tanggal serta total biaya secara naik/turun. | Selesai |

## Repository

URL repository: [https://github.com/Divasyahita/pweb2-cpmk2-H1H024015-paket5.git](https://github.com/Divasyahita/pweb2-cpmk2-H1H024015-paket5.git)

## Video Demonstrasi

URL YouTube: [https://youtu.be/QxHXt3_YZPw?si=fu_HZUmrgCEPSXBt](https://youtu.be/QxHXt3_YZPw?si=fu_HZUmrgCEPSXBt)
