<div align="center">

# eSarana

**Sistem Manajemen Sarana & Prasarana**

Aplikasi web mobile-first untuk mengelola inventaris aset, peminjaman, laporan kerusakan, dan pengadaan bahan habis pakai secara terpadu.

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-4.2-FB70A9?style=flat-square&logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.0-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

</div>

---

## Tentang eSarana

eSarana adalah aplikasi manajemen sarana prasarana berbasis web yang didesain mobile-first. Dibangun di atas Laravel 13 + Livewire 4 dengan antarmuka reaktif yang mulus tanpa reload halaman penuh.

### Fitur Utama

| Modul | Deskripsi |
|-------|-----------|
| **Dashboard** | Ringkasan nilai aset, statistik peminjaman, aktivitas terbaru, laporan masuk |
| **Inventaris** | CRUD aset tetap dengan upload foto, detail lengkap, dan riwayat peminjaman |
| **Peminjaman** | Ajukan, pantau, dan kembalikan aset dengan konfirmasi & notifikasi terlambat |
| **Laporan** | Laporkan kerusakan atau permintaan — bisa oleh pengguna anonim via link publik |
| **Bahan Habis Pakai** | Monitoring stok dengan peringatan otomatis saat stok kritis |
| **Lokasi & Ruangan** | Manajemen gedung, lantai, dan ruangan bertingkat |
| **Pencarian Global** | Cari aset, bahan, dan laporan dari satu kotak pencarian |
| **Master Data** | Kelola kategori, pengguna, dan role permission (khusus administrator) |

---

## Tech Stack

- **Backend:** Laravel 13 (PHP 8.3)
- **Frontend:** Livewire 4.2 + Alpine.js (reaktif tanpa SPA)
- **Styling:** Tailwind CSS 4.0 (mobile-first)
- **Database:** SQLite (default) / MySQL / PostgreSQL
- **Auth & Permission:** Laravel Auth + Spatie Laravel Permission v7
- **Build Tool:** Vite

---

## Persyaratan Sistem

- PHP **8.3** atau lebih tinggi
- Composer **2.x**
- Node.js **18+** dan NPM
- Ekstensi PHP: `pdo`, `pdo_sqlite` (atau `pdo_mysql`), `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `fileinfo`, `gd` (untuk upload gambar)

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/eldorray/esarana-mobile.git
cd esarana-mobile
```

### 2. Install Dependensi

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Buka `.env` dan sesuaikan konfigurasi database:

```env
# Untuk SQLite (default, tidak perlu konfigurasi tambahan)
DB_CONNECTION=sqlite

# Atau untuk MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=esarana
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Setup Database

```bash
# Buat file database SQLite (jika pakai SQLite)
touch database/database.sqlite

# Jalankan migrasi dan seeder
php artisan migrate --seed
```

### 5. Storage & Build

```bash
# Buat symlink untuk file upload
php artisan storage:link

# Build asset frontend
npm run build
```

### 6. Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi di **http://localhost:8000**

> **Tips:** Gunakan `composer dev` untuk menjalankan semua layanan sekaligus (server, vite, queue, log) dengan satu perintah.

---

## Akun Default

Setelah `php artisan migrate --seed`, tersedia akun berikut:

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| **Administrator** | `admin@esarana.id` | `password` | Semua fitur + master data |
| **Supervisor** | `budi@esarana.id` | `password` | Kelola aset, lokasi, laporan, approve |
| **Staff** | `siti@esarana.id` | `password` | Inventaris, pinjam, lapor (data sendiri) |
| **Staff** | `andi@esarana.id` | `password` | Inventaris, pinjam, lapor (data sendiri) |

---

## Panduan Penggunaan

### Login

Buka `http://localhost:8000/login`, masukkan email dan password dari tabel akun di atas.

---

### Dashboard

Setelah login, kamu akan melihat halaman utama yang menampilkan:

- **Total Nilai Aset** — akumulasi nilai seluruh inventaris
- **Statistik cepat** — jumlah aset, pinjaman aktif, permintaan baru
- **Alert stok kritis** — peringatan otomatis jika stok bahan di bawah minimum
- **Aktivitas Terbaru** — klik tiap item untuk langsung ke detail inventaris
- **Laporan Masuk** — klik tiap laporan untuk melihat detail & ubah status

---

### Inventaris

#### Melihat Daftar Aset
1. Buka menu **Inventaris** di navigasi bawah
2. Gunakan tab **Aset Tetap** atau **Bahan Habis Pakai**
3. Ketik di kotak pencarian untuk filter real-time
4. Klik kartu aset untuk melihat halaman **detail**

#### Menambah Aset Baru
1. Tap tombol **+** (FAB) atau **Aset Baru** di dashboard
2. **Langkah 1:** Isi nama, kode inventaris, deskripsi, lokasi penyimpanan, dan foto (opsional)
3. **Langkah 2:** Pilih kategori, isi serial number, kondisi, nilai aset, tanggal perolehan
4. Klik **Simpan Data**

#### Mengedit Aset
1. Buka halaman detail aset
2. Scroll ke bawah dan klik **Edit Data Inventaris**
3. Ubah field yang diinginkan — termasuk ganti foto
4. Klik **Simpan Perubahan**

