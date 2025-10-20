# Kovil App - Modern Temple Management System

A comprehensive temple management system built with PHP and MySQL, featuring member management, matrimony services, donation tracking, and subscription management.

## 🚀 Quick Start

### Option 1: Automated Installation (Recommended)

1. **Check System Requirements**
   ```bash
   # Via web browser
   http://yourdomain.com/kovilapp/check_requirements.php
   
   # Or via command line
   php check_requirements.php
   ```

2. **Run Installation**
   ```bash
   # Web-based installation
   http://yourdomain.com/kovilapp/install.php?install
   
   # Or command line installation
   php install.php
   ```

3. **Deploy to Production** (Optional)
   ```bash
   # Make deployment script executable
   chmod +x deploy.sh
   
   # Run deployment
   ./deploy.sh
   ```

### Option 2: Manual Installation

See [INSTALL.md](INSTALL.md) for detailed manual installation instructions.

## 📋 System Requirements

- **PHP**: 7.4 or higher
- **MySQL**: 5.7+ or MariaDB 10.2+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP Extensions**: mysqli, gd, mbstring, json, fileinfo

## 🎯 Features

### Core Modules
- **👥 Member Management**: Complete family and member registration system
- **💑 Matrimony Services**: Horoscope matching and profile management  
- **💰 Donation Tracking**: Event-based donation collection and receipt management
- **📅 Subscription Management**: Membership fees and annual subscriptions
- **👤 User Management**: Role-based access control
- **🏷️ Label Management**: Configurable categories and classifications

### Key Features
- **📱 Responsive Design**: Works on desktop, tablet, and mobile devices
- **🌐 Multi-language Support**: Tamil and English interface
- **📸 Photo Management**: Member photo upload and management
- **📊 Report Generation**: Various reports and member lists
- **📤 Data Export**: Export member data in various formats
- **🔍 Advanced Search**: Powerful search and filtering capabilities
- **🎨 Modern UI**: Bootstrap-based responsive interface
- **🔒 Security**: Role-based access control and data protection

## 📁 Project Structure

```
kovilapp/
├── 🛠️ Installation & Deployment
│   ├── install.php              # Automated installation script
│   ├── check_requirements.php   # System requirements checker
│   ├── deploy.sh               # Production deployment script
│   ├── config.template.php     # Configuration template
│   └── INSTALL.md              # Detailed installation guide
│
├── 🏛️ Modern Application
│   ├── modern/                 # Main application directory
│   │   ├── member/            # Member management
│   │   ├── matrimony/         # Matrimony services
│   │   ├── subscription/      # Subscription management
│   │   ├── donation/          # Donation tracking
│   │   ├── includes/          # Common includes
│   │   ├── assets/            # Static assets
│   │   ├── images/            # Image uploads
│   │   └── attachments/       # File uploads
│   │
├── 📚 Legacy Version (Optional)
│   └── current/               # Legacy version for migration
│
└── 📖 Documentation
    ├── README.md              # This file
    ├── INSTALL.md             # Installation guide
    └── *.sql                  # Database schemas
```

## 🔧 Installation Scripts

### 1. Requirements Checker (`check_requirements.php`)
Validates your system before installation:
- PHP version and extensions
- Directory permissions
- Database connectivity
- Web server configuration

### 2. Installation Script (`install.php`)
Automated installation with:
- Database setup and table creation
- Configuration file generation
- Default data import
- Security setup
- Web and CLI interfaces

### 3. Deployment Script (`deploy.sh`)
Production deployment with:
- System requirements validation
- Backup creation
- Permission setting
- Web server configuration
- Security hardening

## 🚀 Getting Started

1. **Download/Clone** the repository
2. **Check Requirements**: Run `check_requirements.php`
3. **Install**: Run `install.php` or use web interface
4. **Configure**: Update settings in admin panel
5. **Deploy**: Use `deploy.sh` for production

## 🔐 Default Credentials

After installation, login with:
- **Username**: `admin`
- **Password**: `admin123`

⚠️ **Important**: Change the default password immediately after first login!

## 📖 Documentation

- **[Installation Guide](INSTALL.md)**: Detailed installation instructions
- **Configuration**: See `config.template.php` for all options
- **API Documentation**: Available in the admin panel
- **User Manual**: Built-in help system

## 🛠️ Development

### Local Development Setup
```bash
# Clone repository
git clone <repository-url>
cd kovilapp

# Check requirements
php check_requirements.php

# Install
php install.php

# Start development server (PHP 7.4+)
php -S localhost:8000 -t modern/
```

### Contributing
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 🔒 Security

- Regular security updates
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- CSRF protection
- Secure file uploads
- Role-based access control

## 📊 Database

The application uses MySQL/MariaDB with the following main tables:
- `users` - User accounts
- `family` - Member records
- `subscription_events` - Subscription events
- `member_subscriptions` - Member payments
- `receipt_books` - Receipt management
- And more...

## 🌐 Browser Support

- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## 📱 Mobile Features

- Responsive design for all screen sizes
- Touch-friendly interface
- Mobile-optimized forms
- Offline capability (planned)

## 🔄 Migration

Migrating from legacy version:
1. Backup existing data
2. Install modern version
3. Run migration scripts
4. Verify data integrity
5. Update user training

## 🆘 Support

- **Documentation**: Check INSTALL.md and built-in help
- **Issues**: Report bugs via GitHub issues
- **Community**: Join our community forum
- **Professional Support**: Contact for enterprise support

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Bootstrap team for the UI framework
- PHP community for excellent documentation
- All contributors and testers
- Temple communities using this system

## 📈 Roadmap

- [ ] Mobile app (React Native)
- [ ] Advanced reporting dashboard
- [ ] SMS/Email notifications
- [ ] Online payment integration
- [ ] Multi-temple support
- [ ] API for third-party integrations
- [ ] Advanced analytics
- [ ] Cloud deployment options

---

**Made with ❤️ for temple communities worldwide**

For detailed installation instructions, see [INSTALL.md](INSTALL.md)