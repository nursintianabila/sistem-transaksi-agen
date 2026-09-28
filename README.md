# Sistem Transaksi Agen

Mini Project UTS Pemrograman Web 2 berupa aplikasi **Sistem Transaksi Agen** berbasis web yang digunakan untuk mencatat, mengelola, dan memantau transaksi agen.

Aplikasi dibangun menggunakan **Laravel sebagai REST API backend** dan **Vue.js sebagai frontend**. Sistem memiliki autentikasi berbasis token menggunakan Laravel Sanctum serta dua hak akses pengguna, yaitu **Admin** dan **Owner**.

---

## 1. Masalah dan Pengguna Sistem

### Masalah

Pencatatan transaksi agen yang dilakukan secara manual dapat menimbulkan beberapa masalah, seperti:

- data transaksi sulit dikelola;
- kesalahan dalam menghitung total transaksi;
- pencarian riwayat transaksi membutuhkan waktu;
- data transaksi sulit dipantau secara keseluruhan;
- tidak adanya pembagian hak akses antara pengguna yang mengelola data dan pengguna yang hanya memantau data.

Sistem Transaksi Agen dibuat untuk membantu proses pencatatan dan pengelolaan transaksi agar lebih terstruktur melalui aplikasi berbasis web.

### Pengguna

Sistem memiliki dua jenis pengguna:

#### Admin

Admin bertugas mengelola transaksi dan memiliki hak akses untuk:

- login;
- melihat daftar transaksi;
- melihat ringkasan transaksi;
- menambah transaksi;
- mengedit transaksi;
- menghapus transaksi;
- mencari transaksi;
- memfilter transaksi;
- logout.

#### Owner

Owner berfungsi sebagai pengguna yang memantau transaksi dan memiliki hak akses **read-only** untuk:

- login;
- melihat daftar transaksi;
- melihat total transaksi;
- melihat total nominal;
- melihat total biaya admin;
- melihat total keseluruhan;
- logout.

Owner tidak dapat menambah, mengubah, atau menghapus transaksi.

---

## 2. Fitur Sistem

Fitur utama yang tersedia pada aplikasi:

### Autentikasi

- Login menggunakan email dan password.
- Autentikasi API menggunakan Laravel Sanctum.
- Token dikirim melalui Bearer Token.
- Logout dan penghapusan token aktif.
- Validasi sesi pengguna ketika aplikasi dibuka kembali.

### Role dan Hak Akses

Terdapat dua role:

- `admin`
- `owner`

Admin memiliki akses CRUD terhadap transaksi.

Owner hanya memiliki akses membaca data transaksi.

Pembatasan tidak hanya dilakukan pada tampilan frontend, tetapi juga pada backend melalui middleware sehingga Owner tetap tidak dapat melakukan operasi perubahan data melalui API.

### Pengelolaan Transaksi

Admin dapat:

- menambahkan transaksi;
- melihat transaksi;
- memperbarui transaksi;
- menghapus transaksi.

Data transaksi meliputi:

- jenis transaksi;
- nama pelanggan;
- nomor tujuan/rekening/pembayaran;
- nominal;
- biaya admin;
- total;
- tanggal transaksi.

Total transaksi dihitung dari:

```text
Total = Nominal + Biaya Admin
```

### Ringkasan Transaksi

Dashboard menampilkan:

- Total Transaksi
- Total Nominal
- Total Biaya Admin
- Total Keseluruhan

### Pencarian dan Filter

Pada dashboard Admin tersedia:

- pencarian berdasarkan nama pelanggan;
- pencarian berdasarkan nomor tujuan;
- filter jenis transaksi;
- filter tanggal transaksi.

---

## 3. Batas Scope

Aplikasi pada versi ini difokuskan pada pengelolaan transaksi agen.

Scope yang tersedia:

- autentikasi pengguna;
- role Admin dan Owner;
- CRUD transaksi untuk Admin;
- akses read-only untuk Owner;
- pencarian dan filter transaksi;
- ringkasan transaksi;
- REST API Laravel;
- frontend Vue.js.

Fitur berikut belum menjadi bagian dari scope:

