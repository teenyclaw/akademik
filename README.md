# SIA Akademik — Sistem Informasi Akademik Multi-Instansi

Platform web multi-sekolah untuk SD, SMP, SMA, SMK, Madrasah, Pesantren, Bimbingan Belajar, dan lembaga pendidikan lainnya.

## Teknologi

- Laravel 13 + Blade + Tailwind CSS + Alpine.js
- MySQL
- Laravel Breeze (autentikasi)
- Spatie Laravel Permission & Activity Log
- Laravel Sanctum (REST API)
- DomPDF & Maatwebsite Excel (export)

## Instalasi (Laragon)

**Persyaratan Node.js:** versi **18.x atau 20.x** (Laragon: Menu → Node.js → pilih v20 LTS jika tersedia).

```bash
composer install
cp .env.example .env
php artisan key:generate
# Atur DB_DATABASE=akademik di .env
php artisan migrate:fresh --seed
php artisan storage:link   # abaikan jika symlink sudah ada
npm install && npm run build
```

Akses via: `http://akademik.test` (atau `php artisan serve`)

## Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Super Admin | superadmin@akademik.test | password |
| Admin SD | admin@sd-negeri-1-jakarta.test | password |
| Admin SMA | admin@sma-negeri-1-jakarta.test | password |
| Guru SD | guru@sd-negeri-1-jakarta.test | password |
| Siswa SD | siswa@sd-negeri-1-jakarta.test | password |
| Orang Tua SD | ortu@sd-negeri-1-jakarta.test | password |

## Modul

Dashboard, Tahun Ajaran, Kelas, Siswa, Guru, Orang Tua, Mata Pelajaran, Jadwal, Absensi, Penilaian, Rapor (PDF), Pengumuman, Pembayaran, E-Learning, Laporan (PDF/Excel), Pengaturan Sekolah, Manajemen User & Role.

## API

Prefix: `/api/v1/` — autentikasi via Sanctum token.

```
POST /login (web) → buat token via Sanctum
GET /api/v1/students
GET /api/v1/grades
GET /api/v1/attendances
GET /api/v1/class-schedules
GET /api/v1/announcements
```

## Dokumentasi

Lihat folder [`docs/`](docs/) untuk analisis kebutuhan, flow bisnis, struktur menu, ERD, dan wireframe dashboard.

## Arsitektur

```
Controller → Service → Repository → Model
```

Multi-tenant via `school_id` + global scope `BelongsToSchool`.
