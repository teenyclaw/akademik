# Deploy ke Shared Hosting (cPanel) — SIA Akademik

## Persyaratan PHP

- **Minimum PHP 8.3** (disarankan **8.3.x** di cPanel → Select PHP Version)
- Ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`

---

## Error: `require PHP >= 8.3.0` di browser, tapi `php -v` di terminal sudah 8.4

**Gejala:** Terminal SSH/cPanel menampilkan `PHP 8.4.21`, tetapi buka `https://domain.com` muncul:

```
Composer detected issues in your platform: Your Composer dependencies require a PHP version ">= 8.3.0".
```

**Penyebab:** Di cPanel, **PHP CLI** (terminal) dan **PHP Web** (Apache/LiteSpeed untuk domain) **bisa berbeda versi**. Artisan jalan karena CLI = 8.4; website error karena Web masih **8.2 / 8.1 / 7.x**.

**Solusi (cPanel):**

1. Login **cPanel** → **MultiPHP Manager** (atau **Select PHP Version**)
2. Pilih domain **`akademik.ylabsdev1980.com`**
3. Set PHP ke **8.3** atau **8.4** (ea-php83 / ea-php84)
4. Simpan, tunggu 1–2 menit
5. Cek via browser: `https://akademik.ylabsdev1980.com/check-php.php`  
   Harus tampil `PHP 8.3.x` atau `8.4.x` dan `[OK] PHP version >= 8.3`
6. **Hapus** `check-php.php` setelah selesai

**Alternatif otomatis:** repo sudah menyertakan handler di `public/.htaccess`:

```apache
AddHandler application/x-httpd-ea-php83 .php
```

Setelah `git pull`, pastikan file ini ter-update. Jika host punya PHP 8.4 saja, ganti `ea-php83` → `ea-php84` di `.htaccess`.

**Alternatif manual (jika subdomain pakai folder terpisah):**

Edit `.htaccess` di folder **`public/`** (atau document root), tambahkan **di baris paling atas**:

```apache
# cPanel — paksa PHP 8.4 (sesuaikan dengan versi yang tersedia di host)
AddHandler application/x-httpd-ea-php84 .php
```

Versi handler di host Anda bisa `ea-php83`, `ea-php84`, dll. — cek di cPanel → **MultiPHP Manager**.

**Verifikasi cepat di terminal:**

```bash
# CLI (sudah 8.4 — ini BUKKAN yang dipakai browser)
php -v

# Simulasi versi web — buka check-php.php di browser, BUKAN di terminal
```

---

**Penyebab:** `vendor/` di-build dengan PHP 8.4 + paket Symfony 8 / activitylog v5.

**Solusi:**
1. Di Laragon jalankan ulang: `composer install --no-dev --optimize-autoloader` (proyek sudah dikunci PHP **8.3**)
2. Upload ulang folder **`vendor/`** + **`composer.lock`** ke hosting
3. **Atau** upgrade PHP hosting ke **8.4** via cPanel jika tersedia

**Jangan** jalankan `composer install` di hosting jika `proc_open` disabled — upload `vendor/` dari lokal.

---

## Error: `proc_open is not available`

```
In Process.php line 147:
The Process class relies on proc_open, which is not available on your PHP installation.
```

**Penyebab:** Hosting shared (cPanel) sering menonaktifkan `proc_open`, `exec`, `shell_exec` di `disable_functions`. **Composer** dan beberapa perintah Artisan membutuhkan `proc_open`.

**Solusi:** Jangan jalankan `composer install` di server. Install di komputer lokal (Laragon), lalu upload folder `vendor/`.

---

## Metode A — Upload vendor dari lokal (disarankan)

### Di komputer (Laragon)

```bash
cd C:\laragon\www\akademik
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

Pastikan ada folder:
- `vendor/` (semua dependensi PHP)
- `public/build/` (CSS/JS hasil Vite)

### Upload ke hosting (FTP / File Manager)

Upload **seluruh proyek** ke `/home/user/akademik/` (atau folder di luar `public_html`):

| Upload | Catatan |
|--------|---------|
| `app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `storage/` | Wajib |
| `vendor/` | **Wajib** — dari lokal, jangan composer di server |
| `public/build/` | **Wajib** — hasil `npm run build` |
| `artisan`, `composer.json`, `composer.lock` | Wajib |
| `.env` | Buat manual di server (jangan commit) |
| **Jangan upload** | `node_modules/`, `.git/`, `.env` lokal |

