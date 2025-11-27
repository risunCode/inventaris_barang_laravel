# 🏢 Sistem Inventaris Barang - Kabupaten Kubu Raya

**Version: 0.0.2-beta** 🎉

Sistem manajemen inventaris barang yang komprehensif untuk pemerintah daerah, dibangun dengan Laravel 12 dan teknologi modern.

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

### 👥 User Management  
- **Role-based Access Control (RBAC)** dengan Spatie Laravel Permission
- **Modal-based operations** untuk create/edit users
- **Referral Code System** untuk registrasi user baru
- User details dengan wide layout dan stats

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

## 🛠️ Technical Stack

- **Laravel**: 12.40.1
- **PHP**: 8.3.23
- **Database**: MySQL/SQLite  
- **Frontend**: Tailwind CSS + Alpine.js + Chart.js
- **Permissions**: Spatie Laravel Permission
- **PDF**: DomPDF
- **Notifications**: Laravel native notifications

## 📱 Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Version History

### v0.0.2-beta (Current) - 27 Nov 2025
**Bug Fixes & Improvements**
- Fix modal flash issue on page load (display:none approach)
- Fix security questions validation (integer column issue)
- Fix modal name mismatch di transfers, maintenance, disposals
- Add `.input` CSS class dengan proper border styling

**UI/UX Enhancements**
- Redesign modals dengan grid layout (2 kolom)
- Wider modals (max-w-2xl) untuk users, locations, categories
- Toggle switch untuk status aktif (modern styling)
- Input boxes dengan visible border dan focus ring
- Better placeholder text untuk semua input

**SweetAlert2 Integration (Per-Page)**
- Add SweetAlert2 via CDN untuk halaman spesifik
- Toast notifications untuk success/error
- Confirm dialogs untuk delete actions
- Halaman: users, categories, locations, referral-codes, profile

**Security Questions**
- Birth date verification modal
- Security question modal dengan custom option
- Support untuk pertanyaan custom (value=0)

### v0.0.1-beta
- Complete inventory management system
- All major features implemented and tested
- Modal-based CRUD operations
- Referral code system
- Enhanced UI/UX with charts and galleries
- Comprehensive reporting system

## Roadmap

- [ ] API endpoints for mobile app
- [ ] Advanced reporting with filters
- [ ] Bulk operations for commodities
- [ ] Email notifications
- [ ] Advanced user roles and permissions

---

**Developed for Kabupaten Kubu Raya** 🏛️