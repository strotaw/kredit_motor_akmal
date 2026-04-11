# Rancangan Sistem Kredit Motor Laravel

## 1. Tujuan Sistem

Sistem ini dipakai untuk:

- `user` melihat katalog motor, melakukan simulasi kredit, mengisi form pengajuan, upload dokumen, dan memantau status pengajuan/kredit.
- `admin` memverifikasi data user, memproses approval, membuat data kredit aktif, mengelola pembayaran, dan mengatur pengiriman.
- `ceo` memantau data user, transaksi, performa pengajuan, dan laporan bisnis.

Arsitektur yang disarankan adalah **1 project Laravel** untuk:

- frontend web
- backend admin
- API internal
- koneksi database MySQL XAMPP

## 2. Asumsi dan Keputusan Arsitektur

### 2.1 Asumsi

- Project memakai Laravel 12.
- Database utama memakai MySQL/MariaDB dari XAMPP.
- Frontend, backend, dan API berada di folder Laravel yang sama.
- Akses role dilakukan dari satu sistem login.

### 2.2 Keputusan Arsitektur Utama

Agar flow bisnis rapi, saya sarankan:

1. Semua akun login memakai tabel `users`.
2. Role final yang dipakai saat ini hanya:
   - `user`
   - `admin`
   - `ceo`
3. Tabel `pelanggan` yang sekarang ada jangan dipakai sebagai tabel login.
4. Data detail pelanggan dipindahkan menjadi tabel profil terpisah yang relasinya `users 1:1 user_profiles`.
5. Tabel `pengajuan_kredit` sebaiknya mengarah ke `users.id`, bukan lagi ke `pelanggan.id`.

Alasan utamanya:

- kebutuhan role user/admin/ceo sudah cukup diwakili oleh `users`
- login jadi satu pintu
- laporan CEO lebih mudah
- approval admin lebih mudah dilacak
- tidak ada duplikasi email/password antara `users` dan `pelanggan`

## 3. Catatan Penting dari Database Saat Ini

Dari schema yang ada sekarang, ada beberapa hal yang perlu dibenahi:

1. Migration `users` masih berisi role `admin`, `marketing`, `ceo`, padahal kebutuhan sekarang perlu `user`.
2. Tabel `pelanggan` menyimpan `email` dan `password`, sehingga bentrok dengan tabel `users`.
3. `pengajuan_kredit.id_pelanggan` masih mengarah ke `pelanggan`, padahal proses bisnis yang diminta berbasis role `user`.
4. `pelanggan.password` panjangnya hanya 15 karakter, ini tidak cocok untuk password hash Laravel.
5. Beberapa field status belum cukup untuk audit approval, misalnya siapa admin yang approve, kapan approve, dan catatan verifikasi per dokumen.

## 4. Struktur Role dan Hak Akses

### 4.1 Role User

Hak akses user:

- registrasi dan login
- melihat katalog motor
- melihat detail motor
- menjalankan simulasi kredit
- membuat pengajuan kredit
- upload dokumen
- melihat status pengajuan
- melihat kontrak kredit aktif
- melihat riwayat cicilan
- melihat status pengiriman
- mengubah profil sendiri

### 4.2 Role Admin

Hak akses admin:

- login ke dashboard admin
- melihat antrian pengajuan
- memeriksa data user
- memeriksa dokumen upload
- memberi status `diproses`, `bermasalah`, `diterima`, `dibatalkan_penjual`
- mengisi form approval/admin
- membuat data kredit aktif
- mengatur metode pembayaran
- menginput angsuran manual atau verifikasi pembayaran
- mengatur pengiriman unit
- mengelola master data

### 4.3 Role CEO

Hak akses CEO:

- login ke dashboard executive
- melihat data semua user
- melihat semua transaksi pengajuan dan kredit
- melihat statistik approval
- melihat pembayaran macet/lunas/aktif
- melihat performa motor terlaris
- export laporan

CEO disarankan **read only** untuk transaksi. Jadi CEO melihat dan menganalisis, bukan mengubah proses operasional.