- registrasi akun secara mandiri;
- reset password melalui email;
- manajemen pengguna melalui frontend;
- export laporan PDF/Excel;
- pagination;
- integrasi payment gateway;
- notifikasi;
- deployment production.

---

## 4. Technology Stack

### Backend

- PHP 8.1
- Laravel 10
- Laravel Sanctum 3
- REST API
- MySQL
- Composer

### Frontend

- Vue.js 3
- Vite 5
- Axios
- JavaScript
- HTML
- CSS
- Node.js / NPM

### Development Tools

- Visual Studio Code
- Laragon
- Postman
- Git
- GitHub

---

## 5. Sumber Starter

Backend dibuat menggunakan starter project Laravel.

Frontend dibuat menggunakan starter project Vue.js melalui Vite.

Setelah project dasar dibuat, fitur transaksi, autentikasi, role, middleware, dashboard, form, tabel, filter, dan integrasi API dikembangkan untuk kebutuhan Sistem Transaksi Agen.

---

## 6. Struktur Folder

Struktur utama repository:

```text
sistem-transaksi-agen/
│
├── backend/
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Api/
│   │   │   │   │   └── TransaksiController.php
│   │   │   │   └── AuthController.php
│   │   │   └── Middleware/
│   │   │       ├── AdminMiddleware.php
│   │   │       └── Authenticate.php
│   │   │
│   │   └── Models/
│   │       ├── Transaksi.php
│   │       └── User.php
│   │
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   │
│   ├── routes/
│   │   └── api.php
│   │
│   ├── .env.example
│   ├── artisan
│   └── composer.json
│
├── frontend/
│   ├── src/
│   │   ├── components/
│   │   │   ├── SummaryCards.vue
│   │   │   ├── TransaksiForm.vue
│   │   │   └── TransaksiTable.vue
│   │   │
│   │   ├── services/
│   │   │   └── api.js
│   │   │
│   │   ├── views/
│   │   │   ├── DashboardView.vue
│   │   │   ├── LoginView.vue
│   │   │   └── OwnerDashboardView.vue
│   │   │
│   │   ├── App.vue
│   │   ├── main.js
│   │   └── style.css
│   │
│   ├── package.json
│   └── vite.config.js
│
├── README.md
├── TESTING.md
└── .gitignore
```

---

## 7. Arsitektur Sistem

Aplikasi menggunakan arsitektur frontend dan backend yang terpisah.

```text
+--------------------------+
|        Pengguna          |
|     Admin / Owner        |
+------------+-------------+
             |
             v
+--------------------------+
|      Vue.js Frontend     |
|    http://localhost:5173 |
+------------+-------------+
             |
             | HTTP / JSON
             | Axios
             | Bearer Token
             v
+-------------------------------+
|       Laravel REST API        |
| http://127.0.0.1:8000/api     |
+---------------+---------------+
                |
                | Sanctum
                | Middleware
                | Controller
                v
+-------------------------------+
|             MySQL             |
|                               |
| users                         |
| transaksis                    |
| personal_access_tokens        |
+-------------------------------+
```

### Alur Autentikasi

```text
Login
  |
  v
POST /api/login
  |
  v
Validasi email & password
  |
  v
Laravel Sanctum membuat token
  |
  v
Frontend menyimpan token
  |
  v
Request API menggunakan Bearer Token
  |
  v
auth:sanctum
  |
  +----------------+
  |                |
  v                v
Admin             Owner
  |                |
CRUD            Read Only
```

---

## 8. ERD

ERD sederhana aplikasi:

```text
+---------------------------+
|           USERS           |
+---------------------------+
| id (PK)                   |
| name                      |
| email                     |
| password                  |
| role                      |
| created_at                |
| updated_at                |
+---------------------------+

+---------------------------+
|        TRANSAKSIS         |
+---------------------------+
| id (PK)                   |
| jenis_transaksi           |
| nama_pelanggan            |
| nomor_tujuan              |
| nominal                   |
| biaya_admin               |
| total                     |
| tanggal_transaksi         |
| created_at                |
| updated_at                |
+---------------------------+

+---------------------------+
| PERSONAL_ACCESS_TOKENS    |
+---------------------------+
| id (PK)                   |
| tokenable_type            |
| tokenable_id              |
| name                      |
| token                     |
| abilities                 |
| last_used_at              |
| created_at                |
| updated_at                |
+---------------------------+
```