---

### Peminjaman

#### Mengajukan Peminjaman
1. Buka menu **Pinjaman** di navigasi bawah
2. Klik **Ajukan Peminjaman**
3. Pilih aset yang tersedia, tentukan tanggal pinjam dan rencana kembali
4. Isi catatan keperluan, lalu klik **Ajukan Peminjaman**

> Hanya aset dengan status **Tersedia** yang bisa dipilih.

#### Mengembalikan Aset
1. Buka menu **Pinjaman**
2. Pada kartu peminjaman aktif, klik tombol **Kembalikan Sekarang**
3. Konfirmasi di dialog yang muncul
4. Aset otomatis kembali ke status **Tersedia**

> Peminjaman yang melewati tanggal jatuh tempo ditandai oranye dengan keterangan berapa lama terlambat.

---

### Laporan

#### Membuat Laporan (Login)
1. Buka menu **Laporan** atau klik **Lapor** di dashboard
2. Pilih tipe: **Kerusakan** atau **Permintaan**
3. Isi nama aset/lokasi, deskripsi masalah, dan foto bukti (opsional)
4. Klik **Kirim Laporan**

#### Laporan Publik (Tanpa Login)
Bagikan link **`http://localhost:8000/lapor`** kepada siapa pun (tamu, vendor, dll.) untuk melaporkan masalah tanpa perlu akun.

#### Mengelola Status Laporan (Supervisor / Admin)
1. Buka detail laporan
2. Gunakan tombol aksi di bagian bawah:
   - **Mulai Proses** — ubah status dari *Baru* ke *Sedang Diproses*
   - **Tandai Selesai** — ubah status dari *Proses* ke *Selesai*
   - **Tolak** — tolak laporan yang tidak valid

---

### Pencarian Global

Klik ikon 🔍 di pojok kanan atas aplikasi untuk membuka halaman pencarian.

- Ketik minimal **2 karakter** untuk mulai mencari
- Hasil dibagi per kategori: **Aset Inventaris**, **Bahan Habis Pakai**, dan **Laporan**
- Klik hasil pencarian untuk langsung ke halaman detail

---

### Master Data (Administrator)

Akses menu **Lainnya** di navigasi bawah (hanya muncul untuk role administrator).

| Sub-menu | Fungsi |
|----------|--------|
| **Kategori** | Tambah, edit, nonaktifkan kategori aset |
| **Pengguna** | CRUD akun pengguna, ganti password, assign role |
| **Role & Permission** | Atur hak akses tiap role |

---

## Role & Permission

| Permission | Administrator | Supervisor | Staff |
|------------|:---:|:---:|:---:|
| `view_dashboard` | ✅ | ✅ | ✅ |
| `manage_inventaris` | ✅ | ✅ | ✅ |
| `manage_peminjaman` | ✅ | ✅ | ✅ |
| `approve_peminjaman` | ✅ | ✅ | — |
| `manage_laporan` | ✅ | ✅ | ✅ |
| `manage_bahan` | ✅ | ✅ | ✅ |
| `manage_lokasi` | ✅ | ✅ | — |
| `manage_ruangan` | ✅ | ✅ | — |
| `export_laporan` | ✅ | ✅ | — |
| `manage_kategori` | ✅ | — | — |
| `manage_users` | ✅ | — | — |
| `manage_roles` | ✅ | — | — |

---

## Struktur Direktori

```
app/
├── Livewire/
│   ├── Auth/           # Login, Logout
│   ├── Inventaris/     # Index, Create, Edit, Show
│   ├── Peminjaman/     # Index, Create
│   ├── Laporan/        # Index, Create, Show, Publik
│   ├── BahanHabisPakai/
│   ├── Lokasi/         # Index, Create, Show
│   ├── MasterData/     # Kategori, Users, Roles
│   ├── Cari.php        # Pencarian global
│   └── Dashboard.php
├── Models/             # Eloquent models
resources/
├── views/
│   ├── components/
│   │   └── layouts/    # app.blade.php (layout utama)
│   └── livewire/       # Blade views per modul
routes/
└── web.php             # Semua route
database/
├── migrations/         # Skema database
└── seeders/            # Data awal (users, aset, laporan)
```

---

## Perintah Berguna

```bash
# Jalankan semua service development sekaligus
composer dev

# Reset database dan seed ulang
php artisan migrate:fresh --seed

# Bersihkan cache
php artisan cache:clear && php artisan view:clear

# Jalankan tests
composer test
```

---

## Kontribusi

Pull request dan issue sangat disambut. Untuk perubahan besar, buka issue terlebih dahulu untuk mendiskusikan perubahan yang diinginkan.

1. Fork repository ini
2. Buat branch fitur: `git checkout -b fitur/nama-fitur`
3. Commit perubahan: `git commit -m 'Tambah fitur X'`
4. Push ke branch: `git push origin fitur/nama-fitur`
5. Buka Pull Request

---

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

<div align="center">
  Dibangun dengan Laravel 13 + Livewire 4 + Tailwind CSS
</div>
