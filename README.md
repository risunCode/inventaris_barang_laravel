# 🏢 SIBARANG - Sistem Inventaris Barang

**Version: 0.0.7-beta-007** 🎉

Sistem manajemen inventaris barang yang komprehensif untuk instansi pemerintah, BUMN/BUMD, dan perusahaan swasta. Dibangun dengan Laravel 12 dan teknologi modern.

---

## 🚀 Quick Start for Organizations

**Clone & Setup in 5 Minutes:**
```bash
git clone https://github.com/risunCode/inventaris_barang_laravel.git your-inventory
cd your-inventory && composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate && php artisan db:seed
npm run build && php artisan serve
```

🎉 **Access:** http://127.0.0.1:8000  
🔑 **Login:** admin@inventory.com / password

📋 **Need customization?** See our [Deployment Guide](DEPLOYMENT.md) and [Customization Guide](CUSTOMIZATION.md)

---

## 📋 Daftar Isi

- [🚀 Quick Start for Organizations](#-quick-start-for-organizations)
- [🚀 Features Utama](#-features-utama)
- [📸 Screenshots](#-screenshots)
- [🛠️ Technical Stack](#️-technical-stack)
- [📚 Documentation Guides](#-documentation-guides)
  - [🚀 Deployment Guide](-deployment-guide)
  - [🎨 Customization Guide](-customization-guide)
- [🚀 Installation & Deployment Guide](#-installation--deployment-guide)
  - [Prerequisites](#prerequisites)
  - [Quick Start (Development)](#quick-start-development)
  - [Default Login](#default-login)
  - [Production Deployment](#production-deployment)
  - [LAN Hosting (Optional)](#lan-hosting-optional)
  - [Troubleshooting](#troubleshooting)
- [📱 Browser Support](#-browser-support)
- [📝 Version History](#-version-history)

---

## 🚀 Features Utama

### 📊 Dashboard & Analytics
- Dashboard real-time dengan Chart.js
- Statistik kondisi barang (Donut chart)
- Distribusi per kategori (Bar chart)  
- Alerts untuk approval pending
- Sticky navigation untuk UX yang lebih baik

### 🗂️ Master Data Management
- **Categories**: CRUD dengan modal edit/delete + SweetAlert
- **Locations**: CRUD dengan modal edit/delete + SweetAlert  
- **Commodities**: CRUD lengkap dengan gallery preview
- Image gallery dengan zoom, drag, dan navigasi

### 💼 Transaction Management
- **Transfers**: Workflow approval dengan status tracking
- **Maintenance**: Scheduling dan log pemeliharaan
- **Disposals**: Proses penghapusan barang dengan approval

### 👥 User Management & Profile
- **Role-based Access Control (RBAC)** dengan Spatie Laravel Permission
- **Modal-based operations** untuk create/edit users
- **Referral Code System** untuk registrasi user baru
- User details dengan wide layout dan stats
- **Advanced Profile Management** dengan crop photo functionality
  - **Image Cropping** dengan Cropper.js - fixed circular crop area
  - **Drag & Zoom** untuk positioning gambar dalam circle
  - **Keyboard Shortcuts** (Zoom: +/-, Rotate: R, Reset: Space, Save: Enter, Cancel: Esc)
  - **File Validation** (type, size) dengan user-friendly error messages
  - **Real-time Upload** dengan loading states dan progress feedback

### 📝 Reporting System
- Multiple report types (Inventory, By Category, By Location, dll)
- **PDF Export** dengan custom styling
- Modern card-based report selection interface
- Print-friendly layouts

### 🔔 Notification & Activity
- Real-time notification system
- **Activity logging** untuk audit trail
- Notification bell dengan counter
- Activity exclusion untuk login events

### 🎨 UI/UX Enhancements
- **CSS Variables theming** untuk konsistensi
- **SweetAlert integration** untuk feedback yang lebih baik
- **Modal system** untuk operasi CRUD
- **Gallery lightbox** untuk preview gambar
- Responsive design untuk semua device sizes
- Enhanced error handling (development vs production)

---

## 📸 Screenshots

### 🏠 Dashboard Analytics
Dashboard real-time dengan visualisasi data komprehensif dan monitoring status barang.

<img width="1920" height="1080" alt="Dashboard SIBARANG" src="https://github.com/user-attachments/assets/aa9c0f47-6dc0-4851-95fa-5d45c9869b03" />

### 📦 Detail Barang
Interface detail barang yang informatif dengan gallery preview dan informasi lengkap.

<img width="1920" height="1080" alt="Detail Barang SIBARANG" src="https://github.com/user-attachments/assets/8bd6e9c0-1fe2-4bff-9e04-620d8c5b4319" />

### ℹ️ About System
Halaman about dengan informasi sistem lengkap dan teknologi yang digunakan.

<img width="1920" height="1080" alt="About SIBARANG" src="https://github.com/user-attachments/assets/a6edcd66-32ee-4e1c-882f-9f03668aa37f" />

---

## 🛠️ Technical Stack

- **Laravel**: 12.40.1
- **PHP**: 8.3.23
- **Database**: MySQL/SQLite  
- **Frontend**: Tailwind CSS + Alpine.js + Chart.js
- **Permissions**: Spatie Laravel Permission
- **PDF**: DomPDF
- **Notifications**: Laravel native notifications

---

## 📚 Documentation Guides

### 🚀 [Deployment Guide](DEPLOYMENT.md)
Complete deployment documentation for production environments:
- Quick clone & setup instructions
- Production server configuration (Apache/Nginx)
- Environment templates & optimization
- Security hardening & performance tuning
- Troubleshooting common issues

### 🎨 [Customization Guide](CUSTOMIZATION.md)
Brand and customize SIBARANG for your organization:
- Logo, colors, and organization details
- Industry-specific configurations (Healthcare, Education, Corporate)
- Custom fields and validation rules
- Multi-language support setup
- Advanced feature customization

---

## 🚀 Installation & Deployment Guide

### Prerequisites

Pastikan sudah terinstall:

- **PHP 8.2+**
- **Composer 2.x**
- **Node.js 18+** & NPM
- **MySQL 8.0** / MariaDB 10.6+
- **Git**

### Quick Start (Development)

#### Step 1: Clone Repository
```bash
git clone <repository-url>
cd Inventaris-barang-ferdi
```

#### Step 2: Install Dependencies
```bash
composer install
npm install
```

#### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env`:
```env
APP_NAME="SIBARANG - Sistem Inventaris Barang"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sibarang_inventaris
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### Step 4: Database Setup
```bash
# Buat database
mysql -u root -p -e "CREATE DATABASE sibarang_inventaris"

# Jalankan migrations dan seeder
php artisan migrate
php artisan db:seed
```

#### Step 5: Storage Setup
```bash
php artisan storage:link
```

#### Step 6: Build Assets & Run
```bash
# Build frontend assets
npm run build

# Jalankan server
php artisan serve
```

**Akses:** http://127.0.0.1:8000

### Default Login
| Email | Password | Role |
|-------|----------|------|
| admin@inventaris.com | admin123456 | Admin |

---

### Production Deployment

#### 1. Build Assets
```bash
npm run build
```

#### 2. Environment Configuration
Edit `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

#### 3. Laravel Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

#### 4. PHP Settings (php.ini)
```ini
upload_max_filesize = 10M
post_max_size = 50M
max_file_uploads = 20
memory_limit = 256M
max_execution_time = 300

# Required Extensions
extension=fileinfo
extension=gd
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=zip
```

#### 5. File Permissions (Linux)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 6. Web Server Configuration

**Apache (.htaccess already included)**
```apache
<VirtualHost *:80>
    DocumentRoot /path/to/sibarang/public
    ServerName yourdomain.com
    
    <Directory /path/to/sibarang/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Nginx**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/sibarang/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

### LAN Hosting (Optional)

Untuk akses dari perangkat lain di jaringan lokal:

```bash
# Cari IP lokal (Windows)
ipconfig

# Jalankan server dengan host 0.0.0.0
php artisan serve --host=0.0.0.0 --port=8000

# Buka firewall (PowerShell Admin)
netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=tcp localport=8000
```

Akses dari perangkat lain: `http://192.168.x.x:8000`

---

### Troubleshooting

<details>
<summary><strong>Common Issues & Solutions</strong></summary>

**404 Error on Routes**
```bash
php artisan route:clear
php artisan config:clear
```

**Permission Issues (Linux)**
```bash
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

**Assets Not Loading**
```bash
npm run build
php artisan view:clear
```

**Database Connection Error**
- Check database credentials in `.env`
- Ensure MySQL service is running
- Test connection: `php artisan tinker` then `DB::connection()->getPdo()`

</details>

## 📱 Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## 📝 Latest Updates

### v0.0.7-beta-007 (Current) - 28 Nov 2025

**🎯 Major Highlights:**
- ✅ **Enhanced Development Badge** - Professional indicator dengan network info
- ✅ **Added Screenshots Section** - Visual showcase of dashboard, detail barang, dan about pages
- ✅ **Fixed Export PDF 404 errors** - Route precedence resolved
- ✅ **Advanced Profile Photo Cropping** - Fixed circular crop area dengan keyboard shortcuts
- ✅ **Fixed User Profile 404 errors** - Permission middleware resolved
- ✅ **UI/UX Improvements** - Transfer thumbnails, terminology cleanup, smart currency display

**📋 Complete changelog:** [CHANGELOG.md](CHANGELOG.md)

---

**Developed for Kabupaten Kubu Raya** 🏛️