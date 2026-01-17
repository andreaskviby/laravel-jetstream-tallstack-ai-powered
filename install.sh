#!/bin/bash

# Laravel Jetstream TALL Stack AI-Powered Starter Kit Installer
# Usage: curl -s https://raw.githubusercontent.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/main/install.sh | bash -s -- [project-name]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Helper functions
print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_header() {
    echo ""
    echo -e "${BLUE}================================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}================================================${NC}"
    echo ""
}

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed. Please install Composer first."
    echo "Visit: https://getcomposer.org/download/"
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    print_error "PHP is not installed. Please install PHP 8.2 or higher."
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
PHP_MAJOR=$(echo $PHP_VERSION | cut -d. -f1)
PHP_MINOR=$(echo $PHP_VERSION | cut -d. -f2)

if [ "$PHP_MAJOR" -lt 8 ] || ([ "$PHP_MAJOR" -eq 8 ] && [ "$PHP_MINOR" -lt 2 ]); then
    print_error "PHP 8.2 or higher is required. Current version: $PHP_VERSION"
    exit 1
fi

print_success "PHP $PHP_VERSION detected"

# Get project name
if [ -z "$1" ]; then
    read -p "Enter project name: " PROJECT_NAME
else
    PROJECT_NAME=$1
fi

if [ -z "$PROJECT_NAME" ]; then
    print_error "Project name is required"
    exit 1
fi

# Check if directory already exists
if [ -d "$PROJECT_NAME" ]; then
    print_error "Directory '$PROJECT_NAME' already exists"
    exit 1
fi

print_header "Laravel Jetstream TALL Stack AI-Powered Installer"

print_info "Creating Laravel project: $PROJECT_NAME"
print_info "This will install:"
echo "  • Laravel (latest stable version)"
echo "  • Laravel Jetstream with Livewire (TALL stack)"
echo "  • Teams support"
echo "  • OTP authentication"
echo "  • AI-powered features"
echo ""

# Create Laravel project
print_info "Creating Laravel project..."
composer create-project laravel/laravel "$PROJECT_NAME" --no-interaction --prefer-dist

cd "$PROJECT_NAME"

print_success "Laravel project created"

# Upgrade to latest stable version
print_info "Upgrading to latest stable Laravel version..."
composer update --no-interaction --prefer-stable

print_success "Laravel upgraded to latest stable version"

# Copy setup script
print_info "Downloading setup script..."
TEMP_SCRIPT=$(mktemp)
curl -sL https://raw.githubusercontent.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/main/setup/installer.php -o "$TEMP_SCRIPT"

# Verify the download succeeded
if [ ! -s "$TEMP_SCRIPT" ]; then
    print_error "Failed to download setup script"
    rm -f "$TEMP_SCRIPT"
    exit 1
fi

mv "$TEMP_SCRIPT" setup-installer.php

# Run the interactive setup
print_header "Starting Interactive Setup"
php setup-installer.php

# Clean up
rm -f setup-installer.php

print_header "Installation Complete!"
print_success "Your Laravel Jetstream TALL Stack project is ready!"
echo ""
print_info "Next steps:"
echo "  1. cd $PROJECT_NAME"
echo "  2. php artisan serve"
echo "  3. Visit http://localhost:8000"
echo ""
print_info "To run migrations:"
echo "  php artisan migrate"
echo ""
