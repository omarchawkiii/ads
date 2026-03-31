#!/bin/bash

# =============================================================================
# AdSmart – Environment Setup Script
# Target: Ubuntu 22.04 / 24.04 (Debian-based)
# Stack : Apache2 · MySQL 8 · PHP 8.1 · phpMyAdmin · Composer
# =============================================================================

set -e  # Exit immediately on error

# ─── Colours ──────────────────────────────────────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Colour

info()    { echo -e "${BLUE}[INFO]${NC}  $1"; }
success() { echo -e "${GREEN}[OK]${NC}    $1"; }
warning() { echo -e "${YELLOW}[WARN]${NC}  $1"; }
error()   { echo -e "${RED}[ERROR]${NC} $1"; exit 1; }

# ─── Config (edit if needed) ──────────────────────────────────────────────────
APP_NAME="adsamrt"
APP_DIR="/var/www/${APP_NAME}"
APP_URL="http://localhost"
APACHE_CONF="/etc/apache2/sites-available/${APP_NAME}.conf"

DB_NAME="adsamrt"
DB_USER="adsamrt_user"
DB_PASS="AdSmart@2024!"          # Change before production use
DB_HOST="127.0.0.1"
DB_PORT="3306"

MYSQL_ROOT_PASS=""               # Leave empty if root has no password (fresh install)

PHP_VERSION="8.1"

# ─── Pre-flight checks ────────────────────────────────────────────────────────
echo ""
echo "============================================================"
echo "  AdSmart – Installation Script"
echo "============================================================"
echo ""

if [[ $EUID -ne 0 ]]; then
    error "This script must be run as root. Use: sudo bash install.sh"
fi

info "Detected OS: $(lsb_release -ds 2>/dev/null || echo 'Unknown')"
info "PHP target version: ${PHP_VERSION}"
echo ""

# ─── 1. Update system ─────────────────────────────────────────────────────────
info "Updating package list..."
apt-get update -qq
success "Package list updated."

# ─── 2. Install dependencies ──────────────────────────────────────────────────
info "Installing base utilities..."
apt-get install -y -qq \
    curl \
    wget \
    git \
    unzip \
    zip \
    software-properties-common \
    apt-transport-https \
    ca-certificates \
    gnupg2 \
    lsb-release \
    > /dev/null
success "Base utilities installed."

# ─── 3. Add PHP 8.1 repository (Ondřej Surý PPA) ─────────────────────────────
info "Adding PHP ${PHP_VERSION} repository..."
add-apt-repository -y ppa:ondrej/php > /dev/null 2>&1
apt-get update -qq
success "PHP repository added."

# ─── 4. Install Apache ────────────────────────────────────────────────────────
info "Installing Apache2..."
apt-get install -y -qq apache2 > /dev/null
systemctl enable apache2
systemctl start apache2
success "Apache2 installed and started."

# ─── 5. Install PHP 8.1 + required extensions ─────────────────────────────────
info "Installing PHP ${PHP_VERSION} and extensions..."
apt-get install -y -qq \
    php${PHP_VERSION} \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-pdo \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-fileinfo \
    php${PHP_VERSION}-tokenizer \
    php${PHP_VERSION}-ctype \
    php${PHP_VERSION}-json \
    php${PHP_VERSION}-openssl \
    php${PHP_VERSION}-dom \
    php${PHP_VERSION}-exif \
    libapache2-mod-php${PHP_VERSION} \
    > /dev/null
success "PHP ${PHP_VERSION} and extensions installed."

# Enable required Apache modules
a2enmod php${PHP_VERSION} rewrite headers > /dev/null 2>&1
success "Apache modules enabled (rewrite, headers, php${PHP_VERSION})."

# ─── 6. Install MySQL 8 ───────────────────────────────────────────────────────
info "Installing MySQL 8..."
apt-get install -y -qq mysql-server > /dev/null
systemctl enable mysql
systemctl start mysql
success "MySQL installed and started."

# ─── 7. Secure MySQL and create database / user ───────────────────────────────
info "Configuring MySQL (database + user)..."

MYSQL_CMD="mysql"
if [[ -n "$MYSQL_ROOT_PASS" ]]; then
    MYSQL_CMD="mysql -uroot -p${MYSQL_ROOT_PASS}"
fi

$MYSQL_CMD <<EOF
-- Create application database
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Create dedicated application user
CREATE USER IF NOT EXISTS '${DB_USER}'@'${DB_HOST}' IDENTIFIED BY '${DB_PASS}';

-- Grant privileges
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'${DB_HOST}';
FLUSH PRIVILEGES;
EOF

success "Database '${DB_NAME}' and user '${DB_USER}' created."

# ─── 8. Install phpMyAdmin ────────────────────────────────────────────────────
info "Installing phpMyAdmin..."

# Pre-seed debconf answers to avoid interactive prompts
echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"    | debconf-set-selections
echo "phpmyadmin phpmyadmin/dbconfig-install boolean true"                 | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/admin-user string root"                  | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/admin-pass password ${MYSQL_ROOT_PASS}" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/app-pass password ${DB_PASS}"           | debconf-set-selections
echo "phpmyadmin phpmyadmin/app-password-confirm password ${DB_PASS}"     | debconf-set-selections

