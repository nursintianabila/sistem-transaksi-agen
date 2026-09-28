# TESTING — Sistem Transaksi Agen

Dokumen ini berisi hasil pengujian aplikasi **Sistem Transaksi Agen** yang dibangun menggunakan Laravel sebagai REST API backend dan Vue.js sebagai frontend.

Pengujian dilakukan untuk memastikan fungsi autentikasi, hak akses pengguna, pengelolaan transaksi, validasi input, dashboard, dan keamanan endpoint berjalan sesuai kebutuhan sistem.

---

# 1. Informasi Pengujian

## Aplikasi

**Nama:** Sistem Transaksi Agen

## Backend

```text
Laravel 10
PHP 8.1
Laravel Sanctum
MySQL
```

Backend development server:

```text
http://127.0.0.1:8000
```

Base API:

```text
http://127.0.0.1:8000/api
```

## Frontend

```text
Vue.js 3
Vite 5
Axios
```

Frontend development server:

```text
http://localhost:5173
```

## Tools Pengujian

Pengujian dilakukan menggunakan:

- Browser
- Postman
- Laravel Artisan
- Vue/Vite Development Server

---

# 2. Akun Demo Pengujian

Akun berikut digunakan khusus untuk latihan dan pengujian.

## Admin

```text
Email    : admin@agen.com
Password : admin123
Role     : admin
```

Hak akses:

- melihat transaksi;
- menambah transaksi;
- mengedit transaksi;
- menghapus transaksi;
- melihat ringkasan transaksi;
- melakukan pencarian dan filter.

## Owner

```text
Email    : owner@agen.com
Password : owner123
Role     : owner
```

Hak akses:

- melihat daftar transaksi;
- melihat total transaksi;
- melihat total nominal;
- melihat total biaya admin;
- melihat total keseluruhan.

Owner tidak memiliki akses untuk:

- menambah transaksi;
- mengedit transaksi;
- menghapus transaksi.

---

# 3. Status Pengujian

Status yang digunakan pada dokumen ini:

| Status | Keterangan |
|---|---|
| LULUS | Actual result sesuai dengan expected result |
| GAGAL | Actual result tidak sesuai expected result |
| BELUM DIUJI | Pengujian belum dilakukan |

---

# 4. TC-01 — Login dan Logout

## TC-01.1 Login Admin Valid

**Kondisi awal:**

Pengguna belum login.

**Akun/Peran:**

```text
Admin
```

**Input:**

```json
{
    "email": "admin@agen.com",
    "password": "admin123"
}
```

**Endpoint:**

```http
POST /api/login
```

**Expected Result:**

- HTTP `200 OK`;
- login berhasil;
- API mengembalikan token;
- data user memiliki role `admin`;
- frontend menampilkan Dashboard Admin.

**Actual Result:**

Login berhasil, token diterima dan user dikenali sebagai Admin.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot response login Admin pada Postman dan Dashboard Admin.

---

## TC-01.2 Login Owner Valid

**Kondisi awal:**

Pengguna belum login.

**Akun/Peran:**

```text
Owner
```

**Input:**

```json
{
    "email": "owner@agen.com",
    "password": "owner123"
}
```

**Endpoint:**

```http
POST /api/login
```

**Expected Result:**

- HTTP `200 OK`;
- login berhasil;
- API mengembalikan token;
- role yang diterima adalah `owner`;
- frontend menampilkan Dashboard Owner.

**Actual Result:**

Login berhasil dan user dikenali sebagai Owner.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot response login Owner dan Dashboard Owner.

---

## TC-01.3 Login dengan Password Salah

**Kondisi awal:**

Pengguna belum login.

**Input contoh:**

```json
{
    "email": "admin@agen.com",
    "password": "password-salah"
}
```

**Endpoint:**

```http
POST /api/login
```

**Expected Result:**

- HTTP `401 Unauthorized`;
- login ditolak;
- token tidak diberikan;
- pesan email atau password salah ditampilkan.

**Actual Result:**

Isi berdasarkan hasil pengujian.

**Status:**

```text
BELUM DIUJI
```

**Bukti:**

Screenshot response Postman.

---

## TC-01.4 Logout

**Kondisi awal:**

User sudah login dan mempunyai Bearer Token aktif.

**Endpoint:**

```http
POST /api/logout
```

**Expected Result:**

