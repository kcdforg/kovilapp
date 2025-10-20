#!/bin/bash

# Kovil App - Modern Version Deployment Script
# This script helps deploy the Kovil App to a production server

set -e  # Exit on any error

# Configuration
APP_NAME="Kovil App - Modern Version"
VERSION="1.0.0"
BACKUP_DIR="backups"
LOG_FILE="deploy.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${BLUE}[$(date '+%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$LOG_FILE"
    exit 1
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "$LOG_FILE"
}

success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a "$LOG_FILE"
}

# Print header
print_header() {
    echo -e "${BLUE}"
    echo "=========================================="
    echo "  $APP_NAME"
    echo "  Deployment Script v$VERSION"
    echo "=========================================="
    echo -e "${NC}"
}

# Check if running as root
check_root() {
    if [[ $EUID -eq 0 ]]; then
        warning "Running as root. Consider using a non-root user for security."
    fi
}

# Check system requirements
check_requirements() {
    log "Checking system requirements..."
    
    # Check PHP
    if ! command -v php &> /dev/null; then
        error "PHP is not installed. Please install PHP 7.4 or higher."
    fi
    
    PHP_VERSION=$(php -v | head -n1 | cut -d' ' -f2 | cut -d'.' -f1,2)
    if [[ $(echo "$PHP_VERSION < 7.4" | bc -l) -eq 1 ]]; then
        error "PHP version $PHP_VERSION is not supported. Please install PHP 7.4 or higher."
    fi
    success "PHP version $PHP_VERSION is supported"
    
    # Check MySQL/MariaDB
    if ! command -v mysql &> /dev/null && ! command -v mariadb &> /dev/null; then
        error "MySQL or MariaDB is not installed."
    fi
    success "Database server is available"
    
    # Check web server
    if command -v apache2 &> /dev/null; then
        success "Apache web server detected"
        WEB_SERVER="apache"
    elif command -v nginx &> /dev/null; then
        success "Nginx web server detected"
        WEB_SERVER="nginx"
    else
        warning "No recognized web server found. Manual configuration may be required."
        WEB_SERVER="unknown"
    fi
    
    # Check PHP extensions
    REQUIRED_EXTENSIONS=("mysqli" "gd" "mbstring" "json")
    for ext in "${REQUIRED_EXTENSIONS[@]}"; do
        if ! php -m | grep -q "^$ext$"; then
            error "Required PHP extension '$ext' is not installed"
        fi
        success "PHP extension '$ext' is available"
    done
}

# Create backup
create_backup() {
    if [[ -d "modern" ]]; then
        log "Creating backup of existing installation..."
        
        # Create backup directory
        mkdir -p "$BACKUP_DIR"
        
        # Create backup filename with timestamp
        BACKUP_NAME="kovilapp_backup_$(date +%Y%m%d_%H%M%S)"
        
        # Backup files
        tar -czf "$BACKUP_DIR/$BACKUP_NAME.tar.gz" modern/ current/ 2>/dev/null || true
        
        # Backup database (if config exists)
        if [[ -f "modern/config.php" ]]; then
            # Extract database credentials
            DB_NAME=$(grep '$db_name' modern/config.php | cut -d"'" -f2)
            DB_USER=$(grep '$db_user' modern/config.php | cut -d"'" -f2)
            DB_PASS=$(grep '$db_pass' modern/config.php | cut -d"'" -f2)
            
            if [[ -n "$DB_NAME" && -n "$DB_USER" ]]; then
                log "Backing up database: $DB_NAME"
                if [[ -n "$DB_PASS" ]]; then
                    mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_DIR/${BACKUP_NAME}_database.sql" 2>/dev/null || warning "Database backup failed"
                else
                    mysqldump -u "$DB_USER" "$DB_NAME" > "$BACKUP_DIR/${BACKUP_NAME}_database.sql" 2>/dev/null || warning "Database backup failed"
                fi
            fi
        fi
        
        success "Backup created: $BACKUP_DIR/$BACKUP_NAME.tar.gz"
    fi
}

# Set file permissions
set_permissions() {
    log "Setting file permissions..."
    
    # Application files
    find modern/ -type f -exec chmod 644 {} \; 2>/dev/null || true
    find modern/ -type d -exec chmod 755 {} \; 2>/dev/null || true
    
    # Upload directories - need write permissions
    chmod 777 modern/images/ 2>/dev/null || true
    chmod 777 modern/images/member/ 2>/dev/null || true
    chmod 777 modern/attachments/ 2>/dev/null || true
    
    # Legacy directories (if they exist)
    chmod 777 current/images/ 2>/dev/null || true
    chmod 777 current/images/member/ 2>/dev/null || true
    chmod 777 current/attachments/ 2>/dev/null || true
    
    success "File permissions set"
}

