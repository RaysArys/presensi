# Presensi Kita

Aplikasi presensi karyawan berbasis Laravel, MySQL, GPS, dan kamera dengan dua role: Admin dan User.

## Fitur

- Login multirole dan redirect otomatis berdasarkan akun.
- Dashboard Admin dengan kartu ringkasan, chart, presensi harian, dan pengajuan terbaru.
- CRUD serta aktivasi/nonaktivasi karyawan.
- Presensi masuk/pulang menggunakan foto langsung dan validasi radius GPS.
- Pengajuan izin, sakit, dan cuti; bukti hanya wajib untuk sakit.
- Persetujuan atau penolakan oleh Admin.
- Rekap harian dan export CSV.
- Penentuan alpa otomatis berdasarkan presensi, pengajuan disetujui, akhir pekan, dan hari libur.

## Instalasi di Laragon

1. Extract folder `presensi-app` ke `C:\laragon\www\presensi-app`.
2. Buka Terminal Laragon di folder project.
3. Jalankan:

```bash
composer install
copy .env.example .env
php artisan key:generate
```

4. Buat database kosong bernama `presensi_db` melalui phpMyAdmin.
5. Pastikan konfigurasi `.env`:

```env
DB_DATABASE=presensi_db
DB_USERNAME=root
DB_PASSWORD=
```

6. Buat tabel dan data awal:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

7. Buka `http://127.0.0.1:8000`.

## Akun Demo

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `admin123` |
| User | `user` | `user123` |

## Pengaturan GPS

Data seed menggunakan koordinat contoh Jakarta. Ubah data pada tabel `lokasi_presensi` melalui phpMyAdmin:

- `latitude`
- `longitude`
- `radius_meter`
- `jam_masuk`
- `batas_terlambat`
- `jam_pulang`

Untuk pengujian kamera dan GPS, gunakan `localhost`/`127.0.0.1` atau HTTPS. Browser biasanya memblokir kamera dan GPS pada HTTP publik biasa.