## 5. Struktur Halaman Umum

### 5.1 Halaman Public

#### A. Landing Page

Tujuan:

- branding aplikasi kredit motor
- pintu masuk ke katalog dan simulasi

Isi:

- hero section
- motor unggulan
- langkah pengajuan kredit
- keunggulan layanan
- testimoni
- CTA daftar/login

Fitur:

- tombol `Lihat Motor`
- tombol `Ajukan Kredit`
- tombol `Login`

Layout:

- navbar atas
- hero banner
- section card motor
- footer

#### B. Katalog Motor

Isi:

- grid/list motor
- filter merk
- filter tipe motor
- filter harga
- filter stok
- pencarian nama motor

Fitur:

- sort harga termurah/termahal
- buka detail motor
- simulasi cepat

#### C. Detail Motor

Isi:

- carousel foto motor
- spesifikasi
- warna
- tahun
- harga cash
- estimasi cicilan mulai dari

Fitur:

- simulasi kredit
- tombol ajukan

## 6. Rancangan Halaman Role User

### 6.1 Layout User

Layout yang disarankan:

- top navbar: logo, katalog, simulasi, pengajuan saya, cicilan, profil
- sidebar untuk halaman dashboard
- mobile bottom navigation untuk akses cepat

Komponen global:

- notifikasi status pengajuan
- progress pengajuan
- badge status

### 6.2 Dashboard User

Tujuan:

- memberi ringkasan kondisi akun user

Widget:

- status pengajuan terakhir
- jumlah pengajuan
- kredit aktif
- cicilan berikutnya
- status pengiriman

Fitur:

- tombol `Ajukan Kredit Baru`
- tombol `Lihat Status`
- tombol `Bayar Cicilan`

### 6.3 Profil User

#### Tab Data Akun

Field:

- nama lengkap
- email
- no telepon
- password baru
- konfirmasi password

#### Tab Data Pribadi

Field:

- NIK
- tempat lahir
- tanggal lahir
- jenis kelamin
- pekerjaan
- nama perusahaan
- lama bekerja
- penghasilan per bulan
- status pernikahan

#### Tab Alamat

Field:

- alamat KTP
- kota KTP
- provinsi KTP
- kode pos KTP
- alamat domisili
- kota domisili
- provinsi domisili
- kode pos domisili

#### Tab Kontak Darurat

Field:

- nama kontak darurat
- hubungan
- no telepon kontak darurat

#### Tab Dokumen Profil

Field upload:

- foto profil
- foto KTP
- foto KK
- foto NPWP opsional

### 6.4 Simulasi Kredit

Fungsi:

- menghitung skema kredit sebelum user mengajukan

Input:

- motor
- harga cash
- DP
- tenor / jenis cicilan
- asuransi

Output:

- harga kredit
- margin kredit
- biaya asuransi per bulan
- estimasi cicilan per bulan
- total kewajiban

Fitur:

- validasi minimal DP
- tombol lanjut pengajuan

### 6.5 Pengajuan Kredit Baru

Format terbaik adalah **wizard 5 langkah**.

#### Langkah 1: Pilih Motor

Isi:

- ringkasan motor
- harga cash
- stok
- pilihan warna

#### Langkah 2: Simulasi & Paket Kredit

Field:

- DP
- jenis cicilan
- asuransi

Auto calculate:

- harga kredit
- biaya asuransi per bulan
- cicilan per bulan

#### Langkah 3: Data Pribadi dan Pekerjaan

Field:

- nama lengkap
- email
- no telepon
- NIK
- alamat lengkap
- pekerjaan
- perusahaan
- penghasilan per bulan
- lama bekerja

#### Langkah 4: Upload Dokumen

Dokumen:

- KTP
- KK
- NPWP opsional
- slip gaji
- foto selfie / foto diri

Fitur:

- preview file
- validasi ukuran file
- validasi tipe file

#### Langkah 5: Review dan Submit

Isi:

- ringkasan motor
- ringkasan kredit
- data pribadi
- daftar dokumen
- checkbox persetujuan

Tombol:

