#!/usr/bin/env php
<?php

/**
 * Post-Installation Helper Script
 * This script helps configure the project after composer installation
 */

echo "\n";
echo "================================================\n";
echo "Laravel Jetstream TALL Stack Post-Installation\n";
echo "================================================\n\n";

// Check if we're in a fresh project
if (!file_exists('composer.json')) {
    echo "⚠ Warning: composer.json not found. Are you in the project directory?\n";
    exit(1);
}

// Check if Laravel is already installed
if (file_exists('artisan')) {
    echo "ℹ Laravel is already installed.\n";
    echo "  Run 'php artisan serve' to start the development server.\n\n";
    exit(0);
}

echo "ℹ This appears to be a fresh installation.\n";
echo "  To complete setup, run the installer:\n\n";
echo "  php setup/installer.php\n\n";
echo "  Or use the shell script:\n";
echo "  ./install.sh my-project-name\n\n";

exit(0);