### Document root

Set di cPanel → **Domains** → Document Root:

```
/home/user/akademik/public
```

Bukan root folder `akademik/`.

### Di server (cPanel Terminal — perintah yang AMAN tanpa proc_open)

```bash
cd ~/akademik
cp .env.example .env
# Edit .env via File Manager (DB, APP_URL, APP_DEBUG=false)

php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Jangan jalankan** `composer install` di server jika `proc_open` disabled.

---

## Metode B — Aktifkan proc_open (jika host mengizinkan)

1. cPanel → **Select PHP Version** atau **MultiPHP INI Editor**
2. Pilih PHP **8.3**
3. Cari `disable_functions` — hapus `proc_open` (dan `exec` jika perlu)
4. Simpan, tunggu beberapa menit
5. Cek via script `public/check-php.php` (lihat bawah)

Jika opsi ini tidak tersedia (hosting murah), gunakan **Metode A**.

---

## Cek PHP di hosting

Upload file `public/check-php.php` (sementara), buka `https://domain.com/check-php.php`, lalu **hapus file** setelah cek.

Harus menampilkan:
- PHP >= 8.3
- `proc_open`: enabled (opsional jika pakai Metode A)
- Ekstensi: pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, bcmath, fileinfo, gd

---

## Error: HTTP 500 Server Error (setelah PHP sudah 8.3+)

**Gejala:** Halaman utama menampilkan `500 | Server Error` (Laravel generic error).

**Penyebab paling umum di shared hosting:**

| # | Penyebab | Cek |
|---|----------|-----|
| 1 | **APP_KEY kosong** | `.env` harus punya `APP_KEY=base64:...` → `php artisan key:generate` |
| 2 | **Database salah** | `.env` masih `DB_CONNECTION=sqlite` atau kredensial MySQL cPanel salah |
| 3 | **Belum migrate** | Tabel `sessions`, `cache`, `users` belum ada → `php artisan migrate --force` |
| 4 | **Config cache stale** | `.env` diubah setelah `config:cache` → `php artisan config:clear` |
| 5 | **Permission storage** | `storage/` dan `bootstrap/cache/` tidak writable → `chmod -R 775 storage bootstrap/cache` |

**Diagnostik:** cek `storage/logs/laravel.log` via terminal (`tail -30 storage/logs/laravel.log`) atau aktifkan sementara `APP_DEBUG=true` di `.env` untuk melihat error detail di browser.

**Perintah perbaikan cepat (terminal hosting):**

```bash
cd ~/akademik
php artisan config:clear
php artisan key:generate   # jika APP_KEY kosong
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
tail -30 storage/logs/laravel.log
```

**`.env` production wajib MySQL** (bukan sqlite dari `.env.example`):

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=cpanel_prefix_namadb
DB_USERNAME=cpanel_prefix_user
DB_PASSWORD=password_dari_cPanel
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
```

---

## .env Production (contoh)

```env
APP_NAME="SIA Akademik"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainanda.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=cpanel_user_db
DB_USERNAME=cpanel_user_db
DB_PASSWORD=***

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
```

Gunakan `QUEUE_CONNECTION=sync` di shared hosting (tanpa worker/cron queue).

---

## Permission

```bash
chmod -R 775 storage bootstrap/cache
```

Via File Manager: folder `storage` dan `bootstrap/cache` → permission **775**.

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| `proc_open` error | Metode A: upload `vendor/` dari lokal |
| Halaman tanpa CSS | Upload `public/build/` dari `npm run build` lokal |
| 403/404 | Document root = `public/` |
| 500 error | Baca `storage/logs/laravel.log`; cek APP_KEY, DB, migrate |
| 500 + timeout | Biasanya DB salah / belum migrate — cek `.env` DB_* lalu `php artisan migrate --force` |
| Config stale | `php artisan config:clear` lalu edit `.env`, baru `php artisan config:cache` |
| Login error DB | Cek `.env` DB_* di cPanel MySQL |
| `storage:link` gagal | Buat symlink manual di cPanel atau copy `storage/app/public` |

---

## Update aplikasi

**Lokal:**
```bash
git pull
composer install --no-dev --optimize-autoloader
npm run build
```

**Upload** folder yang berubah: `app/`, `vendor/` (jika composer update), `public/build/`, `database/migrations/` baru.

**Server:**
```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