- HTTP `200 OK`;
- logout berhasil;
- token aktif dihapus;
- frontend kembali ke halaman login.

**Actual Result:**

Logout berhasil dan pengguna kembali ke halaman login.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot setelah logout.

---

## TC-01.5 Mengakses Endpoint Protected Setelah Logout

**Kondisi awal:**

User sudah logout.

**Endpoint:**

```http
GET /api/user
```

Gunakan token lama yang telah di-logout.

**Expected Result:**

```text
401 Unauthorized
```

Data user tidak dapat diakses.

**Actual Result:**

Token lama tidak dapat digunakan kembali untuk mengakses endpoint protected.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot response `401` pada Postman.

---

# 5. TC-02 — Hak Akses Owner

## TC-02.1 Owner Melihat Daftar Transaksi

**Kondisi awal:**

Owner sudah login.

**Endpoint:**

```http
GET /api/transaksi
```

**Authorization:**

```text
Bearer Token Owner
```

**Expected Result:**

- HTTP `200 OK`;
- daftar transaksi dapat dilihat;
- data transaksi tidak berubah.

**Actual Result:**

Owner berhasil melihat daftar transaksi.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot response GET transaksi dengan token Owner.

---

## TC-02.2 Owner Mencoba Menambah Transaksi

**Kondisi awal:**

Owner sudah login.

**Endpoint:**

```http
POST /api/transaksi
```

**Input contoh:**

```json
{
    "jenis_transaksi": "Transfer",
    "nama_pelanggan": "Tes Owner",
    "nomor_tujuan": "1234567890",
    "nominal": 100000,
    "biaya_admin": 5000,
    "tanggal_transaksi": "2026-09-27"
}
```

**Expected Result:**

```text
403 Forbidden
```

Transaksi tidak ditambahkan.

**Actual Result:**

Request ditolak dengan `403 Forbidden`.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot response `403`.

---

## TC-02.3 Owner Mencoba Mengubah Transaksi dengan PUT

**Endpoint:**

```http
PUT /api/transaksi/{id}
```

**Expected Result:**

```text
403 Forbidden
```

Data transaksi harus tetap sama.

**Actual Result:**

Request ditolak dengan `403 Forbidden`.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot response PUT Owner dan data transaksi sebelum/sesudah pengujian.

---

## TC-02.4 Owner Mencoba Mengubah Transaksi dengan PATCH

**Endpoint:**

```http
PATCH /api/transaksi/{id}
```

**Expected Result:**

```text
403 Forbidden
```

Data transaksi tidak berubah.

**Actual Result:**

Request ditolak dengan `403 Forbidden`.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot response PATCH Owner.

---

## TC-02.5 Owner Mencoba Menghapus Transaksi

**Endpoint:**

```http
DELETE /api/transaksi/{id}
```

**Expected Result:**

```text
403 Forbidden
```

Jumlah dan data transaksi tidak berubah.

**Actual Result:**

Request ditolak dengan `403 Forbidden`.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot response DELETE Owner serta bukti transaksi masih tersedia.

---

# 6. TC-03 — Create Transaksi oleh Admin

## TC-03.1 Tambah Transaksi Valid

**Kondisi awal:**

Admin sudah login.

**Input contoh:**

```json
{
    "jenis_transaksi": "Transfer",
    "nama_pelanggan": "Budi Santoso",
    "nomor_tujuan": "1234567890",
    "nominal": 100000,
    "biaya_admin": 5000,
    "tanggal_transaksi": "2026-09-27"
}
```

**Endpoint:**

```http
POST /api/transaksi
```

**Expected Result:**

Transaksi berhasil disimpan.

Total:

```text
100000 + 5000 = 105000
```

Sehingga:

```text
Total = Rp105.000
```

**Actual Result:**

Transaksi berhasil ditambahkan dan tampil pada daftar transaksi.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot form dan transaksi yang berhasil ditambahkan.

---

# 7. TC-04 — Read Transaksi

## TC-04.1 Melihat Seluruh Transaksi

**Endpoint:**

```http
GET /api/transaksi
```

**Akun/Peran:**

Admin atau Owner.

**Expected Result:**

- HTTP `200 OK`;
- seluruh transaksi ditampilkan.

**Actual Result:**

Daftar transaksi berhasil ditampilkan.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot daftar transaksi.

---

## TC-04.2 Melihat Detail Transaksi

**Endpoint:**

