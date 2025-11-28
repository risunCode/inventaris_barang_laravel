# ⚠️ ARCHIVED - Historical Reference

**This document is archived. Please see the current technical specification:**

👉 **[TECHNICAL_SPEC.md](./TECHNICAL_SPEC.md)** ← Current Implementation Details

---

# 📦 Aplikasi Inventaris Barang (ARCHIVED)
## Sistem Manajemen Aset & Inventaris Perkantoran - Original Proposal

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=flat-square&logo=laravel)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-blue?style=flat-square&logo=tailwindcss)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple?style=flat-square&logo=php)

> **Note:** This is the original planning document. For the actual implemented system with exact versions and current features, please refer to [TECHNICAL_SPEC.md](./TECHNICAL_SPEC.md).

---

## 📋 Daftar Isi

- [Overview](#-overview)
- [Tech Stack](#-tech-stack)
- [Fitur Utama](#-fitur-utama)
- [Database Schema](#-database-schema)
- [Struktur Folder](#-struktur-folder)
- [Role & Permission](#-role--permission)
- [Laporan & Cetak](#-laporan--cetak)
- [UI/UX Design](#-uiux-design)
- [API Endpoints](#-api-endpoints)
- [Installation](#-installation)
- [Development Roadmap](#-development-roadmap)

---

## 🎯 Overview

### Tujuan Aplikasi
Aplikasi inventaris barang berbasis web untuk **instansi perkantoran** dengan fitur:
- Pencatatan aset/barang inventaris
- Tracking perpindahan barang antar ruangan/lokasi
- Maintenance & perawatan berkala
- Penghapusan/disposal aset
- Laporan inventaris (Print PDF)
- Berita Acara Transfer
- Kartu Inventaris Barang (KIB)

### Target User
- **Instansi Pemerintah** (Dinas, Badan, Kantor)
- **BUMN/BUMD**
- **Perusahaan Swasta**
- **Sekolah/Universitas**
- **Rumah Sakit**

### Prinsip Desain
```
✅ Simple & Clean       - Mudah digunakan oleh semua level user
✅ Mobile-First         - Responsive di semua device
✅ Print-Ready          - Laporan siap cetak format resmi
✅ Offline-Capable      - Data tetap bisa diakses (PWA)
✅ Audit Trail          - Semua perubahan tercatat
```

---

## 🛠 Tech Stack

### Backend
| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 12.x | PHP Framework |
| **PHP** | 8.2+ | Server-side language |
| **MySQL** | 8.0+ | Database |
| **Spatie Permission** | 6.x | Role & Permission management |
| **Laravel DomPDF** | 2.x | Generate PDF reports |
| **Maatwebsite Excel** | 3.x | Import/Export Excel |
| **Intervention Image** | 3.x | Image processing |

### Frontend
| Technology | Version | Purpose |
|------------|---------|---------|
| **TailwindCSS** | 3.x | CSS Framework |
| **Alpine.js** | 3.x | Lightweight JS framework |
| **SweetAlert2** | 11.x | Beautiful alerts & modals |
| **Heroicons** | 2.x | Icon library |
| **Chart.js** | 4.x | Dashboard charts |
| **Vite** | 5.x | Build tool |

### Optional (Phase 2+)
| Technology | Purpose |
|------------|---------|
| **Laravel Scout** | Full-text search |
| **Picqer Barcode** | Generate barcode labels |
| **Simple QRCode** | Generate QR codes |
| **Laravel Backup** | Automated backups |

---

## ✨ Fitur Utama

### 1. 📦 Manajemen Barang (Commodities)
- [x] CRUD barang inventaris
- [x] Kode barang otomatis (format: `INV-2024-001`)
- [x] Multiple foto per barang
- [x] Kategori barang (hierarchical)
- [x] Lokasi/ruangan barang
- [x] Kondisi barang (Baik/Rusak Ringan/Rusak Berat)
- [x] Tahun perolehan & harga
- [x] Penanggung jawab barang
- [x] Soft delete (arsip)
- [x] Search & filter advanced
- [x] Import dari Excel
- [x] Export ke Excel/PDF

### 2. 🔄 Transfer Barang
- [x] Request transfer antar lokasi
- [x] Approval workflow (Manager)
- [x] Tracking status transfer
- [x] Cetak Berita Acara Transfer
- [x] History transfer per barang
- [x] Notifikasi ke pihak terkait

### 3. 🔧 Maintenance/Perawatan
- [x] Log maintenance per barang
- [x] Jadwal maintenance berkala
- [x] Reminder maintenance due
- [x] Biaya maintenance
- [x] History perawatan

### 4. 🗑 Penghapusan/Disposal
- [x] Request penghapusan barang
- [x] Alasan: Rusak/Dijual/Hilang/Usang/Dihibahkan
- [x] Approval workflow
- [x] Cetak Berita Acara Penghapusan
- [x] Nilai sisa/jual

### 5. 👥 Manajemen User
- [x] Role-based access control
- [x] User management (CRUD)
- [x] Password reset via Security Questions
- [x] Activity log per user
- [x] Profile management

### 6. 📊 Dashboard & Statistik
- [x] Total barang per kategori
- [x] Total barang per lokasi
- [x] Grafik perolehan per tahun
- [x] Grafik kondisi barang
- [x] Barang terbaru
- [x] Transfer pending
- [x] Maintenance due
- [x] Quick actions

### 7. 📄 Laporan & Cetak (PENTING!)
| Laporan | Format | Keterangan |
|---------|--------|------------|
| **Daftar Inventaris** | PDF/Excel | Per lokasi, kategori, tahun |
| **Kartu Inventaris Barang (KIB)** | PDF | Per item, format resmi |
| **Berita Acara Transfer** | PDF | Dokumen serah terima |
| **Berita Acara Penghapusan** | PDF | Dokumen disposal |
| **Laporan Kondisi Barang** | PDF/Excel | Summary kondisi |
| **Laporan Maintenance** | PDF/Excel | History perawatan |
| **Rekapitulasi Tahunan** | PDF/Excel | Laporan akhir tahun |
| **Label Barcode** | PDF | Cetak label aset |

### 8. 🔔 Notifikasi
- [x] Transfer request (ke Manager)
- [x] Transfer approved/rejected (ke Requester)
- [x] Maintenance due reminder
- [x] Disposal request (ke Admin)
- [x] In-app notification bell
- [x] SweetAlert2 toast

---

## 🗄 Database Schema

### Entity Relationship Diagram (ERD)

```
┌─────────────────┐       ┌─────────────────┐
│     users       │       │     roles       │
├─────────────────┤       ├─────────────────┤
│ id              │───┐   │ id              │
│ name            │   │   │ name            │
│ email           │   │   │ guard_name      │
│ password        │   │   └─────────────────┘
│ phone           │   │            │
│ avatar          │   │            │ (spatie)
│ is_active       │   │            ▼
│ security_q1     │   │   ┌─────────────────┐
│ security_a1     │   │   │ model_has_roles │
│ security_q2     │   └──►├─────────────────┤
│ security_a2     │       │ role_id         │
│ created_at      │       │ model_type      │
│ updated_at      │       │ model_id        │
└─────────────────┘       └─────────────────┘

┌─────────────────┐       ┌─────────────────┐
│   categories    │       │    locations    │
├─────────────────┤       ├─────────────────┤
│ id              │       │ id              │
│ name            │       │ name            │
│ parent_id (FK)  │       │ description     │
│ description     │       │ created_at      │
│ created_at      │       └─────────────────┘
└─────────────────┘               │
        │                         │
        │                         │
        ▼                         ▼
┌───────────────────────────────────────────┐
│              commodities                   │
├───────────────────────────────────────────┤
│ id                 BIGINT PK AUTO         │
│ item_code          VARCHAR(50) UNIQUE     │
│ name               VARCHAR(255)           │
│ category_id        BIGINT FK              │
│ location_id        BIGINT FK              │
│ brand              VARCHAR(100)           │
│ acquisition_type   ENUM                   │
│ quantity           INT DEFAULT 1          │
│ condition          TINYINT (1-5)          │
│ purchase_year      YEAR                   │
│ purchase_price     DECIMAL(15,2)          │
│ notes              TEXT                   │
│ responsible_person VARCHAR(255)           │
│ created_by         BIGINT FK              │
│ updated_by         BIGINT FK              │
│ created_at         TIMESTAMP              │
│ updated_at         TIMESTAMP              │
│ deleted_at         TIMESTAMP (soft)       │
└───────────────────────────────────────────┘
        │
        │ 1:N
        ▼
┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐
│commodity_images │  │    transfers    │  │maintenance_logs │
├─────────────────┤  ├─────────────────┤  ├─────────────────┤
│ id              │  │ id              │  │ id              │
│ commodity_id FK │  │ commodity_id FK │  │ commodity_id FK │
│ image_path      │  │ from_location   │  │ maintenance_date│
│ is_primary      │  │ to_location     │  │ description     │
│ created_at      │  │ requested_by FK │  │ cost            │
└─────────────────┘  │ approved_by FK  │  │ performed_by    │
                     │ status ENUM     │  │ next_maintenance│
                     │ reason          │  │ created_at      │
                     │ rejection_reason│  └─────────────────┘
                     │ transfer_date   │
                     │ created_at      │
                     │ updated_at      │
                     └─────────────────┘

┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐
│    disposals    │  │  activity_logs  │  │  notifications  │
├─────────────────┤  ├─────────────────┤  ├─────────────────┤
│ id              │  │ id              │  │ id UUID         │
│ commodity_id FK │  │ user_id FK      │  │ type            │
│ disposal_date   │  │ action          │  │ notifiable_type │
│ reason ENUM     │  │ model_type      │  │ notifiable_id   │
│ disposal_value  │  │ model_id        │  │ data JSON       │
│ notes           │  │ description     │  │ read_at         │
│ requested_by FK │  │ ip_address      │  │ created_at      │
│ approved_by FK  │  │ created_at      │  │ updated_at      │
│ status ENUM     │  └─────────────────┘  └─────────────────┘
│ created_at      │
│ updated_at      │
└─────────────────┘
```

### Total Tables: 12
1. `users` - Data pengguna
2. `roles` - Daftar role (Spatie)
3. `permissions` - Daftar permission (Spatie)
4. `model_has_roles` - Pivot user-role (Spatie)
5. `model_has_permissions` - Pivot user-permission (Spatie)
6. `role_has_permissions` - Pivot role-permission (Spatie)
7. `categories` - Kategori barang
8. `locations` - Lokasi/ruangan
9. `commodities` - Data barang utama
10. `commodity_images` - Foto barang
11. `transfers` - Transfer barang
12. `maintenance_logs` - Log perawatan
13. `disposals` - Penghapusan barang
14. `activity_logs` - Audit trail
15. `notifications` - Notifikasi (Laravel default)

---

## 📁 Struktur Folder

```
inventaris-barang/
├── app/
│   ├── Enums/
│   │   ├── AcquisitionType.php      # purchase, grant, donation, etc
│   │   ├── ConditionType.php        # baik, rusak_ringan, rusak_berat
│   │   ├── TransferStatus.php       # pending, approved, rejected, completed
│   │   └── DisposalReason.php       # sold, damaged, obsolete, donated, lost
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   └── SecurityQuestionController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── CommodityController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── LocationController.php
│   │   │   ├── TransferController.php
│   │   │   ├── MaintenanceController.php
│   │   │   ├── DisposalController.php
│   │   │   ├── UserController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── ReportController.php          # Generate reports
│   │   │   └── PrintController.php           # Print documents
│   │   │
│   │   ├── Middleware/
│   │   │   └── CheckSecurityQuestions.php
│   │   │
│   │   └── Requests/
│   │       ├── StoreCommodityRequest.php
│   │       ├── UpdateCommodityRequest.php
│   │       ├── StoreTransferRequest.php
│   │       └── ...
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── Location.php
│   │   ├── Commodity.php
│   │   ├── CommodityImage.php
│   │   ├── Transfer.php
│   │   ├── MaintenanceLog.php
│   │   ├── Disposal.php
│   │   └── ActivityLog.php
│   │
│   ├── Notifications/
│   │   ├── TransferRequestNotification.php
│   │   ├── TransferApprovedNotification.php
│   │   ├── TransferRejectedNotification.php
│   │   ├── MaintenanceDueNotification.php
│   │   └── DisposalRequestNotification.php
│   │
│   ├── Observers/
│   │   ├── CommodityObserver.php
│   │   └── TransferObserver.php
│   │
│   ├── Policies/
│   │   ├── CommodityPolicy.php
│   │   ├── TransferPolicy.php
│   │   ├── DisposalPolicy.php
│   │   └── UserPolicy.php
│   │
│   ├── Services/
│   │   ├── CommodityService.php
│   │   ├── ReportService.php
│   │   ├── ExportService.php
│   │   └── ImportService.php
│   │
│   └── Traits/
│       ├── HasActivityLog.php
│       └── GeneratesItemCode.php
│
├── config/
│   ├── security_questions.php        # Daftar pertanyaan keamanan
│   └── inventory.php                 # Config inventory
│
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 2024_01_01_000001_create_categories_table.php
│   │   ├── 2024_01_01_000002_create_locations_table.php
│   │   ├── 2024_01_01_000003_create_commodities_table.php
│   │   ├── 2024_01_01_000004_create_commodity_images_table.php
│   │   ├── 2024_01_01_000005_create_transfers_table.php
│   │   ├── 2024_01_01_000006_create_maintenance_logs_table.php
│   │   ├── 2024_01_01_000007_create_disposals_table.php
│   │   ├── 2024_01_01_000008_create_activity_logs_table.php
│   │   └── 2024_01_01_000009_create_notifications_table.php
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RolePermissionSeeder.php
│       ├── UserSeeder.php
│       ├── CategorySeeder.php
│       ├── LocationSeeder.php
│       └── DummyDataSeeder.php
│
├── resources/
│   ├── css/
│   │   └── app.css                   # TailwindCSS imports
│   │
│   ├── js/
│   │   ├── app.js                    # Alpine.js + SweetAlert2
│   │   └── components/
│   │       ├── modal.js
│   │       ├── datatable.js
│   │       └── image-upload.js
│   │
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php         # Main layout
│       │   ├── guest.blade.php       # Auth layout
│       │   ├── sidebar.blade.php
│       │   └── navigation.blade.php
│       │
│       ├── components/
│       │   ├── alert.blade.php
│       │   ├── button.blade.php
│       │   ├── card.blade.php
│       │   ├── modal.blade.php       # Wide modal with grid
│       │   ├── table.blade.php
│       │   ├── badge.blade.php
│       │   ├── notification-bell.blade.php
│       │   └── form/
│       │       ├── input.blade.php
│       │       ├── select.blade.php
│       │       ├── textarea.blade.php
│       │       └── file-upload.blade.php
│       │
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── forgot-password.blade.php
│       │   ├── security-questions.blade.php
│       │   └── setup-security.blade.php
│       │
│       ├── dashboard/
│       │   └── index.blade.php
│       │
│       ├── commodities/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   ├── show.blade.php
│       │   └── _form.blade.php       # Partial form
│       │
│       ├── categories/
│       │   └── index.blade.php       # CRUD in modal
│       │
│       ├── locations/
│       │   └── index.blade.php       # CRUD in modal
│       │
│       ├── transfers/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── show.blade.php
│       │   └── _approval-modal.blade.php
│       │
│       ├── maintenance/
│       │   ├── index.blade.php
│       │   └── _form-modal.blade.php
│       │
│       ├── disposals/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── show.blade.php
│       │
│       ├── users/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── profile.blade.php
│       │
│       ├── reports/
│       │   ├── index.blade.php       # Report generator
│       │   ├── inventory.blade.php
│       │   └── summary.blade.php
│       │
│       ├── print/                    # Print templates (clean, no nav)
│       │   ├── layouts/
│       │   │   └── print.blade.php   # Print layout
│       │   ├── inventory-list.blade.php
│       │   ├── kib.blade.php         # Kartu Inventaris Barang
│       │   ├── transfer-ba.blade.php # Berita Acara Transfer
│       │   ├── disposal-ba.blade.php # Berita Acara Penghapusan
│       │   ├── condition-report.blade.php
│       │   └── barcode-label.blade.php
│       │
│       └── notifications/
│           └── index.blade.php
│
├── routes/
│   ├── web.php
│   └── auth.php
│
├── storage/
│   └── app/public/
│       ├── commodities/              # Foto barang
│       ├── exports/                  # Generated files
│       └── imports/                  # Upload imports
│
├── public/
│   ├── images/
│   │   ├── logo.png
│   │   └── default-item.png
│   └── print.css                     # Print stylesheet
│
├── .env.example
├── composer.json
├── package.json
├── tailwind.config.js
├── vite.config.js
└── README.md
```

---

## 👤 Role & Permission

### Roles

| Role | Level | Description |
|------|-------|-------------|
| **Super Admin** | 1 | Full access, system config |
| **Admin** | 2 | Manage all, approve disposal |
| **Manager** | 3 | Approve transfer, manage dept items |
| **Staff** | 4 | CRUD items, request transfer |
| **Viewer** | 5 | View only |

### Permission Matrix

| Permission | Super Admin | Admin | Manager | Staff | Viewer |
|------------|:-----------:|:-----:|:-------:|:-----:|:------:|
| **Dashboard** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Commodities** |||||
| - View | ✅ | ✅ | ✅ | ✅ | ✅ |
| - Create | ✅ | ✅ | ✅ | ✅ | ❌ |
| - Edit | ✅ | ✅ | ✅ | ✅* | ❌ |
| - Delete | ✅ | ✅ | ❌ | ❌ | ❌ |
| - Export | ✅ | ✅ | ✅ | ❌ | ❌ |
| - Import | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Transfers** |||||
| - View | ✅ | ✅ | ✅ | ✅ | ✅ |
| - Create | ✅ | ✅ | ✅ | ✅ | ❌ |
| - Approve | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Disposals** |||||
| - View | ✅ | ✅ | ✅ | ✅ | ✅ |
| - Request | ✅ | ✅ | ✅ | ✅ | ❌ |
| - Approve | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Users** |||||
| - Manage | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Settings** |||||
| - Access | ✅ | ❌ | ❌ | ❌ | ❌ |

*Staff hanya bisa edit barang yang dia buat

---

## 🖨 Laporan & Cetak

### Dokumen yang Bisa Dicetak

#### 1. Daftar Inventaris Barang
```
┌────────────────────────────────────────────────────────────┐
│                    KOP SURAT INSTANSI                      │
│                                                            │
│            DAFTAR INVENTARIS BARANG                        │
│            Tahun Anggaran: 2024                            │
│            Lokasi: Ruang Kepala                            │
├────┬──────────┬────────────┬─────────┬─────────┬──────────┤
│ No │ Kode     │ Nama       │ Merk    │ Kondisi │ Harga    │
├────┼──────────┼────────────┼─────────┼─────────┼──────────┤
│ 1  │ INV-001  │ Komputer   │ HP      │ Baik    │ 8.000.000│
│ 2  │ INV-002  │ Meja Kerja │ Olympic │ Baik    │ 2.500.000│
│ ...│ ...      │ ...        │ ...     │ ...     │ ...      │
├────┴──────────┴────────────┴─────────┴─────────┼──────────┤
│                                    Total       │15.500.000│
└────────────────────────────────────────────────┴──────────┘
                                        [Kota], [Tanggal]
                                        Penanggung Jawab,
                                        
                                        
                                        [Nama]
                                        NIP. xxxx
```

#### 2. Kartu Inventaris Barang (KIB)
```
┌────────────────────────────────────────────────────────────┐
│                KARTU INVENTARIS BARANG                     │
├────────────────────────────────────────────────────────────┤
│ Kode Barang    : INV-2024-001                              │
│ Nama Barang    : Komputer Desktop                          │
│ Merk/Type      : HP ProDesk 400 G7                         │
│ Spesifikasi    : Intel i5, RAM 8GB, SSD 256GB              │
├────────────────────────────────────────────────────────────┤
│ Tahun Perolehan: 2024                                      │
│ Cara Perolehan : Pembelian                                 │
│ Harga Perolehan: Rp 8.000.000                              │
├────────────────────────────────────────────────────────────┤
│ Lokasi         : Ruang Kepala                              │
│ Kondisi        : Baik                                      │
│ Penanggung     : Budi Santoso                              │
├────────────────────────────────────────────────────────────┤
│                    RIWAYAT MUTASI                          │
├────────┬──────────────────┬──────────────────┬─────────────┤
│ Tanggal│ Dari             │ Ke               │ Keterangan  │
├────────┼──────────────────┼──────────────────┼─────────────┤
│10/01/24│ Gudang           │ Ruang Kepala     │ Distribusi  │
└────────┴──────────────────┴──────────────────┴─────────────┘
│                    [BARCODE/QRCODE]                        │
└────────────────────────────────────────────────────────────┘
```

#### 3. Berita Acara Transfer
```
┌────────────────────────────────────────────────────────────┐
│                    KOP SURAT INSTANSI                      │
│                                                            │
│              BERITA ACARA SERAH TERIMA                     │
│                BARANG INVENTARIS                           │
│             Nomor: BA/001/INV/2024                         │
├────────────────────────────────────────────────────────────┤
│                                                            │
│ Pada hari ini, [hari] tanggal [tanggal], kami yang         │
│ bertanda tangan di bawah ini:                              │
│                                                            │
│ 1. Nama     : [Nama Penyerah]                              │
│    Jabatan  : [Jabatan]                                    │
│    Selanjutnya disebut PIHAK PERTAMA                       │
│                                                            │
│ 2. Nama     : [Nama Penerima]                              │
│    Jabatan  : [Jabatan]                                    │
│    Selanjutnya disebut PIHAK KEDUA                         │
│                                                            │
│ Telah melakukan serah terima barang inventaris:            │
├────┬──────────┬────────────┬─────────┬────────────────────┤
│ No │ Kode     │ Nama Barang│ Jumlah  │ Kondisi            │
├────┼──────────┼────────────┼─────────┼────────────────────┤
│ 1  │ INV-001  │ Komputer   │ 1 unit  │ Baik               │
└────┴──────────┴────────────┴─────────┴────────────────────┘
│                                                            │
│ Demikian berita acara ini dibuat untuk dipergunakan        │
│ sebagaimana mestinya.                                      │
│                                                            │
│      PIHAK PERTAMA                    PIHAK KEDUA          │
│                                                            │
│                                                            │
│      [Nama]                           [Nama]               │
│                                                            │
│                       Mengetahui,                          │
│                    Kepala [Instansi]                       │
│                                                            │
│                                                            │
│                       [Nama]                               │
│                    NIP. xxxx                               │
└────────────────────────────────────────────────────────────┘
```

#### 4. Label Barcode
```
┌─────────────────────────────┐
│ |||||||||||||||||||||||||||│
│        INV-2024-001         │
├─────────────────────────────┤
│ Komputer Desktop            │
│ Ruang Kepala                │
│ [QR CODE]                   │
└─────────────────────────────┘
```

### Print Implementation

```php
// routes/web.php
Route::prefix('print')->name('print.')->group(function () {
    Route::get('/inventory', [PrintController::class, 'inventoryList']);
    Route::get('/kib/{commodity}', [PrintController::class, 'kib']);
    Route::get('/transfer/{transfer}', [PrintController::class, 'transferBA']);
    Route::get('/disposal/{disposal}', [PrintController::class, 'disposalBA']);
    Route::get('/barcode/{commodity}', [PrintController::class, 'barcodeLabel']);
});

// Export PDF
Route::get('/export/inventory/pdf', [ReportController::class, 'exportPDF']);
Route::get('/export/inventory/excel', [ReportController::class, 'exportExcel']);
```

---

## 🎨 UI/UX Design

### Color Palette
```css
/* Primary - Blue */
--primary-50: #eff6ff;
--primary-500: #3b82f6;
--primary-600: #2563eb;
--primary-700: #1d4ed8;

/* Success - Green */
--success-500: #22c55e;

/* Warning - Yellow */
--warning-500: #eab308;

/* Danger - Red */
--danger-500: #ef4444;

/* Neutral - Gray */
--gray-50: #f9fafb;
--gray-100: #f3f4f6;
--gray-500: #6b7280;
--gray-900: #111827;
```

### Component Examples

#### Wide Modal with Grid
```html
<!-- Modal Tambah Barang -->
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50"></div>
        
        <!-- Modal Content - Wide -->
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-4xl">
            <!-- Header -->
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">Tambah Barang</h3>
                <button class="text-gray-400 hover:text-gray-600">✕</button>
            </div>
            
            <!-- Body - Grid -->
            <div class="p-6">
                <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <label>Nama Barang *</label>
                            <input type="text" class="input">
                        </div>
                        <div>
                            <label>Kategori *</label>
                            <select class="input"></select>
                        </div>
                        <div>
                            <label>Merk/Brand</label>
                            <input type="text" class="input">
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div>
                            <label>Lokasi *</label>
                            <select class="input"></select>
                        </div>
                        <div>
                            <label>Kondisi *</label>
                            <select class="input"></select>
                        </div>
                        <div>
                            <label>Harga Perolehan</label>
                            <input type="number" class="input">
                        </div>
                    </div>
                    
                    <!-- Full Width -->
                    <div class="md:col-span-2">
                        <label>Foto Barang</label>
                        <div class="border-2 border-dashed rounded-lg p-8 text-center">
                            Drop files here or click to upload
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label>Keterangan</label>
                        <textarea class="input" rows="3"></textarea>
                    </div>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 border-t flex justify-end gap-3">
                <button class="btn btn-secondary">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
```

#### SweetAlert2 Integration
```javascript
// resources/js/app.js
import Swal from 'sweetalert2';

window.Swal = Swal;

// Toast notification
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});

// Confirm delete
window.confirmDelete = (url) => {
    Swal.fire({
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form or make request
        }
    });
};

// Success notification
window.showSuccess = (message) => {
    Toast.fire({ icon: 'success', title: message });
};

// Error notification  
window.showError = (message) => {
    Toast.fire({ icon: 'error', title: message });
};
```

---

## 🔌 API Endpoints

### Authentication
```
POST   /login                    # Login
POST   /logout                   # Logout
GET    /forgot-password          # Show forgot password
POST   /forgot-password          # Verify email
POST   /verify-security          # Verify security answers
POST   /reset-password           # Reset password
```

### Dashboard
```
GET    /dashboard                # Dashboard page
```

### Commodities
```
GET    /commodities              # List all
GET    /commodities/create       # Create form
POST   /commodities              # Store new
GET    /commodities/{id}         # Show detail
GET    /commodities/{id}/edit    # Edit form
PUT    /commodities/{id}         # Update
DELETE /commodities/{id}         # Soft delete
POST   /commodities/import       # Import Excel
GET    /commodities/export       # Export Excel/PDF
```

### Categories & Locations (AJAX)
```
GET    /categories               # List (JSON)
POST   /categories               # Create
PUT    /categories/{id}          # Update
DELETE /categories/{id}          # Delete

GET    /locations                # List (JSON)
POST   /locations                # Create
PUT    /locations/{id}           # Update
DELETE /locations/{id}           # Delete
```

### Transfers
```
GET    /transfers                # List all
GET    /transfers/create         # Create form
POST   /transfers                # Store request
GET    /transfers/{id}           # Show detail
POST   /transfers/{id}/approve   # Approve
POST   /transfers/{id}/reject    # Reject
POST   /transfers/{id}/complete  # Mark complete
```

### Maintenance
```
GET    /commodities/{id}/maintenance      # List per commodity
POST   /commodities/{id}/maintenance      # Add log
PUT    /maintenance/{id}                  # Update
DELETE /maintenance/{id}                  # Delete
```

### Disposals
```
GET    /disposals                # List all
GET    /disposals/create         # Create form
POST   /disposals                # Store request
GET    /disposals/{id}           # Show detail
POST   /disposals/{id}/approve   # Approve
POST   /disposals/{id}/reject    # Reject
```

### Reports & Print
```
GET    /reports                  # Report generator page
GET    /reports/inventory        # Inventory report
GET    /reports/condition        # Condition report
GET    /reports/transfer         # Transfer report

GET    /print/inventory          # Print inventory list
GET    /print/kib/{id}           # Print KIB
GET    /print/transfer/{id}      # Print BA Transfer
GET    /print/disposal/{id}      # Print BA Disposal
GET    /print/barcode/{id}       # Print barcode label
```

### Users & Settings
```
GET    /users                    # List users
GET    /users/create             # Create form
POST   /users                    # Store
GET    /users/{id}/edit          # Edit form
PUT    /users/{id}               # Update
DELETE /users/{id}               # Delete

GET    /profile                  # My profile
PUT    /profile                  # Update profile
GET    /profile/security         # Security settings
PUT    /profile/security         # Update security questions
```

### Notifications
```
GET    /notifications            # List all
POST   /notifications/read/{id}  # Mark as read
POST   /notifications/read-all   # Mark all as read
DELETE /notifications/{id}       # Delete
```

---

## 🚀 Installation

### Requirements
- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 18.x
- MySQL >= 8.0

### Steps

```bash
# 1. Clone repository
git clone https://github.com/username/inventaris-barang.git
cd inventaris-barang

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris_barang
DB_USERNAME=root
DB_PASSWORD=

# 6. Run migrations & seeders
php artisan migrate --seed

# 7. Create storage link
php artisan storage:link

# 8. Build assets
npm run build

# 9. Start development server
php artisan serve
npm run dev
```

### Default Users

| Email | Password | Role |
|-------|----------|------|
| superadmin@example.com | password | Super Admin |
| admin@example.com | password | Admin |
| manager@example.com | password | Manager |
| staff@example.com | password | Staff |
| viewer@example.com | password | Viewer |

---

## 📅 Development Roadmap

### Phase 1: Foundation (Week 1-2) ✅
- [x] Project setup (Laravel 12 + TailwindCSS)
- [x] Database migrations
- [x] Authentication (Login, Security Questions)
- [x] Role & Permission setup (Spatie)
- [x] Base layout & components
- [x] SweetAlert2 integration

### Phase 2: Core Features (Week 3-4)
- [ ] Category CRUD (modal)
- [ ] Location CRUD (modal)
- [ ] Commodity CRUD (full page + modal)
- [ ] Image upload (multiple)
- [ ] Search & filter
- [ ] Dashboard statistics

### Phase 3: Transactions (Week 5-6)
- [ ] Transfer request system
- [ ] Transfer approval workflow
- [ ] Maintenance logging
- [ ] Disposal request & approval
- [ ] Notifications

### Phase 4: Reports (Week 7)
- [ ] Inventory list report
- [ ] KIB (Kartu Inventaris Barang)
- [ ] Berita Acara Transfer
- [ ] Berita Acara Disposal
- [ ] Export Excel
- [ ] Export PDF (DomPDF)

### Phase 5: Enhancement (Week 8)
- [ ] Import from Excel
- [ ] Barcode/QR code label
- [ ] Activity log viewer
- [ ] User management
- [ ] Profile & security settings

### Phase 6: Polish (Week 9-10)
- [ ] Performance optimization
- [ ] Security audit
- [ ] Bug fixes
- [ ] Documentation
- [ ] Deployment

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=CommodityTest

# Run with coverage
php artisan test --coverage
```

---

## 📝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📄 License

MIT License - see [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**Ferdi**

- GitHub: [@ferdi](https://github.com/ferdi)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com)
- [TailwindCSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
- [SweetAlert2](https://sweetalert2.github.io)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf)
- [Maatwebsite Excel](https://laravel-excel.com)

---

**Last Updated:** 2025-11-26  
**Version:** 1.0.0  
**Status:** Planning Complete ✅ - Ready for Development 🚀
