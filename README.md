# Servisin

Aplikasi pemesanan jasa servis elektronik rumah tangga.

A household electronics repair service booking application.

---

🇮🇩 [Bahasa Indonesia](#-bahasa-indonesia) · 🇬🇧 [English](#-english)

---

## 🇮🇩 Bahasa Indonesia

### Tentang

Servisin adalah aplikasi berbasis web untuk pemesanan jasa servis elektronik rumah tangga. Pelanggan dapat memesan layanan perbaikan (AC, TV, mesin cuci, kulkas), memilih jenis kerusakan, mengunggah foto, dan melacak status booking mereka secara real-time.

### Fitur

- **Pemesanan Servis** — Pesan layanan perbaikan elektronik dengan memilih jenis layanan dan kerusakan
- **Katalog Layanan** — Daftar layanan yang tersedia beserta jenis kerusakan dan harga
- **Pelacakan Status** — Pantau status booking dari Menunggu → Teknisi Ditugaskan → Sedang Dikerjakan → Selesai
- **Dashboard** — Ringkasan statistik booking untuk pelanggan dan admin
- **Manajemen Admin** — Kelola layanan, jenis kerusakan, dan pengguna
- **Daftar Pekerjaan** — Teknisi dapat melihat dan mengelola pekerjaan yang ditugaskan
- **Sistem Komplain** — Ajukan keluhan terkait booking dengan lampiran foto
- **Autentikasi Lengkap** — Login, register, verifikasi email, lupa & reset password
- **Mode Gelap** — Dukungan tema terang dan gelap

### Layanan yang Tersedia

| Layanan | Contoh Kerusakan |
|---------|------------------|
| Servis AC | AC tidak dingin, AC bocor, AC mati total, freon habis |
| Servis TV | TV tidak menyala, layar gelap, layar bergaris |
| Servis Mesin Cuci | Mesin tidak menyala, tidak bisa berputar, air tidak masuk |
| Servis Kulkas | Kulkas tidak dingin, bocor air, kompresor tidak jalan |

### Peran Pengguna

- **Pelanggan** — Memesan servis dan melacak status booking
- **Teknisi** — Menerima dan mengerjakan pekerjaan yang ditugaskan
- **Admin** — Mengelola seluruh layanan, pengguna, dan booking

### Tech Stack

- **Laravel 12** — Framework PHP
- **Livewire 4** — Komponen reaktif tanpa JavaScript
- **Tailwind CSS 4** — Utility-first CSS framework
- **Sheaf UI** — Pustaka komponen UI
- **Pest 4** — Framework testing
- **SQLite / MySQL** — Database

### Instalasi

```bash
# Clone repositori
git clone https://github.com/aslammaududy/servisin.git
cd servisin

# Install dependensi
composer install
npm install

# Konfigurasi environment
cp .env.example .env
php artisan key:generate

# Migrasi dan seed database
php artisan migrate --seed

# Build assets
npm run build

# Buat akun admin
php artisan app:create-admin

# Jalankan aplikasi
composer run dev
```

### Testing

```bash
php artisan test
```

---

## 🇬🇧 English

### About

Servisin is a web-based application for booking household electronics repair services. Customers can book repair services (AC, TV, washing machine, refrigerator), select a damage type, upload photos, and track their booking status in real-time.

### Features

- **Service Booking** — Book electronics repair services by selecting the service type and damage
- **Service Catalog** — Browse available services with damage types and pricing
- **Status Tracking** — Track booking status from Pending → Technician Assigned → In Progress → Completed
- **Dashboard** — Booking statistics overview for customers and admins
- **Admin Management** — Manage services, damage types, and users
- **Job List** — Technicians can view and manage assigned jobs
- **Complaint System** — File complaints related to bookings with photo attachments
- **Full Authentication** — Login, register, email verification, forgot & reset password
- **Dark Mode** — Light and dark theme support

### Available Services

| Service | Example Damages |
|---------|----------------|
| AC Repair | AC not cooling, AC leaking, AC dead, freon leak |
| TV Repair | TV won't turn on, blank screen, striped display |
| Washing Machine Repair | Won't start, won't spin, water not flowing in |
| Refrigerator Repair | Not cooling, water leak, compressor failure |

### User Roles

- **Customer** — Book services and track booking status
- **Technician** — Receive and work on assigned jobs
- **Admin** — Manage all services, users, and bookings

### Tech Stack

- **Laravel 12** — PHP framework
- **Livewire 4** — Reactive components without JavaScript
- **Tailwind CSS 4** — Utility-first CSS framework
- **Sheaf UI** — UI component library
- **Pest 4** — Testing framework
- **SQLite / MySQL** — Database

### Installation

```bash
# Clone the repository
git clone https://github.com/aslammaududy/servisin.git
cd servisin

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Migrate and seed the database
php artisan migrate --seed

# Build assets
npm run build

# Create an admin account
php artisan app:create-admin

# Run the application
composer run dev
```

### Testing

```bash
php artisan test
```

---

## Lisensi / License

Open source, licensed under the [MIT License](LICENCE.md).
