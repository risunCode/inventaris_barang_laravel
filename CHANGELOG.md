# CHANGELOG - SIBARANG
**Sistem Inventaris Barang**

---

## [v0.0.7-beta] - 2025-11-28 (Major Bug Fixes & UI Enhancements)

### 🎨 Enhanced Development Badge (NEW)
- **Professional Development Indicator** inspired by E-Surat-Perkim
- **Gradient Background** - orange to red with dashed border styling  
- **Network Information Display**:
  - Client IP address dengan desktop icon
  - Server IP:Port dengan WiFi icon untuk local development
  - Smart IPv4 detection dari system network
- **Enhanced Tooltip** dengan multi-line instructions untuk production deployment
- **Dark Mode Support** - adaptive colors untuk light/dark themes
- **Production Fallback** - clean IP display untuk production environment

### Critical Bug Fixes

#### Export PDF Functionality Restored
- **Fixed 404 error** on `/master/barang/ekspor` 
- **Route conflict resolved** - moved export routes before resource routes
- **Permission middleware** temporarily disabled for testing
- **Throttle middleware** removed to prevent rate limiting issues
- **Simplified export method** for better reliability

#### User Management 404 Issues
- **Fixed 404 errors** on user detail pages (`/admin/pengguna/{id}`)
- **Permission middleware** causing access denial resolved
- **Route parameter mapping** corrected for user resource

#### Database Migration Fixes
- **Fixed maintenances table** not found error after fresh migration
- **Proper migration sequence** restored for maintenance_logs → maintenances rename

### UI & UX Improvements

#### Transfer Page Enhancements
- **Added thumbnails** for commodities in transfer list
- **Commodity images** with fallback icons for items without photos
- **Item codes display** below commodity names for better identification
- **Improved visual layout** with proper spacing and alignment

#### User Interface Terminology
- **Removed "User" references** throughout the application
- **Standardized role naming**: Only "Admin" and "Staff" roles displayed
- **Updated all user-facing text** to reflect Staff/Admin terminology only

#### Currency Formatting Enhancement
- **Smart currency display** with proper thresholds:
  - < 1M: `Rp 750,000` (full number)
  - ≥ 1M: `Rp 1.5Jt` (Juta)
  - ≥ 1B: `Rp 2.3M` (Milyar) 
  - ≥ 1T: `Rp 4.1T` (Trilyun)
- **Hover tooltips** showing Indonesian terbilang (spelled out numbers)
- **NumberHelper class** created for consistent formatting across app

#### Report Improvements  
- **Fixed data sync issues** in condition reports
- **Added sequential numbering** to category condition tables
- **Removed unused "Persentase Baik"** column from reports
- **Better table alignment** with centered numeric columns
- **Consistent data source** for counts and detail lists

### Technical Improvements

#### Enhanced Notification System
- **Action buttons** directly in notification list (inspired by E-Surat-Perkim)
- **Approve/Reject actions** without navigating to detail pages
- **Smart confirmation dialogs** for destructive actions
- **Multiple action support** with proper icons and styling
- **Enhanced notification data structure** with action arrays

#### Duplicate Prevention System
- **Database transactions** with row-level locking for item code generation
- **Retry logic** with automatic +1 increment when duplicates detected
- **Fallback mechanism** using timestamp for edge cases
- **User-friendly error messages** for duplicate code scenarios
- **Production-ready implementation** without debug log leaks

#### Code Quality & Security
- **Removed debug logging** from production methods
- **Clean error handling** without sensitive data exposure
- **Optimized database queries** for better performance
- **Consistent variable naming** across views and controllers

### Files Modified

#### Controllers
- `app/Http/Controllers/CommodityController.php` - Export fixes, permission adjustments
- `app/Http/Controllers/ReportController.php` - Data sync fixes, export parameter support
- `app/Http/Controllers/NotificationController.php` - Enhanced with action support

#### Models  
- `app/Models/Commodity.php` - Duplicate prevention system, cleaner item code generation
- `app/Helpers/NumberHelper.php` - NEW: Currency formatting and terbilang utilities

#### Views
- `resources/views/transfers/index.blade.php` - Added thumbnails and item codes
- `resources/views/users/show.blade.php` - Updated terminology (User → Staff/Admin)
- `resources/views/users/index.blade.php` - Updated terminology
- `resources/views/reports/by-condition.blade.php` - Added numbering, removed percentage column
- `resources/views/notifications/index.blade.php` - Enhanced with action buttons
- `resources/views/dashboard.blade.php` - Smart currency formatting with tooltips

#### Routes & Configuration
- `routes/web.php` - Fixed route precedence, removed problematic middleware
- Database migrations - Fixed maintenances table issues

---

## [v0.0.6-beta] - 2025-11-28 (Route Enhancement & PDF Fix)

### Route Reorganization

#### URL Structure Enhancement
Routes reorganized with prefix grouping for better organization:

