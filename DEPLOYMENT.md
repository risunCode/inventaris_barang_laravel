# 🚀 Quick Deployment Guide
## Clone & Setup SIBARANG for Your Organization

**Version: 0.0.7-beta-007** | ⚡ Ready for Production

---

## 🎯 Quick Start (5 Minutes)

### 1. Clone Repository
```bash
git clone https://github.com/risunCode/inventaris_barang_laravel.git your-inventory-system
cd your-inventory-system
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 5. Build Assets & Start
```bash
npm run build
php artisan serve
```

**🎉 Access:** http://127.0.0.1:8000  
**🔑 Default Login:** admin@inventory.com / password

---

## 🏢 Organization Customization

### Quick Branding Setup
Edit `config/sibarang.php`:

```php
return [
    'name' => 'YOUR_SYSTEM_NAME',          // Change system name
    'version' => '1.0.0',                 // Your version
    'description' => 'Your Description',
    
    'organization' => [
        'name' => 'Your Organization Name',
        'short_name' => 'SHORT',
        'address' => 'Your Address',
        'phone' => 'Your Phone',
        'email' => 'your@email.com',
    ],
];
```

### Logo Customization
Replace these files in `public/images/`:
- `logo-kab.png` → Your main logo
- `logo-pbj-kalbar.png` → Your secondary logo

### Color Theme (Optional)
Edit `resources/css/app.css` variables:
```css
:root {
    --primary-color: #your-color;
    --secondary-color: #your-accent;
}
```

---

## 🌐 Production Deployment

### Environment Configuration
```bash
# .env for production
APP_NAME="Your Inventory System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

### Production Commands
```bash
# Build optimized assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Create storage link
php artisan storage:link
```

### Web Server Setup

#### Apache (.htaccess)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

---

## 📋 Pre-Deployment Checklist

### ✅ Security
- [ ] Change default admin password
- [ ] Set `APP_DEBUG=false` in production
- [ ] Configure proper database credentials
- [ ] Set up HTTPS/SSL certificate
- [ ] Configure firewall rules

### ✅ Performance
- [ ] Enable Laravel caching
- [ ] Optimize database with indexes
- [ ] Set up CDN for static assets
- [ ] Configure backup schedule

### ✅ Functionality
- [ ] Test all CRUD operations
- [ ] Verify PDF report generation
- [ ] Test email notifications
- [ ] Check mobile responsiveness

---

## 🔧 Advanced Configuration

### Custom Email Templates
Edit files in `resources/views/emails/`:
- Transfer notifications
- Maintenance reminders
- Disposal approvals

### Custom Report Templates
Edit files in `resources/views/reports/pdf/`:
- Add your organization header
- Modify footer information
- Customize report layouts

### Database Customization
```bash
# Add custom seeders for your data
php artisan make:seeder YourCustomSeeder
# Edit DatabaseSeeder.php to include your seeder
php artisan db:seed --class=YourCustomSeeder
```

---

## 📱 Mobile App Setup (Optional)

### PWA Configuration
```bash
# Enable PWA features
npm install @vitejs/plugin-pwa
# Configure vite.config.js for PWA
npm run build
```

### Progressive Web App Features
- Offline capability
- Install to home screen
- Push notifications
- Mobile-optimized interface

---

## 🆘 Troubleshooting

### Common Issues

**404 Errors on Routes**
```bash
php artisan route:clear
php artisan config:clear
```

**PDF Generation Issues**
```bash
# Install required PHP extensions
sudo apt-get install php8.2-dompdf php8.2-gd
```

**File Upload Issues**
```bash
# Check permissions
chmod -R 755 storage/app/public
php artisan storage:link
```

**Database Connection Errors**
- Verify database credentials in `.env`
- Check database server is running
- Ensure database exists and user has permissions

### Performance Optimization

**Slow Dashboard**
```bash
# Cache database queries
php artisan optimize:clear
php artisan config:cache
```

**Large File Uploads**
Edit `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

---

## 📞 Support & Documentation

### 📚 Complete Documentation
- [README.md](README.md) - Full feature documentation
- [CHANGELOG.md](CHANGELOG.md) - Version history & updates
- [proposal-laravel/](proposal-laravel/) - Technical specifications

### 🐛 Bug Reports
- Create issue on GitHub repository
- Include Laravel version, PHP version, and error details
- Provide steps to reproduce the issue

### 💡 Feature Requests
- Submit feature requests via GitHub issues
- Include use case and expected behavior

---

## 🎯 Success Metrics

### Implementation Goals
- ✅ Fully functional inventory system
- ✅ Customized for your organization
- ✅ Mobile-responsive design
- ✅ Professional PDF reports
- ✅ Role-based access control

### Next Steps
1. Train your team on the system
2. Import existing inventory data
3. Set up regular backup schedule
4. Monitor system performance
5. Plan future enhancements

**🎉 Your inventory system is now ready for production use!**

---

*Need help? Check our [complete documentation](README.md) or create an issue on GitHub.*