- simpan draft
- kirim pengajuan

### 6.6 Status Pengajuan

Isi:

- nomor pengajuan
- tanggal pengajuan
- status pengajuan
- catatan admin
- timeline proses

Status yang tampil:

- menunggu
- diproses
- bermasalah
- diterima
- dibatalkan pembeli
- dibatalkan penjual

Fitur:

- lihat detail
- batalkan pengajuan jika belum diproses final
- upload ulang dokumen jika diminta admin

### 6.7 Detail Kredit Aktif

Isi:

- nomor kontrak
- motor
- tanggal mulai
- tanggal selesai
- sisa kredit
- status kredit
- metode bayar
- histori angsuran

Fitur:

- lihat jadwal angsuran
- unggah bukti bayar jika dibutuhkan
- lihat invoice

### 6.8 Status Pengiriman

Isi:

- nomor invoice
- tanggal kirim
- estimasi tiba
- status kirim
- nama kurir
- telepon kurir
- bukti foto

## 7. Rancangan Halaman Role Admin

### 7.1 Layout Admin

Layout disarankan:

- sidebar kiri
- topbar dengan pencarian, notifikasi, akun admin
- konten utama berbasis tabel dan detail panel

Menu sidebar:

- dashboard
- pengajuan kredit
- kredit aktif
- pembayaran/angsuran
- pengiriman
- motor
- master data
- user
- laporan operasional

### 7.2 Dashboard Admin

Card utama:

- pengajuan baru hari ini
- pengajuan menunggu
- pengajuan bermasalah
- kredit aktif
- kredit macet
- pengiriman berjalan

Widget tambahan:

- daftar pengajuan terbaru
- reminder cicilan jatuh tempo
- notifikasi dokumen kurang

### 7.3 Manajemen Pengajuan Kredit

#### Halaman List Pengajuan

Kolom tabel:

- nomor pengajuan
- tanggal
- nama user
- motor
- DP
- tenor
- cicilan per bulan
- status
- admin penanggung jawab

Filter:

- tanggal
- status
- nama user
- motor

Action:

- lihat detail
- ubah status
- assign admin

#### Halaman Detail Pengajuan

Section:

- data user
- data motor
- simulasi kredit
- dokumen
- catatan verifikasi
- histori status

Fitur:

- approve
- reject
- minta perbaikan dokumen
- ubah status ke diproses
- tambah catatan internal

#### Form Verifikasi Admin

Checklist:

- data identitas valid
- alamat valid
- slip gaji valid
- kemampuan bayar layak
- stok motor tersedia
- dokumen lengkap

Field keputusan:

- status keputusan
- catatan admin
- alasan penolakan bila ditolak
- tanggal verifikasi

### 7.4 Form Aktivasi Kredit

Halaman ini muncul setelah pengajuan diterima.

Field:

- nomor kontrak
- metode bayar
- tanggal mulai kredit
- tanggal selesai kredit
- total sisa kredit
- status kredit awal
- catatan kredit

Auto generate:

- jadwal angsuran
- angsuran ke-1 sampai akhir

### 7.5 Manajemen Angsuran

#### Halaman List Kredit Aktif

Kolom:

- nomor kontrak
- nama user
- motor
- cicilan per bulan
- sisa kredit
- status kredit
- jatuh tempo berikutnya

#### Halaman Detail Angsuran

Isi:

- histori angsuran
- total terbayar
- sisa kredit
- keterlambatan

Action admin:

- input pembayaran
- verifikasi pembayaran
- ubah status macet/lunas/cicil

#### Form Pembayaran/Verifikasi Angsuran

Field:

- tanggal bayar
- angsuran ke
- total bayar
- metode bayar
- bukti bayar opsional
- keterangan

### 7.6 Manajemen Pengiriman

#### Halaman List Pengiriman

Kolom:

- nomor invoice
- nama user
- nomor kontrak
- tanggal kirim
- tanggal tiba
- status
- kurir

#### Form Pengiriman

Field:

- nomor invoice
- tanggal kirim
- estimasi tiba
- nama kurir
- telepon kurir
- bukti foto
- keterangan