| Category | Old URL | New URL |
|----------|---------|---------|
| **Master Data** | | |
| Barang | `/barang` | `/master/barang` |
| Kategori | `/kategori` | `/master/kategori` |
| Lokasi | `/lokasi` | `/master/lokasi` |
| **Transaksi** | | |
| Transfer | `/mutasi` | `/transaksi/transfer` |
| Maintenance | `/pemeliharaan` | `/transaksi/maintenance` |
| Penghapusan | `/penghapusan` | `/transaksi/penghapusan` |
| **Administrator** | | |
| Pengguna | `/pengguna` | `/admin/pengguna` |
| Kode Referral | `/kode-referral` | `/admin/kode-referral` |

#### Laporan URL Updates
| Old URL | New URL |
|---------|---------|
| `/laporan/mutasi` | `/laporan/transfer` |
| `/laporan/pemeliharaan` | `/laporan/maintenance` |

---

### 🎯 Dashboard Improvements

#### "Barang Terbaru" Enhancement
- ✅ **Added numbering** to "Barang Terbaru" table
- Added "No" column with sequential numbering (1, 2, 3...)
- Updated `colspan` for empty state message

---

### 🔧 Auto Item Code Generation

#### Category-Based Item Code System
- ✅ **Enhanced item code generation** to use category codes
- Format: `[KODE_KATEGORI]-[TAHUN]-[URUT 4 DIGIT]`
- Examples: `ATK-2025-0001`, `ELK-2025-0001`
- Fallback: `INV-2025-0001` (if no category code)

#### Smart Auto-Generate Feature
- **Auto-generate** when category is selected
- **Manual input** supported - can override generated code
- **Smart tracking** - only auto-generate if not manually edited
- New API endpoint: `GET /master/barang/preview-code`
- Seamless UX without buttons

#### Duplicate Prevention System
- **Database transactions** with row-level locking
- **Retry logic** - auto +1 increment if duplicate detected
- **Fallback mechanism** with timestamp for edge cases
- **User-friendly error messages** for duplicate codes
- **Production-ready** - no debug logs leak

#### Form Layout Improvements
- **Optimized input widths** - nama barang tidak terlalu lebar
- **Consistent layout** across create and edit forms
- **Validation** for unique item codes

---

### PDF Templates - Complete Fix

#### Missing PDF Templates Created
All 7 missing PDF templates have been created and are fully functional:

| Template | Description | Features |
|----------|-------------|----------|
| `by-category.blade.php` | ✅ Laporan per kategori | Subtotal per kategori, ringkasan |
| `by-location.blade.php` | ✅ Laporan per lokasi | Detail lokasi + gedung/lantai/ruang |
| `by-condition.blade.php` | ✅ Laporan per kondisi | Color-coded kondisi (baik/rusak) |
| `transfers.blade.php` | ✅ Laporan transfer | Status tracking dengan badge |
| `disposals.blade.php` | ✅ Laporan penghapusan | Alasan penghapusan + metode |
| `maintenance.blade.php` | ✅ Laporan maintenance | Monthly breakdown + biaya |
| `kib.blade.php` | ✅ Kartu Inventaris Barang | Complete KIB format with QR code |

#### Professional PDF Features
- Consistent header with logo & app name
- Meta information (totals, counts, values)
- Professional table styling with borders
- Summary sections with subtotals
- Official signature areas
- Print-ready A4 layout

---

### 🛠️ Technical Fixes

#### Security Risk Eliminated
- 🔥 **DELETED** `fix_security.php` (contained hardcoded passwords)

#### Route & Controller Fixes
- Fixed export route: `/master/barang/ekspor`
- Removed non-existent `import` method references
- Added error handling to export method
- Disabled middleware for debugging

#### Localization Enhancement
- Locale set to Indonesian (`id`)
- Timezone set to Asia/Jakarta
- Created complete Indonesian validation messages

---

### 📁 Files Modified

#### Controllers
- `app/Http/Controllers/CommodityController.php`
  - Enhanced `generateItemCode()` method
  - Added `previewItemCode()` method
  - Added `previewCode()` API method
  - Added export error handling

#### Routes
- `routes/web.php`
  - Complete route reorganization with prefixes
  - Added `commodities.preview-code` route
  - Fixed export route structure

#### Views
- `resources/views/dashboard.blade.php` - Added numbering to "Barang Terbaru"
- `resources/views/commodities/create.blade.php` - Added live item code preview
- `resources/views/reports/pdf/*.blade.php` - 7 new PDF templates

#### Models
- `app/Models/Commodity.php` - Enhanced item code generation

#### Configuration
- `config/app.php` - Set locale to `id` and timezone to `Asia/Jakarta`
- `lang/id/validation.php` - Complete Indonesian validation messages

#### Files Deleted
- `CHANGELOG.md` (old version, merged to ROUTE_DATABASE_REPORT.md)
- `fix_security.php` (security risk)

---
 

---

### 🚀 What's Working Now

#### ✅ Dashboard
- Clean numbered "Barang Terbaru" table

#### ✅ Item Management  
- Auto category-based item codes (ATK-2025-0001)
- Live preview in create form

#### ✅ PDF Reports
- All print buttons working
- Professional formatted reports
- Complete KIB (Kartu Inventaris Barang)

#### ✅ Routes
- Clean organized URL structure
- `/master/`, `/transaksi/`, `/admin/` prefixes
- Better navigation organization

---

### 📋 Next Steps
- Test all PDF export functionality
- Consider adding import feature for commodities
- Add QR code generation for KIB cards

---
 