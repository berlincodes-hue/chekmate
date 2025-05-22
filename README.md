# Laravel To Do List App 📝

Ini adalah aplikasi **To Do List sederhana** berbasis Laravel. Project ini memungkinkan pengguna untuk membuat, melihat, mengedit, dan menghapus daftar tugas.

## 🚀 Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project ini secara lokal:

### 1. Clone Repository
```bash
git clone <URL_REPO_KAMU>
cd nama-folder-project
````

### 2. Install Dependencies

```bash
composer install
```

Jika menggunakan front-end dengan npm:

```bash
npm install
```

### 3. Copy File Environment

```bash
cp .env.example .env
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Konfigurasi Database

Buka file `.env` dan sesuaikan bagian database seperti ini:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Buat Database di MySQL

Buat database baru bernama `laravel`:

```sql
CREATE DATABASE laravel;
```

### 7. Jalankan Migrasi (dan Seeder jika ada)

```bash
php artisan migrate
# Jika ada data awal:
# php artisan db:seed
```

### 8. Jalankan Aplikasi Laravel

```bash
php artisan serve
```

Akses aplikasimu di [http://localhost:8000](http://localhost:8000)

---

## ✅ Fitur Aplikasi

* Tambah tugas baru
* Lihat daftar tugas
* Edit tugas
* Hapus tugas
* Validasi input sederhana

---

## 📌 Kebutuhan Sistem

* PHP 8.x
* Composer
* MySQL
* Node.js & npm (jika menggunakan front-end framework)
* Laravel 9/10

---

Happy coding & selamat mencoba! 🎉
