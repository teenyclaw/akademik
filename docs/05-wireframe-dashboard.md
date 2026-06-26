# Wireframe Dashboard — SIA Multi-Instansi

## Layout Utama

```
+------------------------------------------------------------------------+
| [=] [Logo] SMA Negeri 1 Jakarta                    [🌙] [🔔] [User ▼] |
+----------+-------------------------------------------------------------+
|          | Dashboard > Beranda                                         |
| SIDEBAR  |                                                             |
|          | +-------------+ +-------------+ +-------------+ +----------+|
| 🏠 Dash  | | 👨‍🎓 Siswa   | | 👨‍🏫 Guru    | | 🏫 Kelas    | | ✅ Absensi||
| 📅 TA    | |   1,240     | |    68       | |    36       | |  94.2%   ||
| 📚 Master| |  +12 bulan  | |  +2 bulan   | |   +3 TA     | |  hari ini||
| 👨‍🎓 Siswa | +-------------+ +-------------+ +-------------+ +----------+|
| 👨‍🏫 Guru  |                                                             |
| ...      | +---------------------------+ +-----------------------------+|
|          | | 📈 Trend Nilai Rata-rata  | | 📊 Absensi Bulan Ini       ||
|          | |                           | |                             ||
|          | |  [Line Chart - Chart.js]  | |  [Bar Chart - Chart.js]    ||
|          | |                           | |                             ||
|          | +---------------------------+ +-----------------------------+|
|          |                                                             |
|          | +---------------------------+ +-----------------------------+|
|          | | 📢 Pengumuman Terbaru     | | 📅 Agenda Akademik         ||
|          | |                           | |                             ||
|          | | • UTS Semester Ganjil     | | 28 Jun - Rapat Guru        ||
|          | | • Libur Idul Adha         | | 30 Jun - Batas Input Nilai ||
|          | | • Pendaftaran Ekstrakur.  | | 05 Jul - UAS Semester 1    ||
|          | |                           | |                             ||
|          | +---------------------------+ +-----------------------------+|
|          |                                                             |
| [Avatar] | +-----------------------------------------------------------+|
| Admin    | | 📋 Aktivitas Terbaru                                      ||
| Logout   | | Siswa baru: Ahmad Fauzi | Nilai diinput: Matematika X-A  ||
|          | +-----------------------------------------------------------+|
+----------+-------------------------------------------------------------+
```

## Komponen UI

### 1. Top Navigation Bar
- **Logo + Nama Sekolah** (dari `schools.name` + logo)
- **Dark Mode Toggle** — Alpine.js + localStorage + Tailwind `dark:` class
- **Notifikasi Bell** — badge count pengumuman baru
- **User Dropdown** — profil, ganti sekolah (super admin), logout
- **Hamburger Menu** — mobile toggle sidebar

### 2. Sidebar
- Collapsible on desktop (icon-only mode)
- Drawer overlay on mobile (< 768px)
- Menu items dengan icon SVG (Heroicons)
- Submenu expandable dengan Alpine.js `x-show`
- Active state: bg-indigo-50 dark:bg-indigo-900 border-l-4 border-indigo-600
- Permission-based rendering via `@can`

### 3. Breadcrumb
```html
<nav class="flex text-sm text-gray-500">
  <a href="/dashboard">Dashboard</a>
  <span class="mx-2">/</span>
  <span class="text-gray-900 dark:text-white">Siswa</span>
</nav>
```

### 4. Stat Cards
```
+----------------------------------+
|  [Icon]  Label                   |
|          1,240                   |
|          +12 dari bulan lalu  ↑  |
+----------------------------------+
```
- Background: white / dark:bg-gray-800
- Icon dengan rounded background color
- Angka besar (text-3xl font-bold)
- Trend indicator (hijau naik, merah turun)

### 5. Charts (Chart.js)
- **Line Chart**: Trend nilai rata-rata per bulan
- **Bar Chart**: Absensi H/S/I/A per minggu
- **Doughnut Chart**: Distribusi predikat (opsional)
- Responsive, dark mode compatible colors

### 6. DataTable
```
+------+--------+----------+--------+---------+--------+
| No   | NIS    | Nama     | Kelas  | Status  | Aksi   |
+------+--------+----------+--------+---------+--------+
| 1    | 2024001| Ahmad F. | X-A    | Aktif   | [Edit] |
| 2    | 2024002| Budi S.  | X-A    | Aktif   | [Edit] |
+------+--------+----------+--------+---------+--------+
| << 1 2 3 ... 10 >>          Menampilkan 1-10 dari 240 |
+-------------------------------------------------------+
```
- Search input
- Sortable columns
- Pagination
- Bulk actions (opsional)
- Export buttons (PDF/Excel)

### 7. Form Layout
```
+-------------------------------------------------------+
| Tambah Siswa                                          |
+-------------------------------------------------------+
| NIS *          [_______________]  NISN [___________] |
| Nama *         [_______________________________]      |
| Jenis Kelamin  ( ) Laki-laki  ( ) Perempuan          |
| Tempat Lahir   [___________]  Tanggal [____/__/____]  |
| Kelas *        [Dropdown v]                           |
| Foto           [Choose File]                        |
+-------------------------------------------------------+
|                              [Batal]  [Simpan]        |
+-------------------------------------------------------+
```

### 8. Dark Mode
- Toggle di topbar
- Strategy: Tailwind `class` mode
- Persist preference di localStorage
- Semua komponen support `dark:` variants
- Charts: adjust colors for dark background

### 9. Responsive Breakpoints
| Breakpoint | Layout |
|-----------|--------|
| < 768px (mobile) | Sidebar hidden, hamburger menu, stat cards 1 kolom |
| 768-1024px (tablet) | Sidebar collapsed, stat cards 2 kolom |
| > 1024px (desktop) | Sidebar full, stat cards 4 kolom |

### 10. Color Palette
| Elemen | Light | Dark |
|--------|-------|------|
| Background | gray-50 | gray-900 |
| Card | white | gray-800 |
| Primary | indigo-600 | indigo-500 |
| Success | green-500 | green-400 |
| Warning | yellow-500 | yellow-400 |
| Danger | red-500 | red-400 |
| Text | gray-900 | gray-100 |

## Wireframe Dashboard per Role

### Super Admin
- Total sekolah, total user, login hari ini
- List sekolah terbaru
- Activity log global

### Admin Sekolah
- Stat cards: siswa, guru, kelas, absensi
- Charts: nilai & absensi
- Pengumuman & agenda

### Wali Kelas
- Info kelas (nama, jumlah siswa)
- Absensi hari ini kelas
- Nilai belum diinput
- Catatan rapor pending

### Guru
- Jadwal mengajar hari ini
- Tugas e-learning pending review
- Pengumuman

### Siswa
- Jadwal hari ini
- Nilai terbaru
- Absensi bulan ini
- Tugas e-learning deadline

### Orang Tua
- Pilih anak (dropdown)
- Nilai & absensi anak
- Tagihan pembayaran
- Pengumuman
