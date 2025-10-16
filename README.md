# Dokumentasi Setup Proyek Laravel: Sistem Distribusi Produk

Dokumen ini berisi panduan langkah demi langkah untuk melakukan *setup* dan menjalankan proyek Sistem Distribusi Produk berbasis Laravel, AJAX, dan DataTables *Server-side*.

## Persyaratan Sistem

Pastikan sistem Anda telah menginstal komponen berikut:

1.  **PHP:** Versi 8.1 atau yang kompatibel.
2.  **Composer:** Manajer paket PHP.
3.  **Database Server:** MySQL/MariaDB.
4.  **Node.js & npm:** (Diperlukan jika Anda ingin melakukan *build frontend assets*).

***

## I Langkah-langkah Setup Proyek

Buka terminal Anda dan navigasikan ke dalam direktori proyek yang sudah Anda *clone*.

### 1. Instalasi Dependensi

Instal semua paket PHP yang dibutuhkan yang didefinisikan dalam `composer.json`.

```bash
composer install
```

### 2. Generate key
```bash
php artisan key:generate
```
## II. Konfigurasi File `.env` Secara Detail

File `.env` adalah pusat konfigurasi aplikasi Anda. Pastikan Anda mengatur setiap bagian dengan benar.

### 1. Konfigurasi Database (Wajib)

| Key | Nilai yang Disarankan | Keterangan |
| :--- | :--- | :--- |
| `DB_CONNECTION` | `mysql` | Jenis database yang digunakan. |
| `DB_HOST` | `127.0.0.1` | Host database lokal Anda. |
| `DB_PORT` | `3306` | Port default MySQL/MariaDB. |
| `DB_DATABASE` | `test_clone` | **GANTI** dengan nama database yang Anda buat. |
| `DB_USERNAME` | `root` | **GANTI** dengan *username* database Anda. |
| `DB_PASSWORD` | *(kosong/password Anda)* | **GANTI** dengan *password* database Anda. |

### 2. Konfigurasi Sesi dan Cache (Penting)

| Key | Nilai yang Disarankan | Keterangan |
| :--- | :--- | :--- |
| `SESSION_DRIVER`| **`file`** | **PENTING:** Menggunakan *file system* untuk sesi. Ini mencegah *error* `sessions table not found` saat *booting*. |
| `CACHE_DRIVER` | `file` | Menggunakan *file system* untuk *caching*. |
| `QUEUE_CONNECTION` | `sync` | Antrian diproses secara sinkron (langsung). |

***

### 3. Jalankan migrate
```bash
php artisan migrate:fresh --seed
```

# Jalankan aplikasi
```bash
php artisan serve
```