Action:

- tandai dikirim
- tandai diterima

### 7.7 Manajemen Master Data

#### A. Motor

Field:

- nama motor
- jenis motor
- harga jual
- deskripsi
- warna
- kapasitas mesin
- tahun
- foto 1
- foto 2
- foto 3
- stok

#### B. Jenis Motor

Field:

- merk
- tipe
- deskripsi jenis
- image

#### C. Jenis Cicilan

Field:

- lama cicilan
- margin kredit

#### D. Asuransi

Field:

- nama perusahaan asuransi
- nama produk asuransi
- margin asuransi
- nomor rekening
- logo

#### E. Metode Bayar

Field:

- nama metode bayar
- tempat bayar
- nomor rekening
- logo

### 7.8 Manajemen User

Kolom:

- nama
- email
- role
- status akun
- tanggal daftar

Action:

- lihat profil
- nonaktifkan akun
- reset password

## 8. Rancangan Halaman Role CEO

### 8.1 Layout CEO

Layout disarankan:

- sidebar sederhana
- filter periode di topbar
- dashboard fokus data dan grafik

Menu:

- dashboard executive
- data user
- data transaksi
- laporan kredit
- laporan pembayaran
- laporan motor

### 8.2 Dashboard Executive

KPI:

- total user
- total pengajuan
- pengajuan diterima
- pengajuan ditolak
- kredit aktif
- kredit macet
- pendapatan angsuran

Grafik:

- tren pengajuan per bulan
- tren approval rate
- tren kredit macet
- motor paling banyak diajukan

### 8.3 Data User

Fitur:

- list semua user
- filter tanggal daftar
- filter status pengajuan
- detail user

Detail user berisi:

- data akun
- data profil
- jumlah pengajuan
- jumlah kredit aktif
- total transaksi

### 8.4 Data Transaksi

Fitur:

- list pengajuan dan kredit
- filter status
- filter rentang tanggal
- filter motor
- export Excel/PDF

Kolom utama:

- nomor pengajuan
- nomor kontrak
- nama user
- motor
- nominal kredit
- status pengajuan
- status kredit
- total terbayar
- sisa kredit

### 8.5 Laporan

Laporan yang disarankan:

- laporan pengajuan kredit
- laporan approval admin
- laporan kredit aktif
- laporan tunggakan
- laporan pelunasan
- laporan motor terlaris

## 9. Rancangan Database yang Disarankan

## 9.1 Tabel Inti yang Dipakai

### 1. users

Fungsi:

- semua akun login

Field utama:

- id
- name
- email
- password
- role (`user`, `admin`, `ceo`)
- email_verified_at
- remember_token
- is_active
- last_login_at
- created_at
- updated_at

### 2. user_profiles

Fungsi:

- detail profil customer/user

Field utama:

- id
- user_id
- nik
- no_telp
- tempat_lahir
- tanggal_lahir
- jenis_kelamin
- pekerjaan
- nama_perusahaan
- lama_bekerja_bulan
- penghasilan_bulanan
- status_pernikahan
- alamat_ktp
- kota_ktp
- provinsi_ktp
- kodepos_ktp
- alamat_domisili
- kota_domisili
- provinsi_domisili
- kodepos_domisili
- nama_kontak_darurat
- hubungan_kontak_darurat
- no_telp_kontak_darurat
- foto_profil
- created_at
- updated_at

### 3. jenis_motor

Tetap dipakai dari schema sekarang.

### 4. motor

Tetap dipakai dari schema sekarang.

Tambahan yang disarankan:

- status_aktif

### 5. jenis_cicilan

Tetap dipakai dari schema sekarang.

### 6. asuransi

Tetap dipakai dari schema sekarang.

### 7. metode_bayar

Tetap dipakai dari schema sekarang.

### 8. pengajuan_kredit

Fungsi:

- inti proses pengajuan user

Field utama:

