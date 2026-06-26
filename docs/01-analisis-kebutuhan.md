# Analisis Kebutuhan Sistem — SIA Multi-Instansi

## 1. Latar Belakang

Sistem Informasi Akademik (SIA) dirancang sebagai platform web multi-instansi yang dapat digunakan oleh berbagai jenjang pendidikan: SD, SMP, SMA, SMK, Madrasah, Pesantren, Bimbingan Belajar, dan lembaga pendidikan lainnya. Setiap instansi (sekolah) memiliki data terpisah dan dapat melakukan kustomisasi tanpa mengubah source code.

## 2. Aktor Sistem

| No | Aktor | Deskripsi |
|----|-------|-----------|
| 1 | Super Admin | Mengelola platform, sekolah, dan user global |
| 2 | Admin Sekolah | Mengelola seluruh data akademik sekolah |
| 3 | Kepala Sekolah | Monitoring, laporan, persetujuan |
| 4 | Wali Kelas | Kelola kelas, absensi, nilai, catatan rapor |
| 5 | Guru | Jadwal, absensi, penilaian, e-learning |
| 6 | Siswa | Lihat jadwal, nilai, absensi, pengumuman |
| 7 | Orang Tua | Monitoring anak (nilai, absensi, pembayaran) |
| 8 | Tata Usaha | Administrasi data, pembayaran, laporan |

## 3. Kebutuhan Fungsional

### 3.1 Multi-Sekolah
- CRUD data sekolah (nama, logo, alamat, kontak, website)
- Tahun ajaran aktif per sekolah
- Kurikulum dan kop laporan per sekolah
- Isolasi data antar sekolah via `school_id`

### 3.2 Kustomisasi Tanpa Source Code
- Nama sekolah, logo, jenjang pendidikan
- Tahun ajaran, kurikulum, mata pelajaran
- Struktur kelas (tingkat, jurusan)
- Format nilai dan predikat
- Kop rapor dan tanda tangan digital

### 3.3 Modul Akademik
| Modul | Fitur Utama |
|-------|-------------|
| Dashboard | Statistik siswa/guru/kelas, absensi, grafik nilai, pengumuman |
| Tahun Ajaran | TA, semester, status aktif |
| Kelas | Tingkat, jurusan, wali kelas, kapasitas |
| Siswa | NIS, NISN, biodata, orang tua, riwayat, foto |
| Guru | NIP, data, mapel, jadwal, riwayat |
| Mata Pelajaran | Kode, nama, kelompok, guru pengampu |
| Jadwal | Hari, jam, kelas, guru, mapel, ruangan |
| Absensi | H/S/I/A, rekap harian/bulanan |
| Penilaian | Tugas, ulangan, UTS, UAS, nilai akhir, predikat |
| Rapor | Cetak PDF semester, catatan wali kelas, tanda tangan |
| Pengumuman | Sekolah, kegiatan, agenda akademik |
| Pembayaran | SPP, daftar ulang, biaya kegiatan, riwayat |
| E-Learning | Materi, tugas, pengumpulan, penilaian |
| Laporan | Export PDF/Excel semua modul |
| Pengaturan | Profil sekolah, format nilai, kurikulum |

## 4. Kebutuhan Non-Fungsional

| Aspek | Spesifikasi |
|-------|-------------|
| Arsitektur | Clean Architecture, Service Layer, Repository Pattern |
| Keamanan | Role & permission (Spatie), activity log, audit trail, login history |
| UI/UX | Responsive mobile, sidebar, dark mode, breadcrumb, DataTable |
| Performa | Pagination, query optimization, soft delete |
| API | REST API v1 dengan Sanctum authentication |
| Export | PDF (DomPDF), Excel (Maatwebsite) |
| Database | MySQL, migration, seeder, factory |

## 5. Aturan Bisnis Kunci

1. **Satu tahun ajaran aktif** per sekolah pada satu waktu
2. **Nilai akhir** dihitung dari bobot komponen penilaian (konfigurasi per sekolah/kurikulum)
3. **Absensi harian** per kelas, satu record per siswa per hari
4. **Rapor semester** di-generate setelah nilai akhir terkunci
5. **Orang tua** hanya dapat melihat data anak yang ter-link
6. **Siswa** hanya melihat data milik sendiri
7. **Super Admin** bypass tenant scope, dapat switch sekolah
8. **Soft delete** untuk semua entitas utama, restore oleh admin

## 6. Fleksibilitas Jenjang Pendidikan

Tabel `education_levels` menyimpan jenjang yang didukung:
- SD (6 tingkat)
- SMP (3 tingkat)
- SMA/SMK (3 tingkat + jurusan)
- Madrasah (MI/MTs/MA)
- Pesantren (custom tingkat)
- Bimbingan Belajar (kelas/fase)

Konfigurasi per sekolah via `school_settings` (JSON):
- Label tingkat kelas
- Apakah menggunakan jurusan
- Format penomoran NIS
- Skala nilai (0-100, A-E, dll)

## 7. Teknologi

- Backend: Laravel 12
- Frontend: Blade + Tailwind CSS + Alpine.js
- Database: MySQL
- Auth: Laravel Breeze
- Permission: Spatie Laravel Permission
- Activity Log: Spatie Laravel Activitylog
- PDF: barryvdh/laravel-dompdf
- Excel: maatwebsite/excel
- API Auth: Laravel Sanctum

## 8. Batasan dan Asumsi

- Single database dengan `school_id` untuk multi-tenancy
- Satu domain, konteks sekolah via session setelah login
- Bahasa UI: Indonesia
- Timezone: Asia/Jakarta