```http
GET /api/transaksi/{id}
```

**Expected Result:**

Data transaksi dengan ID yang dipilih berhasil ditampilkan.

**Actual Result:**

Isi berdasarkan hasil pengujian detail transaksi.

**Status:**

```text
BELUM DIUJI
```

**Bukti:**

Screenshot response Postman.

---

# 8. TC-05 — Update Transaksi oleh Admin

## TC-05.1 Update Transaksi Valid

**Kondisi awal:**

Admin sudah login dan transaksi tersedia.

Contoh perubahan:

```text
Nominal:
100000 → 150000

Biaya Admin:
5000 → 7000
```

**Endpoint:**

```http
PUT /api/transaksi/{id}
```

atau:

```http
PATCH /api/transaksi/{id}
```

**Expected Result:**

Data berhasil diperbarui.

Perhitungan total baru:

```text
150000 + 7000 = 157000
```

**Actual Result:**

Transaksi berhasil diperbarui.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot sebelum dan sesudah update.

---

# 9. TC-06 — Delete Transaksi oleh Admin

## TC-06.1 Hapus Transaksi

**Kondisi awal:**

Admin sudah login dan transaksi tersedia.

**Endpoint:**

```http
DELETE /api/transaksi/{id}
```

**Expected Result:**

- transaksi berhasil dihapus;
- transaksi tidak lagi muncul pada daftar;
- jumlah transaksi pada ringkasan berkurang.

**Actual Result:**

Transaksi berhasil dihapus.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot sebelum dan sesudah delete.

---

# 10. TC-07 — Validasi Input Transaksi

## TC-07.1 Nominal Tidak Valid

**Input contoh:**

```text
Nominal = 0
```

atau nilai negatif.

**Expected Result:**

Data tidak disimpan dan pengguna mendapatkan pesan validasi.

**Actual Result:**

Isi berdasarkan hasil pengujian.

**Status:**

```text
BELUM DIUJI
```

**Bukti:**

Screenshot pesan validasi.

---

## TC-07.2 Biaya Admin Negatif

**Input:**

```text
Biaya Admin = -5000
```

**Expected Result:**

Data tidak disimpan.

**Actual Result:**

Isi berdasarkan hasil pengujian.

**Status:**

```text
BELUM DIUJI
```

**Bukti:**

Screenshot pesan validasi.

---

## TC-07.3 Nomor Tujuan Tidak Valid

**Input contoh:**

```text
ABC123
```

**Expected Result:**

Input ditolak sesuai validasi frontend.

**Actual Result:**

Isi berdasarkan hasil pengujian.

**Status:**

```text
BELUM DIUJI
```

**Bukti:**

Screenshot pesan validasi.

---

## TC-07.4 Field Wajib Kosong

**Kondisi:**

Salah satu atau beberapa field wajib tidak diisi.

**Expected Result:**

Form tidak dapat disimpan dan menampilkan informasi validasi.

**Actual Result:**

Isi berdasarkan hasil pengujian.

**Status:**

```text
BELUM DIUJI
```

**Bukti:**

Screenshot form dan pesan validasi.

---

# 11. TC-08 — Dashboard dan Ringkasan

## TC-08.1 Total Transaksi

**Expected Result:**

Jumlah pada kartu `Total Transaksi` sama dengan jumlah seluruh transaksi.

**Actual Result:**

Total transaksi sesuai dengan data.

**Status:**

```text
LULUS
```

---

## TC-08.2 Total Nominal

**Expected Result:**

Total nominal merupakan penjumlahan nilai `nominal` seluruh transaksi.

**Actual Result:**

Nilai total nominal sesuai.

**Status:**

```text
LULUS
```

---

## TC-08.3 Total Biaya Admin

**Expected Result:**

Total biaya admin merupakan penjumlahan seluruh `biaya_admin`.

**Actual Result:**

Nilai total biaya admin sesuai.

**Status:**

```text
LULUS
```

---

## TC-08.4 Total Keseluruhan

**Expected Result:**

Total keseluruhan merupakan penjumlahan nilai `total` seluruh transaksi.

Secara umum:

```text
Total Keseluruhan =
Total Nominal + Total Biaya Admin
```

**Actual Result:**

Nilai total keseluruhan sesuai.

**Status:**

```text
LULUS
```

**Bukti TC-08:**

Screenshot empat Summary Cards dan daftar transaksi yang digunakan sebagai pembanding.