DEBIAN_FRONTEND=noninteractive apt-get install -y -qq phpmyadmin > /dev/null

# Ensure phpMyAdmin is included in Apache config
if ! grep -q "phpmyadmin" /etc/apache2/apache2.conf; then
    echo "Include /etc/phpmyadmin/apache.conf" >> /etc/apache2/apache2.conf
fi

success "phpMyAdmin installed. Access at: ${APP_URL}/phpmyadmin"

# ─── 9. Install Composer ──────────────────────────────────────────────────────
info "Installing Composer..."
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer > /dev/null
    success "Composer installed."
else
    composer self-update --quiet 2>/dev/null || true
    success "Composer already installed (updated)."
fi

# ─── 10. Deploy project files ─────────────────────────────────────────────────
info "Setting up project directory at ${APP_DIR}..."

# If the script is run from the project root, copy it; otherwise skip.
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

if [[ "$SCRIPT_DIR" != "$APP_DIR" ]]; then
    if [[ -d "$APP_DIR" ]]; then
        warning "Directory ${APP_DIR} already exists. Skipping copy."
    else
        cp -r "$SCRIPT_DIR" "$APP_DIR"
        success "Project files copied to ${APP_DIR}."
    fi
else
    success "Project already located at ${APP_DIR}."
fi

cd "$APP_DIR"

# ─── 11. Configure .env ───────────────────────────────────────────────────────
info "Configuring .env file..."

if [[ ! -f ".env" ]]; then
    if [[ -f ".env.example" ]]; then
        cp .env.example .env
        success ".env created from .env.example."
    else
        # Create a minimal .env from scratch
        cat > .env <<ENVEOF
APP_NAME=AdSmart
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=${APP_URL}

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_NAME}
DB_USERNAME=${DB_USER}
DB_PASSWORD=${DB_PASS}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@adsamrt.local"
MAIL_FROM_NAME="\${APP_NAME}"
ENVEOF
        success ".env created from scratch."
    fi
fi

# Patch DB credentials in .env (handles both fresh and existing files)
sed -i "s|^DB_CONNECTION=.*|DB_CONNECTION=mysql|"   .env
sed -i "s|^DB_HOST=.*|DB_HOST=${DB_HOST}|"           .env
sed -i "s|^DB_PORT=.*|DB_PORT=${DB_PORT}|"           .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|"   .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USER}|"   .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|"   .env
sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|"           .env

success ".env database credentials configured."

# ─── 12. Install PHP dependencies ─────────────────────────────────────────────
info "Installing Composer dependencies (this may take a moment)..."
sudo -u www-data composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev 2>&1 | \
    grep -E "Installing|Generating|Nothing" || true
success "Composer dependencies installed."

# ─── 13. Generate application key ─────────────────────────────────────────────
info "Generating application key..."
php artisan key:generate --force
success "Application key generated."

# ─── 14. Run migrations and seeders ───────────────────────────────────────────
info "Running database migrations..."
php artisan migrate --force
success "Migrations completed."

info "Running database seeders..."
php artisan db:seed --force
success "Seeders completed."

# ─── 15. Storage & cache setup ────────────────────────────────────────────────
info "Setting up storage link and caches..."
php artisan storage:link --quiet || true
php artisan config:cache --quiet
php artisan route:cache --quiet
php artisan view:cache --quiet
success "Storage linked and caches optimised."

# ─── 16. File permissions ─────────────────────────────────────────────────────
info "Setting file permissions..."
chown -R www-data:www-data "$APP_DIR"
chmod -R 755 "$APP_DIR"
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
success "Permissions set."

# ─── 17. Apache virtual host ──────────────────────────────────────────────────
info "Configuring Apache virtual host..."

cat > "$APACHE_CONF" <<APACHEEOF
<VirtualHost *:80>
    ServerName localhost
    ServerAlias ${APP_NAME}.local

    DocumentRoot ${APP_DIR}/public

    <Directory ${APP_DIR}/public>
        AllowOverride All
        Require all granted
        Options -Indexes +FollowSymLinks
    </Directory>

    ErrorLog  \${APACHE_LOG_DIR}/${APP_NAME}_error.log
    CustomLog \${APACHE_LOG_DIR}/${APP_NAME}_access.log combined
</VirtualHost>
APACHEEOF

a2ensite "${APP_NAME}.conf" > /dev/null 2>&1
a2dissite 000-default.conf     > /dev/null 2>&1 || true

success "Virtual host configured: ${APACHE_CONF}"

# ─── 18. Restart Apache ───────────────────────────────────────────────────────
info "Restarting Apache..."
systemctl restart apache2
success "Apache restarted."

# ─── Done ─────────────────────────────────────────────────────────────────────
echo ""
echo "============================================================"
echo -e "${GREEN}  Installation complete!${NC}"
echo "============================================================"
echo ""
echo "  Application URL : ${APP_URL}"
echo "  phpMyAdmin URL  : ${APP_URL}/phpmyadmin"
echo ""
echo "  Database        : ${DB_NAME}"
echo "  DB User         : ${DB_USER}"
echo "  DB Password     : ${DB_PASS}"
echo ""
echo "  Project path    : ${APP_DIR}"
echo ""
echo -e "${YELLOW}  IMPORTANT: Change DB_PASS in this script before production use.${NC}"
echo "============================================================"
echo ""
