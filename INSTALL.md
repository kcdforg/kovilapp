# Kovil App - Modern Version Installation Guide

## Overview

Kovil App Modern Version is a comprehensive temple management system built with PHP and MySQL. It provides features for member management, matrimony services, donation tracking, subscription management, and more.

## System Requirements

### Minimum Requirements
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher (or MariaDB 10.2+)
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Memory**: 256MB RAM minimum
- **Storage**: 1GB free space

### Required PHP Extensions
- `mysqli` - Database connectivity
- `gd` - Image processing
- `mbstring` - Multi-byte string handling
- `json` - JSON data processing
- `fileinfo` - File type detection
- `zip` - Archive handling (optional)

### Recommended PHP Settings
```ini
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

## Installation Methods

### Method 1: Automated Installation (Recommended)

#### Option A: Web-based Installation
1. Upload all files to your web server
2. Navigate to: `http://yourdomain.com/kovilapp/install.php?install`
3. Fill in the database configuration form
4. Click "Install Kovil App"
5. Follow the on-screen instructions

#### Option B: Command Line Installation
1. Upload all files to your web server
2. SSH into your server
3. Navigate to the application directory:
   ```bash
   cd /path/to/kovilapp
   ```
4. Run the installation script:
   ```bash
   php install.php
   ```
5. Follow the prompts to configure your database

### Method 2: Manual Installation

#### Step 1: Database Setup
1. Create a new MySQL database:
   ```sql
   CREATE DATABASE kovil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Create a database user:
   ```sql
   CREATE USER 'kovil_user'@'localhost' IDENTIFIED BY 'your_secure_password';
   GRANT ALL PRIVILEGES ON kovil.* TO 'kovil_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. Import the database structure:
   ```bash
   mysql -u kovil_user -p kovil < modern/subscription_database.sql
   ```

#### Step 2: File Configuration
1. Copy the application files to your web server directory
2. Set proper file permissions:
   ```bash
   chmod 755 modern/
   chmod 777 modern/images/
   chmod 777 modern/attachments/
   chmod 777 current/images/
   chmod 777 current/attachments/
   ```

3. Configure the database connection in `modern/config.php`:
   ```php
   $db_host = 'localhost';
   $db_name = 'kovil';
   $db_user = 'kovil_user';
   $db_pass = 'your_secure_password';
   ```

#### Step 3: Web Server Configuration

##### Apache Configuration
Create or update `.htaccess` file in the root directory:
```apache
RewriteEngine On

# Prevent access to sensitive files
<Files "config.php">
    Order Allow,Deny
    Deny from all
</Files>

<Files "*.sql">
    Order Allow,Deny
    Deny from all
</Files>

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain text/html text/css application/javascript
</IfModule>
```

##### Nginx Configuration
Add to your server block:
```nginx
location ~ \.(sql|config\.php)$ {
    deny all;
}

location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

## Post-Installation Setup

### 1. Initial Login
- Navigate to: `http://yourdomain.com/kovilapp/modern/`
- Default credentials:
  - **Username**: `admin`
  - **Password**: `admin123`

### 2. Security Configuration
1. **Change default password immediately**
2. **Delete or rename `install.php`** for security
3. **Update database credentials** if using default values
4. **Configure file upload limits** in PHP settings

### 3. Application Configuration
1. **System Settings**: Configure temple name, address, and contact details
2. **User Management**: Create additional user accounts as needed
3. **Labels Setup**: Configure categories for Kattalai, Education, Occupation, etc.
4. **Backup Setup**: Configure regular database backups

## Directory Structure

```
kovilapp/
├── current/                    # Legacy version (optional)
├── modern/                     # Modern version (main application)
│   ├── assets/                # Static assets (CSS, JS, images)
│   ├── attachments/           # File uploads
│   ├── images/                # Image uploads
│   │   └── member/           # Member photos
│   ├── includes/             # Common includes
│   │   ├── header.php
│   │   └── footer.php
│   ├── member/               # Member management
│   ├── matrimony/            # Matrimony services
│   ├── subscription/         # Subscription management
│   ├── donation/             # Donation tracking
│   ├── config.php            # Database configuration
│   ├── init.php              # Application initialization
│   ├── function.php          # Common functions
│   └── index.php             # Application entry point
├── install.php               # Installation script
├── INSTALL.md               # This file
└── README.md                # Project documentation
```

## Features Overview

### Core Modules
- **Member Management**: Complete family and member registration system
- **Matrimony Services**: Horoscope matching and profile management
- **Donation Tracking**: Event-based donation collection and receipt management
- **Subscription Management**: Membership fees and annual subscriptions
- **User Management**: Role-based access control
- **Label Management**: Configurable categories and classifications

### Key Features
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Multi-language Support**: Tamil and English interface
- **Photo Management**: Member photo upload and management
- **Report Generation**: Various reports and member lists
- **Data Export**: Export member data in various formats
- **Search & Filter**: Advanced search and filtering capabilities

## Troubleshooting

### Common Issues

#### Database Connection Error
```
Error: Could not connect to database
```
**Solution**: Check database credentials in `modern/config.php` and ensure MySQL service is running.

#### File Upload Issues
```
Error: Failed to upload file
```
**Solution**: Check file permissions on upload directories:
```bash
chmod 777 modern/images/member/
chmod 777 modern/attachments/
```

#### PHP Memory Limit Error
```
Fatal error: Allowed memory size exhausted
```
**Solution**: Increase PHP memory limit in `php.ini`:
```ini
memory_limit = 512M
```

#### Session Issues
```
Warning: session_start(): Cannot send session cookie
```
**Solution**: Ensure session directory is writable and check PHP session configuration.

### Log Files
- **PHP Error Log**: Check your server's PHP error log
- **Apache/Nginx Log**: Check web server access and error logs
- **Application Log**: Check `modern/logs/` directory if logging is enabled

## Backup and Maintenance

### Database Backup
Create regular database backups:
```bash
mysqldump -u kovil_user -p kovil > backup_$(date +%Y%m%d_%H%M%S).sql
```

### File Backup
Backup uploaded files:
```bash
tar -czf files_backup_$(date +%Y%m%d_%H%M%S).tar.gz modern/images/ modern/attachments/
```

### Update Process
1. Backup database and files
2. Download new version
3. Replace application files (keep config.php)
4. Run any database migrations
5. Test functionality

## Security Considerations

### File Permissions
```bash
# Application files
find modern/ -type f -exec chmod 644 {} \;
find modern/ -type d -exec chmod 755 {} \;

# Upload directories
chmod 777 modern/images/member/
chmod 777 modern/attachments/
```

### Database Security
- Use strong passwords
- Limit database user privileges
- Regular security updates
- Enable SSL for database connections

### Web Server Security
- Keep software updated
- Use HTTPS in production
- Configure proper firewall rules
- Regular security audits

## Support and Documentation

### Getting Help
- **Documentation**: Check the `docs/` directory for detailed guides
- **Issues**: Report bugs and issues on the project repository
- **Community**: Join the community forum for discussions

### Development
- **Contributing**: See `CONTRIBUTING.md` for development guidelines
- **API Documentation**: Available in `docs/api/`
- **Database Schema**: See `docs/database/`

## License

This project is licensed under the MIT License. See `LICENSE` file for details.

## Changelog

### Version 1.0.0
- Initial release of modern version
- Complete rewrite with modern PHP practices
- Responsive Bootstrap-based UI
- Enhanced security features
- Improved performance and scalability

---

**Note**: Always test the installation in a development environment before deploying to production.
