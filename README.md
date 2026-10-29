# Aplikasi Manajemen Proyek dan Task

Aplikasi berbasis Laravel 13 untuk mengelola proyek, task, status task, dan riwayat aktivitas pengguna.

## Teknologi

- PHP
- Laravel 13
- MySQL
- Composer
- Node.js dan NPM (untuk aset frontend jika digunakan oleh project)

## Persyaratan

Pastikan perangkat sudah memiliki:
- PHP dengan ekstensi yang dibutuhkan Laravel 13
- Composer
- MySQL
- Node.js dan NPM
- Git (jika mengunduh project melalui Git)

Periksa versi PHP yang disyaratkan oleh `composer.json` project ini sebelum instalasi.

## 1. Instalasi

Clone repository, lalu masuk ke folder project:

```bash
git clone <URL_REPOSITORY>
cd <NAMA_FOLDER_PROJECT>
```

Install dependency PHP:

```bash
composer install
```

Buat file environment dari contoh yang tersedia.

**Windows (Command Prompt):**
```bat
copy .env.example .env
```

**macOS/Linux/Git Bash:**
```bash
cp .env.example .env
```

Buat application key:

```bash
php artisan key:generate
```

## 2. Konfigurasi MySQL

1. Jalankan MySQL melalui aplikasi yang digunakan (misalnya MySQL Server, XAMPP, atau Laragon).
2. Buat database baru, misalnya `kopnus_test`.
3. Buka file `.env`, lalu sesuaikan konfigurasi database:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kopnus_test
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan konfigurasi MySQL lokal. Contoh di atas mengasumsikan MySQL lokal menggunakan user `root` tanpa password; ubah jika konfigurasi perangkat berbeda.

Jangan unggah file `.env` yang berisi kredensial ke repository.

## 3. Menyiapkan database

Pilih salah satu cara berikut, sesuai file yang tersedia di repository.

### Opsi A — Membuat tabel melalui migration (umumnya disarankan)

Jika repository menyertakan migration Laravel dan tidak ada file dump SQL yang harus diimpor, jalankan:

```bash
php artisan migrate --seed
```

Perintah tersebut menjalankan migration dan seeder yang terdaftar di project. Pastikan `DatabaseSeeder` memanggil seeder pengguna yang diperlukan.

Jika hanya ingin menjalankan migration tanpa data contoh:

```bash
php artisan migrate
```

> **Perhatian:** `php artisan migrate:fresh --seed` menghapus semua tabel terlebih dahulu, sehingga seluruh data database akan hilang. Jalankan hanya pada database pengembangan yang memang boleh di-reset.

### Opsi B — Import file database `.sql`

Jika repository menyediakan file dump database, misalnya `database.sql`:

**Melalui phpMyAdmin**
1. Buka phpMyAdmin.
2. Buat atau pilih database yang sesuai dengan `DB_DATABASE` di file `.env`.
3. Buka tab **Import**.
4. Pilih file `.sql`, lalu jalankan proses import.
5. Pastikan tabel-tabel berhasil dibuat.

**Melalui terminal MySQL**

```bash
mysql -u root -p kopnus_test < database.sql
```

Ganti `root`, `kopnus_test`, dan `database.sql` sesuai konfigurasi serta lokasi file. Jika MySQL tidak menggunakan password, ikuti konfigurasi lokal yang berlaku.

Setelah import, pastikan konfigurasi `.env` menunjuk ke database yang benar. Jangan menjalankan `migrate:fresh` setelah import jika ingin mempertahankan data hasil import.

## 4. Menyiapkan akun contoh

Seeder project menyediakan akun contoh berikut:

| Nama | Email |
|---|---|
| Admin | `admin@example.com` |
| Novita | `novita@example.com` |
| Budi | `budi@example.com` |

**Password:** gunakan password yang ditetapkan di seeder project (`DatabaseSeeder` atau seeder pengguna terkait). Pastikan password yang benar dicatat di sini sebelum README dipublikasikan; jangan menebak password berdasarkan email.

Jika akun belum dibuat, jalankan seeder:

```bash
php artisan db:seed
```

Atau jalankan migration sekaligus seeder pada database baru:

```bash
php artisan migrate --seed
```

Pastikan seeder mengisi password yang di-hash dan role yang sesuai dengan aturan aplikasi. Gunakan hanya akun/password demo untuk lingkungan pengembangan.

## 5. Menjalankan aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Buka alamat yang ditampilkan di terminal, biasanya:

http://127.0.0.1:8000

Login menggunakan salah satu email contoh dan password yang sudah dikonfigurasi pada seeder.

## 6. Fitur utama

- Login dan autentikasi pengguna.
- Pengelolaan proyek milik pengguna.
- Pembuatan task dalam proyek.
- Perubahan status task sesuai alur yang diterapkan aplikasi.
- Riwayat aktivitas pengguna.
- Filter riwayat aktivitas berdasarkan aksi dan rentang tanggal.

Fitur yang tersedia mengikuti implementasi pada repository.

## 7. Troubleshooting

### Error koneksi database
- Pastikan layanan MySQL sedang berjalan.
- Periksa `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`.
- Pastikan database sudah dibuat.

### Tabel `sessions` tidak ditemukan
Periksa nilai `SESSION_DRIVER` di `.env`. Jika menggunakan driver `database`, pastikan migration tabel sessions tersedia dan sudah dijalankan:

```bash
php artisan migrate
```

### Perubahan `.env` tidak terbaca
Bersihkan cache konfigurasi:

```bash
php artisan config:clear
php artisan cache:clear
```

### Akun contoh tidak bisa login
- Pastikan seeder pengguna sudah dijalankan.
- Periksa password dan role yang ditetapkan di seeder.
- Pastikan password disimpan menggunakan hashing Laravel, misalnya `Hash::make(...)`.

## Catatan

- Jangan commit `.env`, kredensial database, atau password pribadi ke Git.
- Untuk instalasi baru, gunakan migration dan seeder yang disediakan project.
- Jika menggunakan file SQL hasil export, simpan file tersebut dengan nama dan lokasi yang jelas, lalu sesuaikan instruksi import di atas.