- id
- kode_pengajuan
- tgl_pengajuan_kredit
- user_id
- motor_id
- harga_cash
- dp
- jenis_cicilan_id
- harga_kredit
- asuransi_id
- biaya_asuransi_perbulan
- cicilan_perbulan
- status_pengajuan
- keterangan_status_pengajuan nullable
- assigned_admin_id nullable
- approved_by nullable
- approved_at nullable
- rejected_by nullable
- rejected_at nullable
- created_at
- updated_at

### 9. pengajuan_dokumen

Fungsi:

- memisahkan file dokumen dari tabel pengajuan

Field:

- id
- pengajuan_kredit_id
- jenis_dokumen (`ktp`, `kk`, `npwp`, `slip_gaji`, `foto_diri`)
- file_path
- status_verifikasi (`menunggu`, `valid`, `revisi`)
- catatan_verifikasi nullable
- created_at
- updated_at

### 10. pengajuan_status_logs

Fungsi:

- histori status pengajuan

Field:

- id
- pengajuan_kredit_id
- status_lama
- status_baru
- catatan
- changed_by
- created_at

### 11. kredit

Fungsi:

- kontrak kredit aktif

Field utama:

- id
- nomor_kontrak
- pengajuan_kredit_id
- metode_bayar_id
- tgl_mulai_kredit
- tgl_selesai_kredit
- sisa_kredit
- status_kredit (`cicil`, `macet`, `lunas`)
- keterangan_status_kredit nullable
- created_by
- created_at
- updated_at

### 12. angsuran

Fungsi:

- histori pembayaran cicilan

Field utama:

- id
- kredit_id
- tgl_bayar
- angsuran_ke
- total_bayar
- metode_bayar_snapshot nullable
- bukti_bayar nullable
- status_verifikasi (`menunggu`, `valid`, `ditolak`)
- verified_by nullable
- verified_at nullable
- keterangan nullable
- created_at
- updated_at

### 13. pengiriman

Fungsi:

- pengiriman unit motor

Field:

- id
- kredit_id
- no_invoice
- tgl_kirim
- tgl_tiba
- status_kirim (`dikirim`, `diterima`)
- nama_kurir
- telpon_kurir
- bukti_foto
- keterangan nullable
- created_at
- updated_at

## 9.2 Relasi Antar Tabel

- `users` 1:1 `user_profiles`
- `users(role=user)` 1:N `pengajuan_kredit`
- `users(role=admin)` 1:N `pengajuan_kredit` melalui `assigned_admin_id`
- `motor` 1:N `pengajuan_kredit`
- `jenis_cicilan` 1:N `pengajuan_kredit`
- `asuransi` 1:N `pengajuan_kredit`
- `pengajuan_kredit` 1:N `pengajuan_dokumen`
- `pengajuan_kredit` 1:N `pengajuan_status_logs`
- `pengajuan_kredit` 1:1 `kredit`
- `kredit` 1:N `angsuran`
- `kredit` 1:1 `pengiriman`

## 9.3 Keputusan Migrasi dari Schema Saat Ini

Saya sarankan jalur perubahan berikut:

1. ubah `users.role` menjadi `user`, `admin`, `ceo`
2. hapus ketergantungan login dari tabel `pelanggan`
3. buat tabel `user_profiles`
4. ubah `pengajuan_kredit.id_pelanggan` menjadi `user_id`
5. pindahkan kolom dokumen file dari `pengajuan_kredit` ke `pengajuan_dokumen`
6. tambahkan tabel histori status untuk audit
7. tambahkan kolom audit approval di `pengajuan_kredit` dan `kredit`

## 10. Rancangan API

Karena frontend, backend, dan API ada di satu Laravel, struktur API yang disarankan:

- `routes/web.php` untuk halaman blade/inertia
- `routes/api.php` untuk JSON API
- auth session untuk web
- Laravel Sanctum untuk request API yang butuh token atau AJAX authenticated

## 10.1 Public API

### Auth

- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `GET /api/auth/me`

### Motor & Simulasi

- `GET /api/motors`
- `GET /api/motors/{id}`
- `GET /api/jenis-motor`
- `GET /api/tenors`
- `GET /api/asuransi`
- `POST /api/simulasi-kredit`

