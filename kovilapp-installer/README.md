# Kovil App - Modern Version Installer Package

## 📦 Package Contents

This is a complete, self-contained installation package for the Kovil App Modern Version. Everything you need to install and run the application is included in this package.

### What's Included:
- ✅ **Complete Modern Application** (`modern/` folder)
- ✅ **Database Schema** (`kovil.sql`)
- ✅ **Installation Script** (`install.php`)
- ✅ **Requirements Checker** (`check_requirements.php`)
- ✅ **Documentation** (this README)

## 🚀 Quick Installation

### Step 1: Upload Package
1. Download/extract this entire `kovilapp-installer` folder
2. Upload the entire folder to your web server
3. Make sure the folder is accessible via web browser

### Step 2: Check Requirements
Visit: `http://yourdomain.com/kovilapp-installer/check_requirements.php`

This will verify that your server meets all requirements.

### Step 3: Install
Visit: `http://yourdomain.com/kovilapp-installer/install.php?install`

Follow the web-based installation wizard.

### Step 4: Access Your Application
After successful installation, the application files will be moved to your web root.
Access your app at: `http://yourdomain.com/`

**Default Login:**
- Username: `admin`
- Password: `admin123`

⚠️ **Change the password immediately after first login!**

## 📋 System Requirements

- **PHP**: 7.4 or higher
- **MySQL**: 5.7+ or MariaDB 10.2+
- **Web Server**: Apache or Nginx
- **PHP Extensions**: mysqli, gd, mbstring, json, fileinfo
- **Disk Space**: ~50MB minimum
- **Memory**: 256MB PHP memory limit recommended

## 📁 Package Structure

```
kovilapp-installer/
├── 🛠️ Installation Files
│   ├── install.php              # Main installation script
│   ├── check_requirements.php   # System requirements checker
│   ├── kovil.sql               # Database schema
│   └── README.md               # This file
│
└── 🏛️ Application Files
    └── modern/                 # Complete Kovil App Modern Version
        ├── member/            # Member management
        ├── matrimony/         # Matrimony services
        ├── subscription/      # Subscription management
        ├── donation/          # Donation tracking
        ├── includes/          # Common includes
        ├── assets/            # Static assets
        ├── images/            # Image uploads directory
        ├── attachments/       # File uploads directory
        ├── config.php         # Configuration (created during install)
        ├── index.php          # Application entry point
        └── ... (all other app files)
```

## 🔧 Installation Options

### Option 1: Web Installation (Recommended)
1. **Check Requirements**: `http://yourdomain.com/kovilapp-installer/check_requirements.php`
2. **Install**: `http://yourdomain.com/kovilapp-installer/install.php?install`
3. **Follow the wizard** - enter database details and click install

### Option 2: Command Line Installation
```bash
# Navigate to the installer directory
cd /path/to/kovilapp-installer

# Check requirements
php check_requirements.php

# Run installation
php install.php
```

## 🗄️ Database Setup

The installer will:
1. **Create the database** if it doesn't exist (requires CREATE privileges)
2. **Import the schema** from `kovil.sql`
3. **Create default admin user** (admin/admin123)
4. **Set up sample data** and configurations

### Database Permissions Required:
- CREATE (to create database)
- SELECT, INSERT, UPDATE, DELETE (for normal operations)
- CREATE, ALTER, DROP (for table management)

## 🔒 Security Features

### Automatic Security Setup:
- ✅ **File Protection**: Sensitive files protected via .htaccess
- ✅ **Directory Permissions**: Proper file/folder permissions set
- ✅ **Configuration Security**: Database credentials protected
- ✅ **Default User**: Secure password hashing for admin user

### Post-Installation Security:
1. **Change default password** immediately
2. **Delete/rename** `install.php` and `kovil.sql` files
3. **Set up regular backups**
4. **Keep software updated**

## 🎯 Features Included

### Core Modules:
- **👥 Member Management**: Complete family registration system
- **💑 Matrimony Services**: Horoscope matching and profiles
- **💰 Donation Tracking**: Event-based donation management
- **📅 Subscription Management**: Membership fees and subscriptions
- **👤 User Management**: Role-based access control
- **🏷️ Label Management**: Configurable categories

### Technical Features:
- **📱 Responsive Design**: Works on all devices
- **🌐 Multi-language**: Tamil and English support
- **📸 Photo Management**: Member photo uploads
- **📊 Reports**: Various reports and exports
- **🔍 Advanced Search**: Powerful filtering
- **🎨 Modern UI**: Bootstrap-based interface

## 🛠️ Troubleshooting

### Common Issues:

#### "Database connection failed"
- Check database credentials
- Ensure MySQL/MariaDB is running
- Verify database user has proper privileges

#### "Directory not writable"
```bash
chmod 777 images/
chmod 777 attachments/
```
Note: After installation, directories are moved to web root.

#### "PHP extension missing"
Install required extensions:
```bash
# Ubuntu/Debian
sudo apt-get install php-mysqli php-gd php-mbstring

# CentOS/RHEL
sudo yum install php-mysqli php-gd php-mbstring
```

#### "Memory limit exceeded"
Increase PHP memory limit in `php.ini`:
```ini
memory_limit = 512M
```

### Installation Logs:
- Check your web server error logs
- PHP errors will be displayed during installation
- Database connection issues are reported in detail

## 📞 Support

### Getting Help:
1. **Check Requirements**: Run the requirements checker first
2. **Review Logs**: Check web server and PHP error logs
3. **Documentation**: Read through this README completely
4. **Common Issues**: See troubleshooting section above

### Technical Support:
- **System Requirements**: Ensure all requirements are met
- **File Permissions**: Verify proper directory permissions
- **Database Access**: Confirm database credentials and privileges
- **PHP Configuration**: Check PHP version and extensions

## 🔄 Upgrade Path

### From Legacy Version:
1. **Backup** existing data and files
2. **Install** this modern version in a separate directory
3. **Migrate** data using provided migration tools
4. **Test** thoroughly before switching over

### Future Updates:
1. **Backup** current installation
2. **Download** new installer package
3. **Replace** application files (keep config.php)
4. **Run** any database migrations
5. **Test** functionality

## 📝 Configuration

### After Installation:
1. **Login** with admin/admin123
2. **Change Password** immediately
3. **Configure Settings**:
   - Temple name and details
   - Contact information
   - System preferences
4. **Create Users** as needed
5. **Set up Categories** (Kattalai, etc.)
6. **Import/Add Members**

### File Locations:
- **Configuration**: `modern/config.php`
- **Uploads**: `modern/images/` and `modern/attachments/`
- **Logs**: Check web server logs
- **Backups**: Create regular database and file backups

## 📄 License

This project is licensed under the MIT License. See the application documentation for full license details.

## 🙏 Acknowledgments

- **Bootstrap** for the responsive UI framework
- **PHP Community** for excellent documentation
- **MySQL/MariaDB** for reliable database engine
- **All Contributors** who helped build this system

---

## 🚨 Important Notes

1. **Security**: Change default password immediately after installation
2. **Cleanup**: Delete `install.php` and `kovil.sql` after successful installation
3. **Backups**: Set up regular database and file backups
4. **Updates**: Keep PHP, MySQL, and web server updated
5. **Testing**: Test all functionality after installation

**Made with ❤️ for temple communities worldwide**

---

**Installation Support**: If you encounter issues, ensure all system requirements are met and check the troubleshooting section above.
