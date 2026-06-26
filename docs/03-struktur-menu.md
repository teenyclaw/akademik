# Struktur Menu — SIA Multi-Instansi

Menu dirender dinamis berdasarkan **Spatie Permission** menggunakan `@can` directive di Blade.

---

## Super Admin

| Menu | Permission | Route |
|------|-----------|-------|
| Dashboard Platform | `platform.dashboard` | `/admin/dashboard` |
| Manajemen Sekolah | `schools.view` | `/admin/schools` |
| Manajemen User Global | `users.view` | `/admin/users` |
| Log Aktivitas | `activity-log.view` | `/admin/activity-log` |
| Login History | `login-history.view` | `/admin/login-history` |
| Pengaturan Sistem | `system.settings` | `/admin/settings` |

---

## Admin Sekolah

| Menu | Submenu | Permission |
|------|---------|-----------|
| **Dashboard** | | `dashboard.view` |
| **Tahun Ajaran** | Daftar TA, Semester | `academic-years.view` |
| **Data Master** | Tingkat Kelas | `grade-levels.view` |
| | Jurusan | `majors.view` |
| | Kelas | `classes.view` |
| | Ruangan | `rooms.view` |
| | Jam Pelajaran | `lesson-hours.view` |
| **Siswa** | Daftar Siswa, Import | `students.view` |
| **Guru** | Daftar Guru | `teachers.view` |
| **Orang Tua** | Daftar Orang Tua | `parents.view` |
| **Mata Pelajaran** | Mapel, Kelompok, Pengampu | `subjects.view` |
| **Jadwal** | Jadwal Pelajaran | `schedules.view` |
| **Absensi** | Input Absensi, Rekap | `attendances.view` |
| **Penilaian** | Komponen, Assessment, Nilai | `grades.view` |
| **Rapor** | Generate, Cetak PDF | `report-cards.view` |
| **Pengumuman** | Daftar, Buat | `announcements.view` |
| **Pembayaran** | Jenis Biaya, Tagihan, Riwayat | `payments.view` |
| **E-Learning** | Materi, Tugas, Penilaian | `elearning.view` |
| **Laporan** | Siswa, Guru, Absensi, Nilai, Rapor, Pembayaran | `reports.view` |
| **Pengaturan** | Profil Sekolah, Format Nilai, Kurikulum, Kop Rapor | `school-settings.view` |
| **User & Role** | Manajemen User, Role | `users.view` |

---

## Kepala Sekolah

| Menu | Permission |
|------|-----------|
| Dashboard | `dashboard.view` |
| Laporan Akademik | `reports.view` |
| Rapor (Approve) | `report-cards.approve` |
| Pengumuman | `announcements.view` |
| Statistik Sekolah | `dashboard.view` |

---

## Wali Kelas

| Menu | Permission |
|------|-----------|
| Dashboard | `dashboard.view` |
| Kelas Saya | `classes.view-own` |
| Absensi Kelas | `attendances.create` |
| Nilai Siswa | `grades.view` |
| Catatan Rapor | `report-cards.edit-notes` |
| Data Siswa Kelas | `students.view-own-class` |
| Pengumuman | `announcements.view` |

---

## Guru

| Menu | Permission |
|------|-----------|
| Dashboard | `dashboard.view` |
| Jadwal Mengajar | `schedules.view-own` |
| Absensi | `attendances.create` |
| Input Nilai | `grades.create` |
| E-Learning | `elearning.view`, `elearning.create` |
| Pengumuman | `announcements.view` |

---

## Siswa

| Menu | Permission |
|------|-----------|
| Dashboard | `dashboard.view` |
| Jadwal Pelajaran | `schedules.view-own` |
| Nilai Saya | `grades.view-own` |
| Absensi Saya | `attendances.view-own` |
| Pengumuman | `announcements.view` |
| E-Learning | `elearning.view-own` |
| Pembayaran | `payments.view-own` |

---

## Orang Tua

| Menu | Permission |
|------|-----------|
| Dashboard | `dashboard.view` |
| Monitoring Anak | `students.view-children` |
| Nilai Anak | `grades.view-children` |
| Absensi Anak | `attendances.view-children` |
| Pengumuman | `announcements.view` |
| Pembayaran | `payments.view-children` |

---

## Tata Usaha

| Menu | Permission |
|------|-----------|
| Dashboard | `dashboard.view` |
| Siswa | `students.view`, `students.create` |
| Guru | `teachers.view` |
| Orang Tua | `parents.view`, `parents.create` |
| Pembayaran | `payments.view`, `payments.create` |
| Laporan | `reports.view`, `reports.export` |
| Pengumuman | `announcements.view` |

---

## Struktur Sidebar UI

```
┌─────────────────────────┐
│ [Logo] Nama Sekolah     │
├─────────────────────────┤
│ 🏠 Dashboard            │
│ 📅 Tahun Ajaran      ▼  │
│ 📚 Data Master       ▼  │
│ 👨‍🎓 Siswa               │
│ 👨‍🏫 Guru                │
│ 👪 Orang Tua            │
│ 📖 Mata Pelajaran    ▼  │
│ 🗓️ Jadwal               │
│ ✅ Absensi           ▼  │
│ 📝 Penilaian         ▼  │
│ 📄 Rapor                │
│ 📢 Pengumuman           │
│ 💰 Pembayaran        ▼  │
│ 💻 E-Learning        ▼  │
│ 📊 Laporan           ▼  │
│ ⚙️ Pengaturan        ▼  │
├─────────────────────────┤
│ [User Avatar] Nama      │
│ Role | Logout           │
└─────────────────────────┘
```

- Submenu expandable via Alpine.js
- Active state highlight
- Mobile: drawer overlay
- Collapse toggle untuk desktop
