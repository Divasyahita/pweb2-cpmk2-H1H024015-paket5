# JAWABAN TUGAS PEMWEB II — CPMK 2

## A. Identitas Proyek

- **Nama:** Diva Syahita Mawarni
- **NIM:** H1H024015
- **Paket:** 5
- **Judul:** Sistem Informasi Bengkel Servis Kendaraan

## B. Jawaban Pertanyaan Konsep

### 1. Jelaskan relasi `hasManyThrough` dari Customer ke Service melalui Vehicle dan tuliskan kodenya.

Relasi `hasManyThrough` digunakan ketika suatu model ingin mengakses data yang berhubungan melalui model perantara. Pada sistem bengkel ini, satu pelanggan dapat memiliki banyak kendaraan, dan setiap kendaraan dapat memiliki banyak transaksi servis. Dengan demikian, pelanggan dapat mengakses seluruh riwayat servis melalui kendaraan yang dimilikinya.

Kode pada model `Customer`:

```php
public function vehicles()
{
    return $this->hasMany(Vehicle::class);
}

public function services()
{
    return $this->hasManyThrough(
        Service::class,
        Vehicle::class
    );
}
```

Relasi tersebut memungkinkan pengambilan seluruh transaksi servis milik pelanggan tanpa harus mengambil data kendaraan satu per satu secara manual.

### 2. Apa itu masalah N+1 query? Tunjukkan perbedaan jumlah query di tabel servis dengan dan tanpa `with(['vehicle.customer'])`.

Masalah N+1 query terjadi ketika aplikasi menjalankan satu query untuk mengambil data utama, kemudian menjalankan query tambahan berulang kali untuk mengambil relasi dari setiap data tersebut. Hal ini dapat memperlambat aplikasi ketika jumlah data semakin banyak.

Contohnya, ketika menampilkan daftar servis beserta kendaraan dan nama pelanggannya, tanpa eager loading aplikasi dapat menjalankan query berulang kali untuk mengambil relasi setiap servis.

Dengan eager loading:

```php
Service::with(['vehicle.customer'])->get();
```

Laravel mengambil data servis beserta relasinya secara lebih efisien. Pada kondisi relasi standar dan tanpa query tambahan lainnya, perbandingannya adalah:

- **Tanpa eager loading:** jumlah query dapat bertambah sesuai jumlah servis yang ditampilkan. Jika ada N servis dan setiap baris memicu dua query relasi, jumlahnya dapat mencapai `1 + 2N`.
- **Dengan eager loading:** umumnya cukup 3 query, yaitu untuk servis, kendaraan, dan pelanggan.

Jumlah sebenarnya bergantung pada pola akses relasi, jumlah data, dan query tambahan yang dijalankan aplikasi. Eager loading membantu mengurangi query berulang dan meningkatkan efisiensi pengambilan data.

### 3. Apa fungsi lifecycle hook `updated<NamaProperti>` di Livewire?

Lifecycle hook `updated<NamaProperti>` merupakan metode yang dijalankan setelah properti tertentu pada komponen Livewire diperbarui. Hook ini dapat digunakan untuk menjalankan logika tambahan ketika nilai input berubah.

Contohnya, `updatedCustomerId()` dapat digunakan untuk mereset pilihan kendaraan ketika pelanggan yang dipilih berubah.

```php
public function updatedCustomerId()
{
    $this->vehicle_id = '';
}
```

Pada form servis, mekanisme tersebut membantu memastikan pilihan kendaraan diperbarui sesuai pelanggan yang dipilih. Selain itu, validasi pada server tetap diperlukan agar kendaraan yang dikirim benar-benar milik pelanggan terkait.

## D. Link Repository GitHub

[https://github.com/Divasyahita/pweb2-cpmk2-H1H024015-paket5.git](https://github.com/Divasyahita/pweb2-cpmk2-H1H024015-paket5.git)

## E. Link Video Demonstrasi 

[https://drive.google.com/file/d/1SECoy8ujq478CZ0So3hWaOHUITGGAkKa/view?usp=drivesdk](https://drive.google.com/file/d/1SECoy8ujq478CZ0So3hWaOHUITGGAkKa/view?usp=drivesdk)

