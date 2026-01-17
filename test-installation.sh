#!/bin/bash

# Test Script for Laravel Jetstream TALL Stack Starter Kit
# This script helps verify that the installation completed successfully

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

# Test counter
TESTS_PASSED=0
TESTS_FAILED=0

test_check() {
    local description=$1
    local command=$2
    
    if eval "$command" > /dev/null 2>&1; then
        print_success "$description"
        ((TESTS_PASSED++))
        return 0
    else
        print_error "$description"
        ((TESTS_FAILED++))
        return 1
    fi
}

print_header "Laravel Jetstream TALL Stack - Installation Verification"

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    print_error "Not in a Laravel project directory"
    exit 1
fi

print_info "Starting verification tests..."
echo ""

# Check required files
print_header "File Structure Tests"
test_check "composer.json exists" "[ -f composer.json ]"
test_check "package.json exists" "[ -f package.json ]"
test_check ".env file exists" "[ -f .env ]"
test_check "artisan file exists" "[ -f artisan ]"
test_check "vendor directory exists" "[ -d vendor ]"

# Check Laravel installation
print_header "Laravel Installation Tests"
test_check "Laravel is installed" "php artisan --version"
test_check "Application key is set" "grep -q 'APP_KEY=base64:' .env"
test_check "Storage directory is writable" "[ -w storage ]"
test_check "Bootstrap cache is writable" "[ -w bootstrap/cache ]"

# Check Jetstream installation
print_header "Jetstream Installation Tests"
test_check "Jetstream is installed" "composer show | grep -q laravel/jetstream"
test_check "Livewire is installed" "composer show | grep -q livewire/livewire"
test_check "Jetstream config exists" "[ -f config/jetstream.php ]"

# Check database configuration
print_header "Database Configuration Tests"
if grep -q "DB_CONNECTION=sqlite" .env; then
    print_info "SQLite database detected"
    test_check "SQLite database file exists" "[ -f database/database.sqlite ]"
elif grep -q "DB_CONNECTION=mysql" .env; then
    print_info "MySQL database detected"
    test_check "MySQL connection configured" "grep -q 'DB_DATABASE=' .env"
fi

# Check OTP configuration
print_header "OTP Configuration Tests"
test_check "OTP enabled in .env" "grep -q 'OTP_ENABLED=true' .env"
test_check "OTP length configured" "grep -q 'OTP_LENGTH=' .env"
test_check "OTP expiration configured" "grep -q 'OTP_EXPIRES_IN=' .env"

if grep -q "APP_ENV=local" .env; then
    print_info "Local environment detected"
    test_check "OTP prefill configured for local" "grep -q 'OTP_PREFILL_LOCAL=' .env"
fi

# Check frontend assets
print_header "Frontend Assets Tests"
test_check "node_modules exists" "[ -d node_modules ]"
test_check "Tailwind config exists" "[ -f tailwind.config.js ]"
test_check "Vite config exists" "[ -f vite.config.js ]"
test_check "Public assets directory exists" "[ -d public ]"

# Check migrations
print_header "Database Migration Tests"
if test_check "Migrations directory exists" "[ -d database/migrations ]"; then
    MIGRATION_COUNT=$(ls -1 database/migrations/*.php 2>/dev/null | wc -l)
    if [ $MIGRATION_COUNT -gt 0 ]; then
        print_success "Found $MIGRATION_COUNT migration files"
        ((TESTS_PASSED++))
    else
        print_warning "No migration files found"
    fi
fi

# Test artisan commands
print_header "Artisan Commands Tests"
test_check "Artisan is executable" "php artisan list"
test_check "Can clear cache" "php artisan cache:clear"
test_check "Can clear config" "php artisan config:clear"

# Test Claude AI configuration (optional)
print_header "Optional Features Tests"
if grep -q "CLAUDE_API_KEY=" .env && ! grep -q "CLAUDE_API_KEY=$" .env; then
    print_success "Claude AI API key is configured"
    ((TESTS_PASSED++))
else
    print_info "Claude AI not configured (optional)"
fi

# Summary
print_header "Test Summary"
echo ""
TOTAL_TESTS=$((TESTS_PASSED + TESTS_FAILED))
echo -e "Total Tests: ${BLUE}$TOTAL_TESTS${NC}"
echo -e "Passed: ${GREEN}$TESTS_PASSED${NC}"
echo -e "Failed: ${RED}$TESTS_FAILED${NC}"
echo ""

if [ $TESTS_FAILED -eq 0 ]; then
    print_success "All tests passed! Your installation looks good."
    echo ""
    print_info "Next steps:"
    echo "  1. Run migrations: php artisan migrate"
    echo "  2. Start server: php artisan serve"
    echo "  3. Visit: http://localhost:8000"
    echo ""
    exit 0
else
    print_error "Some tests failed. Please review the output above."
    echo ""
    print_info "Common fixes:"
    echo "  - Run: composer install"
    echo "  - Run: npm install"
    echo "  - Run: php artisan key:generate"
    echo "  - Check file permissions: chmod -R 775 storage bootstrap/cache"
    echo ""
    exit 1
fi