---

# 12. TC-09 — Tampilan Berdasarkan Role

## TC-09.1 Dashboard Admin

**Akun:**

```text
admin@agen.com
```

**Expected Result:**

Admin dapat melihat:

- Summary Cards;
- form transaksi;
- pencarian dan filter;
- daftar transaksi;
- tombol Edit;
- tombol Hapus.

**Actual Result:**

Dashboard Admin menampilkan fitur pengelolaan transaksi.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot Dashboard Admin.

---

## TC-09.2 Dashboard Owner

**Akun:**

```text
owner@agen.com
```

**Expected Result:**

Owner hanya melihat:

- Total Transaksi;
- Total Nominal;
- Total Biaya Admin;
- Total Keseluruhan;
- daftar transaksi.

Owner tidak melihat:

- form tambah transaksi;
- tombol Edit;
- tombol Hapus.

**Actual Result:**

Dashboard Owner tampil sebagai halaman read-only.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot Dashboard Owner.

---

# 13. TC-10 — Pencarian dan Filter

## TC-10.1 Pencarian Nama Pelanggan

**Input contoh:**

```text
Budi
```

**Expected Result:**

Tabel hanya menampilkan transaksi dengan nama pelanggan yang sesuai dengan keyword.

**Actual Result:**

Pencarian bekerja sesuai keyword.

**Status:**

```text
LULUS
```

---

## TC-10.2 Pencarian Nomor Tujuan

**Input:**

Nomor tujuan yang terdapat pada salah satu transaksi.

**Expected Result:**

Transaksi dengan nomor tersebut ditampilkan.

**Actual Result:**

Pencarian nomor tujuan berjalan.

**Status:**

```text
LULUS
```

---

## TC-10.3 Filter Jenis Transaksi

**Input contoh:**

```text
Transfer
```

**Expected Result:**

Hanya transaksi dengan jenis `Transfer` yang ditampilkan.

**Actual Result:**

Filter jenis transaksi bekerja.

**Status:**

```text
LULUS
```

---

## TC-10.4 Filter Tanggal

**Input:**

Tanggal transaksi tertentu.

**Expected Result:**

Hanya transaksi pada tanggal yang dipilih yang ditampilkan.

**Actual Result:**

Filter tanggal bekerja.

**Status:**

```text
LULUS
```

**Bukti TC-10:**

Screenshot sebelum dan setelah pencarian/filter.

---

# 14. TC-11 — Autentikasi Endpoint

## TC-11.1 GET Transaksi Tanpa Token

**Kondisi awal:**

Tidak terdapat Bearer Token.

**Endpoint:**

```http
GET /api/transaksi
```

**Expected Result:**

```text
401 Unauthorized
```

**Actual Result:**

Endpoint protected tidak dapat diakses tanpa autentikasi.

**Status:**

```text
LULUS
```

**Bukti:**

Screenshot Postman tanpa token.

---

## TC-11.2 GET User dengan Token Valid

**Endpoint:**

```http
GET /api/user
```

**Authorization:**

```text
Bearer <token-valid>
```

**Expected Result:**

HTTP `200 OK` dan informasi user yang sedang login dikembalikan.

**Actual Result:**

Data user berhasil diterima.

**Status:**

```text
LULUS
```

---

# 15. TC-12 — Persistensi Session Frontend

## TC-12.1 Refresh Setelah Login

**Kondisi awal:**

Admin atau Owner sudah login.

**Langkah:**

Refresh browser menggunakan `F5`.

**Expected Result:**

Frontend memvalidasi token melalui:

```http
GET /api/user
```

Jika token valid, user tetap berada pada dashboard sesuai role.

**Actual Result:**

Session tetap dikenali setelah halaman direfresh.

**Status:**

```text
LULUS
```

---

## TC-12.2 Token Tidak Valid

**Kondisi awal:**

Token pada frontend sudah tidak valid.

**Expected Result:**

- request `/api/user` gagal;
- data login lokal dibersihkan;
- pengguna kembali ke halaman Login.

**Actual Result:**

Isi berdasarkan hasil pengujian.

**Status:**

```text
BELUM DIUJI
```

---

# 16. Ringkasan Hasil Pengujian