Payload `POST /api/simulasi-kredit`:

- motor_id
- dp
- jenis_cicilan_id
- asuransi_id

Response:

- harga_cash
- margin_kredit
- harga_kredit
- biaya_asuransi_perbulan
- cicilan_perbulan
- total_kewajiban

## 10.2 API User

Middleware:

- auth
- role:user

Endpoint:

- `GET /api/user/dashboard`
- `GET /api/user/profile`
- `PUT /api/user/profile`
- `GET /api/user/pengajuan`
- `POST /api/user/pengajuan`
- `GET /api/user/pengajuan/{id}`
- `PUT /api/user/pengajuan/{id}/draft`
- `POST /api/user/pengajuan/{id}/submit`
- `POST /api/user/pengajuan/{id}/cancel`
- `POST /api/user/pengajuan/{id}/documents`
- `GET /api/user/kredit`
- `GET /api/user/kredit/{id}`
- `GET /api/user/angsuran`
- `POST /api/user/angsuran/{id}/upload-bukti`
- `GET /api/user/pengiriman`

Payload utama `POST /api/user/pengajuan`:

- motor_id
- dp
- jenis_cicilan_id
- asuransi_id
- harga_cash
- harga_kredit
- biaya_asuransi_perbulan
- cicilan_perbulan
- data_pribadi
- data_pekerjaan

## 10.3 API Admin

Middleware:

- auth
- role:admin

Endpoint dashboard:

- `GET /api/admin/dashboard`

Endpoint pengajuan:

- `GET /api/admin/pengajuan`
- `GET /api/admin/pengajuan/{id}`
- `PUT /api/admin/pengajuan/{id}/assign`
- `PUT /api/admin/pengajuan/{id}/status`
- `POST /api/admin/pengajuan/{id}/approve`
- `POST /api/admin/pengajuan/{id}/reject`
- `POST /api/admin/pengajuan/{id}/request-revision`

Endpoint kredit:

- `POST /api/admin/kredit`
- `GET /api/admin/kredit`
- `GET /api/admin/kredit/{id}`
- `PUT /api/admin/kredit/{id}/status`

Endpoint angsuran:

- `GET /api/admin/angsuran`
- `POST /api/admin/angsuran`
- `PUT /api/admin/angsuran/{id}/verify`
- `PUT /api/admin/angsuran/{id}/reject`

Endpoint pengiriman:

- `GET /api/admin/pengiriman`
- `POST /api/admin/pengiriman`
- `PUT /api/admin/pengiriman/{id}`
- `PUT /api/admin/pengiriman/{id}/diterima`

Endpoint master:

- `GET /api/admin/motor`
- `POST /api/admin/motor`
- `PUT /api/admin/motor/{id}`
- `DELETE /api/admin/motor/{id}`
- `GET /api/admin/jenis-motor`
- `GET /api/admin/jenis-cicilan`
- `GET /api/admin/asuransi`
- `GET /api/admin/metode-bayar`

## 10.4 API CEO

Middleware:

- auth
- role:ceo

Endpoint:

- `GET /api/ceo/dashboard`
- `GET /api/ceo/users`
- `GET /api/ceo/users/{id}`
- `GET /api/ceo/transaksi`
- `GET /api/ceo/transaksi/{id}`
- `GET /api/ceo/laporan/pengajuan`
- `GET /api/ceo/laporan/kredit`
- `GET /api/ceo/laporan/tunggakan`
- `GET /api/ceo/laporan/motor`
- `GET /api/ceo/export/transaksi`

## 11. Struktur Controller Laravel yang Disarankan

### Web Controllers

- `AuthController`
- `LandingController`
- `MotorCatalogController`
- `UserDashboardController`
- `UserPengajuanController`
- `UserKreditController`
- `AdminDashboardController`
- `AdminPengajuanController`
- `AdminKreditController`
- `AdminPengirimanController`
- `CeoDashboardController`
- `CeoReportController`

### API Controllers