# Configure web server
configure_webserver() {
    case $WEB_SERVER in
        "apache")
            configure_apache
            ;;
        "nginx")
            configure_nginx
            ;;
        *)
            warning "Unknown web server. Manual configuration required."
            ;;
    esac
}

configure_apache() {
    log "Configuring Apache..."
    
    # Create .htaccess if it doesn't exist
    if [[ ! -f ".htaccess" ]]; then
        cat > .htaccess << 'EOF'
# Kovil App Security Settings
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

<Files "install.php">
    Order Allow,Deny
    Deny from all
</Files>

<Files "deploy.sh">
    Order Allow,Deny
    Deny from all
</Files>

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Set cache headers
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
</IfModule>
EOF
        success "Created .htaccess file"
    fi
    
    # Check if mod_rewrite is enabled
    if command -v apache2ctl &> /dev/null; then
        if ! apache2ctl -M | grep -q rewrite_module; then
            warning "Apache mod_rewrite is not enabled. Please enable it: sudo a2enmod rewrite"
        fi
    fi
}

configure_nginx() {
    log "Configuring Nginx..."
    
    # Create nginx configuration snippet
    cat > nginx_kovilapp.conf << 'EOF'
# Kovil App Nginx Configuration
# Include this in your server block

location ~ \.(sql|config\.php|install\.php|deploy\.sh)$ {
    deny all;
}

location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
EOF
    
    success "Created nginx_kovilapp.conf - include this in your server block"
}

# Run installation
run_installation() {
    log "Running installation..."
    
    if [[ -f "install.php" ]]; then
        # Check if we can run PHP CLI
        if command -v php &> /dev/null; then
            php install.php
        else
            warning "Cannot run PHP CLI installation. Please run via web browser: http://yourdomain.com/kovilapp/install.php?install"
        fi
    else
        warning "install.php not found. Manual installation required."
    fi
}

# Security cleanup
security_cleanup() {
    log "Performing security cleanup..."
    
    # Remove or rename sensitive files
    if [[ -f "install.php" ]]; then
        mv install.php install.php.bak
        success "Renamed install.php to install.php.bak"
    fi
    
    if [[ -f "deploy.sh" ]]; then
        chmod 600 deploy.sh
        success "Secured deploy.sh permissions"
    fi
    
    # Remove any .git directory if present
    if [[ -d ".git" ]]; then
        rm -rf .git
        success "Removed .git directory"
    fi
}

# Display post-deployment information
show_completion_info() {
    echo -e "${GREEN}"
    echo "=========================================="
    echo "  DEPLOYMENT COMPLETED SUCCESSFULLY!"
    echo "=========================================="
    echo -e "${NC}"
    
    echo "Next steps:"
    echo "1. Navigate to your application URL"
    echo "2. Login with default credentials:"
    echo "   Username: admin"
    echo "   Password: admin123"
    echo "3. Change the default password immediately"
    echo "4. Configure your application settings"
    echo "5. Set up regular backups"
    echo ""
    echo "Important files:"
    echo "- Configuration: modern/config.php"
    echo "- Logs: $LOG_FILE"
    echo "- Backups: $BACKUP_DIR/"
    echo ""
    echo "For support, check INSTALL.md or README.md"
    echo ""
}

# Main deployment function
main() {
    print_header
    
    log "Starting deployment of $APP_NAME"
    
    # Run deployment steps
    check_root
    check_requirements
    create_backup
    set_permissions
    configure_webserver
    
    # Ask user if they want to run installation
    read -p "Do you want to run the installation now? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        run_installation
    else
        log "Skipping installation. Run install.php manually."
    fi
    
    security_cleanup
    show_completion_info
    
    success "Deployment completed successfully!"
}

# Handle script arguments
case "${1:-}" in
    --help|-h)
        echo "Usage: $0 [options]"
        echo "Options:"
        echo "  --help, -h     Show this help message"
        echo "  --check        Check requirements only"
        echo "  --backup       Create backup only"
        echo "  --permissions  Set permissions only"
        exit 0
        ;;
    --check)
        print_header
        check_requirements
        exit 0
        ;;
    --backup)
        print_header
        create_backup
        exit 0
        ;;
    --permissions)
        print_header
        set_permissions
        exit 0
        ;;
    *)
        main
        ;;
esac