| Test Case | Pengujian | Status |
|---|---|---|
| TC-01 | Login dan Logout | LULUS |
| TC-02 | Hak Akses Owner | LULUS |
| TC-03 | Create Transaksi Admin | LULUS |
| TC-04 | Read Transaksi | LULUS / sebagian belum diuji |
| TC-05 | Update Transaksi Admin | LULUS |
| TC-06 | Delete Transaksi Admin | LULUS |
| TC-07 | Validasi Input | BELUM DIUJI |
| TC-08 | Dashboard dan Ringkasan | LULUS |
| TC-09 | Tampilan Berdasarkan Role | LULUS |
| TC-10 | Pencarian dan Filter | LULUS |
| TC-11 | Proteksi Endpoint | LULUS |
| TC-12 | Persistensi Session | LULUS / sebagian belum diuji |
| TC-13 | Automated Test Backend & Production Build Frontend | LULUS |

> Status `BELUM DIUJI` harus diperbarui setelah pengujian dilakukan. Jangan mengubahnya menjadi `LULUS` tanpa melakukan pengujian dan menyimpan bukti.

---

# 17. Pengujian Build

## Backend

Pengujian backend dilakukan dengan perintah:

```powershell
cd backend
php artisan test
```

Hasil pengujian:

```text
PASS  Tests\Unit\ExampleTest
✓ that true is true

PASS  Tests\Feature\ExampleTest
✓ the application returns a successful response

Tests:    2 passed (2 assertions)
Duration: 8.38s
```

**Actual Result:**

Seluruh automated test Laravel yang tersedia berhasil dijalankan.

```text
Tests      : 2 passed
Assertions : 2 passed
Status     : LULUS
```

> Automated test yang tersedia saat ini masih berupa test bawaan/basic Laravel. Pengujian fitur aplikasi seperti autentikasi, role, dan CRUD didokumentasikan melalui test case manual pada dokumen ini.

---

## Frontend

Pengujian production build frontend dilakukan dengan:

```powershell
cd frontend
npm run build
```

Hasil:

```text
vite v5.4.21 building for production...
✓ 80 modules transformed.

dist/index.html                   0.43 kB
dist/assets/index-DRDzvQPg.css   15.05 kB
dist/assets/index-Be6u3hmy.js   139.12 kB

✓ built in 2.88s
```

**Actual Result:**

Vite berhasil melakukan production build tanpa error.

```text
Modules : 80 modules transformed
Build   : Berhasil
Status  : LULUS
```

Hasil production build tersimpan pada:

```text
frontend/dist/
```

Folder `dist` tidak dimasukkan ke Git karena merupakan hasil build yang dapat dibuat kembali menggunakan `npm run build`.

## Frontend

Jalankan:

```powershell
cd frontend
npm run build
```

Expected Result:

```text
Build berhasil tanpa error.
```

Catat hasil:

```text
Status : BELUM DIUJI
Hasil  : -
```

---

# 18. Bukti Pengujian

Bukti dapat berupa screenshot dari:

1. login Admin;
2. login Owner;
3. login gagal;
4. Dashboard Admin;
5. Dashboard Owner;
6. tambah transaksi;
7. edit transaksi;
8. hapus transaksi;
9. Summary Cards;
10. pencarian/filter;
11. GET transaksi Owner `200 OK`;
12. POST transaksi Owner `403 Forbidden`;
13. PUT transaksi Owner `403 Forbidden`;
14. PATCH transaksi Owner `403 Forbidden`;
15. DELETE transaksi Owner `403 Forbidden`;
16. akses tanpa token `401 Unauthorized`;
17. logout dan penggunaan token lama;
18. hasil `php artisan test`;
19. hasil `npm run build`.

Password, token, cookie, dan informasi sensitif tidak boleh terlihat pada screenshot laporan.

---

# 19. Kesimpulan Pengujian

Berdasarkan pengujian yang telah dilakukan, fungsi utama Sistem Transaksi Agen yang sudah berstatus **LULUS** meliputi:

- autentikasi Admin dan Owner;
- logout;
- proteksi endpoint menggunakan Laravel Sanctum;
- pembatasan hak akses berdasarkan role;
- CRUD transaksi oleh Admin;
- akses transaksi read-only oleh Owner;
- perhitungan ringkasan transaksi;
- pencarian dan filter;
- pemisahan tampilan dashboard Admin dan Owner;
- persistensi session frontend.

Kasus yang masih berstatus `BELUM DIUJI` harus diuji dan didokumentasikan sebelum project dinyatakan final.