- `Api/AuthController`
- `Api/Public/MotorController`
- `Api/Public/SimulationController`
- `Api/User/ProfileController`
- `Api/User/PengajuanController`
- `Api/User/KreditController`
- `Api/Admin/PengajuanController`
- `Api/Admin/KreditController`
- `Api/Admin/AngsuranController`
- `Api/Admin/PengirimanController`
- `Api/Admin/MasterDataController`
- `Api/Ceo/DashboardController`
- `Api/Ceo/ReportController`

## 12. Middleware dan Policy yang Perlu

Middleware:

- `auth`
- `role:user`
- `role:admin`
- `role:ceo`

Policy:

- user hanya boleh melihat pengajuannya sendiri
- user hanya boleh melihat kredit miliknya
- admin hanya bisa memproses data operasional
- ceo hanya read only untuk data executive

## 13. Validasi dan Aturan Bisnis Penting

1. User tidak boleh mengajukan jika profil inti belum lengkap.
2. DP harus memenuhi minimal persentase tertentu, misalnya 20 persen dari harga cash.
3. Pengajuan tidak boleh dikirim jika dokumen wajib belum lengkap.
4. Pengajuan yang sudah `diterima` tidak boleh diedit user.
5. Admin hanya bisa membuat kredit jika status pengajuan `diterima`.
6. Pengiriman hanya bisa dibuat jika data kredit sudah aktif.
7. Status `lunas` otomatis jika sisa kredit sudah 0.
8. Status `macet` muncul jika keterlambatan melebihi aturan bisnis tertentu.

## 14. Penyimpanan File

File yang perlu disimpan:

- foto motor
- dokumen user
- bukti pembayaran
- bukti pengiriman

Saran implementasi:

- simpan ke `storage/app/public`
- expose lewat `php artisan storage:link`
- simpan path file di database, bukan file binary

## 15. Koneksi Database XAMPP

Contoh `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kredit_motor
DB_USERNAME=root
DB_PASSWORD=
```

## 16. Rancangan Routing Web

Contoh struktur halaman:

- `/`
- `/login`
- `/register`
- `/motors`
- `/motors/{slug}`
- `/simulasi`
- `/user/dashboard`
- `/user/pengajuan`
- `/user/pengajuan/create`
- `/user/pengajuan/{id}`
- `/user/kredit`
- `/user/kredit/{id}`
- `/user/pengiriman`
- `/user/profile`
- `/admin/dashboard`
- `/admin/pengajuan`
- `/admin/pengajuan/{id}`
- `/admin/kredit`
- `/admin/kredit/{id}`
- `/admin/angsuran`
- `/admin/pengiriman`
- `/admin/master/motor`
- `/admin/master/asuransi`
- `/admin/master/metode-bayar`
- `/ceo/dashboard`
- `/ceo/users`
- `/ceo/transaksi`
- `/ceo/laporan`

## 17. Urutan Implementasi yang Paling Aman

Tahap 1:

- rapikan schema auth
- satukan akun ke `users`
- buat middleware role

Tahap 2:

- bangun public page dan auth
- bangun katalog motor dan simulasi

Tahap 3:

- bangun dashboard user
- bangun wizard pengajuan
- bangun upload dokumen

Tahap 4:

- bangun dashboard admin
- bangun verifikasi pengajuan
- bangun aktivasi kredit

Tahap 5:

- bangun angsuran dan pengiriman
- bangun dashboard CEO dan laporan

## 18. Kesimpulan Rancangan

Rancangan terbaik untuk kebutuhanmu adalah:

- satu tabel login utama `users`
- satu role system: `user`, `admin`, `ceo`
- user fokus ke pengajuan kredit
- admin fokus ke verifikasi, approval, aktivasi kredit, pembayaran, dan pengiriman
- CEO fokus ke monitoring user dan transaksi
- Laravel dipakai sebagai monolith: web + API + backend dalam satu project

Kalau rancangan ini dipakai, tahap berikutnya yang paling tepat adalah:

1. rapikan migration dan relasi database
2. buat role middleware
3. buat routing per role
4. buat UI dashboard per role
5. implement API sesuai modul