Pada scope aplikasi saat ini, transaksi tidak menyimpan `user_id`, sehingga tabel `transaksis` tidak memiliki foreign key langsung ke tabel `users`. User digunakan untuk autentikasi dan pengaturan hak akses terhadap endpoint.

---

## 9. Konfigurasi Environment

File `.env` tidak disimpan di repository karena berisi konfigurasi lokal.

Gunakan:

```text
backend/.env.example
```

sebagai template.

Salin `.env.example`:

### Windows PowerShell

```powershell
cd backend
Copy-Item .env.example .env
```

Kemudian sesuaikan konfigurasi database pada `.env`.

Contoh:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_transaksi_agen
DB_USERNAME=root
DB_PASSWORD=
```

> Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan konfigurasi MySQL pada komputer masing-masing.

---

## 10. Membuat Database

Buat database MySQL baru:

```sql
CREATE DATABASE sistem_transaksi_agen;
```

Kemudian pastikan `.env` menggunakan database tersebut:

```env
DB_DATABASE=sistem_transaksi_agen
```

---

## 11. Instalasi Backend

Masuk ke folder backend:

```powershell
cd backend
```

Install dependency:

```powershell
composer install
```

Salin environment:

```powershell
Copy-Item .env.example .env
```

Generate application key:

```powershell
php artisan key:generate
```

Pastikan konfigurasi database pada `.env` sudah benar.

Jalankan migration:

```powershell
php artisan migrate
```

---

## 12. Membuat Akun Demo

Akun latihan dapat dibuat melalui Laravel Tinker.

Jalankan:

```powershell
php artisan tinker
```

### Admin

```php
\App\Models\User::create([
    'name' => 'Admin Agen',
    'email' => 'admin@agen.com',
    'password' => 'admin123',
    'role' => 'admin',
]);
```

### Owner

```php
\App\Models\User::create([
    'name' => 'Owner Agen',
    'email' => 'owner@agen.com',
    'password' => 'owner123',
    'role' => 'owner',
]);
```

Keluar dari Tinker:

```php
exit
```

> Akun tersebut hanya digunakan sebagai akun demo/latihan dan tidak ditujukan untuk penggunaan production.

---

## 13. Menjalankan Backend

Dari folder:

```text
backend/
```

jalankan:

```powershell
php artisan serve
```

Backend akan berjalan pada:

```text
http://127.0.0.1:8000
```

Base URL API:

```text
http://127.0.0.1:8000/api
```

---

## 14. Instalasi Frontend

Buka terminal baru kemudian masuk ke:

```powershell
cd frontend
```

Install dependency:

```powershell
npm install
```

Jalankan development server:

```powershell
npm run dev
```

Frontend secara default dapat diakses melalui:

```text
http://localhost:5173
```

---

## 15. Build Frontend

Untuk membuat production build:

```powershell
cd frontend
npm run build
```

Hasil build akan dibuat pada:

```text
frontend/dist/
```

Folder `dist` tidak disimpan ke repository karena merupakan hasil build.

---

## 16. Menjalankan Aplikasi

Gunakan dua terminal.

### Terminal 1 — Backend

```powershell
cd backend
php artisan serve
```

### Terminal 2 — Frontend

```powershell
cd frontend
npm run dev
```

Kemudian buka:

```text
http://localhost:5173
```

---

## 17. Akun Demo

| Role | Email | Password |
|---|---|---|
| Admin | `admin@agen.com` | `admin123` |
| Owner | `owner@agen.com` | `owner123` |

Akun tersebut hanya digunakan untuk kebutuhan latihan dan demonstrasi project.

---

## 18. Peta Endpoint API

Base URL:

```text
http://127.0.0.1:8000/api
```

| Method | Endpoint | Akses | Keterangan |
|---|---|---|---|
| POST | `/login` | Public | Login |
| GET | `/user` | Admin & Owner | Mendapatkan user login |
| POST | `/logout` | Admin & Owner | Logout |
| GET | `/transaksi` | Admin & Owner | Melihat seluruh transaksi |
| GET | `/transaksi/{id}` | Admin & Owner | Melihat detail transaksi |
| POST | `/transaksi` | Admin | Menambah transaksi |
| PUT | `/transaksi/{id}` | Admin | Memperbarui transaksi |
| PATCH | `/transaksi/{id}` | Admin | Memperbarui sebagian data transaksi |
| DELETE | `/transaksi/{id}` | Admin | Menghapus transaksi |

---

## 19. Hak Akses

| Fitur | Admin | Owner |
|---|:---:|:---:|
| Login | Ya | Ya |
| Logout | Ya | Ya |
| Melihat daftar transaksi | Ya | Ya |
| Melihat ringkasan | Ya | Ya |
| Total transaksi | Ya | Ya |
| Total nominal | Ya | Ya |
| Total biaya admin | Ya | Ya |
| Total keseluruhan | Ya | Ya |
| Menambah transaksi | Ya | Tidak |
| Mengedit transaksi | Ya | Tidak |
| Menghapus transaksi | Ya | Tidak |
| Pencarian/filter pada dashboard Admin | Ya | Tidak |
| POST transaksi melalui API | Ya | 403 |
| PUT/PATCH transaksi melalui API | Ya | 403 |
| DELETE transaksi melalui API | Ya | 403 |

---

## 20. Pengujian

Pengujian API dapat dilakukan menggunakan Postman.

Contoh login:

```http
POST /api/login
```

Body:

```json
{
    "email": "admin@agen.com",
    "password": "admin123"
}
```

Untuk endpoint yang dilindungi, gunakan:

```text
Authorization: Bearer <token>
```

Pengujian lebih lengkap didokumentasikan pada:

```text
TESTING.md
```

### Menjalankan Test Laravel

Dari folder backend:

```powershell
php artisan test
```

### Build Test Frontend

Dari folder frontend:

```powershell
npm run build
```

Build dianggap berhasil apabila Vite menyelesaikan proses tanpa error.

---

## 21. Known Issues / Limitations

Beberapa keterbatasan pada versi saat ini:

1. Sistem belum menyediakan registrasi akun melalui frontend.
2. Akun Admin dan Owner dibuat untuk kebutuhan latihan/demonstrasi.
3. Belum tersedia fitur lupa/reset password melalui email.
4. Belum tersedia export laporan PDF atau Excel.
5. Belum tersedia pagination pada daftar transaksi.
6. Belum tersedia halaman manajemen user.
7. Data transaksi belum mencatat user pembuat transaksi melalui foreign key `user_id`.
8. Aplikasi masih dikonfigurasi untuk development lokal.
9. Token autentikasi frontend disimpan pada local storage untuk kebutuhan project latihan.

---

## 22. Repository

Repository GitHub:

https://github.com/nursintianabila/sistem-transaksi-agen

Branch utama:

```text
main
```

---

## 23. Commit / Hash

Initial commit project:

```text
edc9e43
```

Commit tersebut merupakan commit awal repository yang berisi backend Laravel dan frontend Vue. Commit awal tercatat berisi 106 file. 

Untuk mendapatkan hash commit terbaru/final gunakan:

```powershell
git rev-parse HEAD
```

Untuk melihat commit:

```powershell
git log --oneline
```

> Pada saat pengumpulan, bagian hash final harus diperbarui menggunakan hasil `git rev-parse HEAD` setelah seluruh dokumentasi dan perubahan terakhir sudah di-commit.

---

## 24. Tag Final

Setelah project benar-benar selesai dan seluruh file sudah di-commit, tag final dapat dibuat dengan:

```powershell
git tag -a v1.0.0 -m "Final UTS Sistem Transaksi Agen"
git push origin v1.0.0
```

Tag final:

```text
v1.0.0
```

Sebelum membuat tag, pastikan:

```powershell
git status
```

menghasilkan:

```text
nothing to commit, working tree clean
```

---

## 25. Repository Final

Repository:

```text
https://github.com/nursintianabila/sistem-transaksi-agen
```

Branch:

```text
main
```

Tag final:

```text
v1.0.0
```

Hash final:

```text
DIISI SETELAH COMMIT DOKUMENTASI TERAKHIR
```

---

## Dokumentasi Testing

Dokumentasi pengujian aplikasi tersedia pada:

```text
TESTING.md
```