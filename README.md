# Poliklinik Al-Huda

Sistem pengurusan klinik komprehensif yang dibangunkan menggunakan Laravel 12. Aplikasi ini menyediakan dua bahagian utama: portal awam untuk pesakit dan panel pentadbiran untuk kakitangan klinik.

## 🏥 Ciri-ciri Utama

### Portal Awam (Public Portal)
- **Laman Utama**: Paparan maklumat klinik dan perkhidmatan
- **Tentang Kami**: Maklumat terperinci mengenai klinik
- **Rawatan**: Senarai perkhidmatan dan rawatan yang tersedia
- **Temujanji**: Borang permohonan temujanji dalam talian

### Panel Pentadbiran (Admin Panel)
- **Dashboard**: Papan pemuka dengan statistik dan maklumat penting
- **Pengurusan Temujanji**: Lihat dan urus temujanji pesakit
- **Pengurusan Perkhidmatan**: Tambah, edit dan urus perkhidmatan klinik
- **Sistem Login**: Akses selamat untuk kakitangan

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Bootstrap 5, Sass
- **Database**: MySQL
- **Build Tools**: Vite
- **Authentication**: Laravel Auth

## 📋 Keperluan Sistem

- PHP >= 8.2
- Composer
- Node.js >= 16
- MySQL >= 8.0
- Web Server (Apache/Nginx)

## 🚀 Panduan Setup

### 1. Clone Repository
```bash
git clone https://github.com/afiez97/poliklinikalhuda.git
cd poliklinikalhuda
```

### 2. Install Dependencies

#### Backend Dependencies (PHP)
```bash
composer install
```

#### Frontend Dependencies (Node.js)
```bash
npm install
```

### 3. Environment Configuration

#### Salin fail environment
```bash
cp .env.example .env
```

#### Generate Application Key
```bash
php artisan key:generate
```

#### Konfigurasi Database
Edit fail `.env` dan set maklumat database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=poliklinikalhuda
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### Konfigurasi Aplikasi
```env
APP_NAME="Poliklinik Al-Huda"
APP_URL=http://localhost:8000
APP_ENV=local
APP_DEBUG=true
```

### 4. Database Setup

#### Buat Database
```bash
mysql -u root -p
CREATE DATABASE poliklinikalhuda;
exit
```

#### Jalankan Migration
```bash
php artisan migrate
```

#### Seed Data (Pilihan)
```bash
php artisan db:seed
```

### 5. Build Assets

#### Development
```bash
npm run dev
```

#### Production
```bash
npm run build
```

### 6. Jalankan Aplikasi

#### Development Server
```bash
php artisan serve
```

Aplikasi akan tersedia di: http://localhost:8000

#### Production Setup
Untuk deployment production, configure web server untuk point ke folder `public/`.

## 📁 Struktur Project

```
poliklinikalhuda/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php      # Kawalan admin
│   │   ├── PortalController.php     # Kawalan portal awam
│   │   └── Controller.php
│   ├── Models/
│   │   └── User.php
│   └── View/Components/             # Komponen view
├── resources/
│   ├── views/
│   │   ├── admin/                   # Views admin
│   │   ├── portal/                  # Views portal awam
│   │   ├── components/              # Komponen views
│   │   └── layouts/                 # Layout templates
│   ├── js/
│   └── scss/
├── public/
│   ├── assets-admin/                # Asset admin panel
│   ├── assets-portal/               # Asset portal awam
│   └── medical/                     # Template HTML medical
├── database/
│   ├── migrations/
│   └── seeders/
└── routes/
    └── web.php                      # Route definitions
```

## 🔗 Routes Available

### Portal Awam
- `/` - Laman utama
- `/about` - Tentang kami
- `/treatments` - Rawatan
- `/appointment` - Borang temujanji

### Admin Panel
- `/admin/login` - Login pentadbir
- `/admin/dashboard` - Dashboard
- `/admin/appointments` - Pengurusan temujanji
- `/admin/services` - Pengurusan perkhidmatan

## 🔐 User Authentication

Sistem menggunakan Laravel Authentication untuk kawalan akses admin panel. Pastikan buat user admin melalui seeder atau manual dalam database.

## 🛡️ Security Features

- CSRF Protection
- SQL Injection Prevention
- XSS Protection
- Secure Session Management
- Password Hashing

## 📝 Development Notes

### Code Style
Project ini menggunakan Laravel Pint untuk code formatting:
```bash
./vendor/bin/pint
```

### Testing
Jalankan test menggunakan PHPUnit:
```bash
php artisan test
```

## 🤝 Contributing

1. Fork repository
2. Buat branch feature (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 License

Project ini menggunakan [MIT license](LICENSE).

## 👨‍💻 Developer

Dibangunkan oleh **afiez97**

## 📞 Support

Untuk sokongan teknikal atau pertanyaan, sila hubungi melalui:
- GitHub Issues: [Create Issue](https://github.com/afiez97/poliklinikalhuda/issues)
- Email: [your-email@example.com]

---

**Poliklinik Al-Huda** - Sistem Pengurusan Klinik Moden 🏥
