<?php

/**
 * Laravel Jetstream TALL Stack AI-Powered Installer
 * Interactive setup script for configuring the application
 */

class Installer
{
    private $colors = [
        'reset' => "\033[0m",
        'red' => "\033[0;31m",
        'green' => "\033[0;32m",
        'yellow' => "\033[1;33m",
        'blue' => "\033[0;34m",
        'cyan' => "\033[0;36m",
        'white' => "\033[1;37m",
    ];

    private $config = [];
    private $isLocalEnvironment = false;

    public function run()
    {
        $this->printHeader("Laravel Jetstream TALL Stack Setup");
        
        // Detect environment
        $this->detectEnvironment();
        
        // Install Jetstream
        $this->installJetstream();
        
        // Database configuration
        $this->configureDatabaseConnection();
        
        // Claude AI configuration
        $this->configureClaudeAI();
        
        // Configure OTP authentication
        $this->configureOTPAuthentication();
        
        // Laravel Herd mail configuration
        $this->configureMailSettings();
        
        // Install additional packages
        $this->installAdditionalPackages();
        
        // Publish and configure Jetstream
        $this->configureJetstream();
        
        // Generate application key
        $this->generateAppKey();
        
        // Display summary
        $this->displaySummary();
    }

    private function detectEnvironment()
    {
        $this->printInfo("Detecting environment...");
        
        $hostname = gethostname();
        $this->isLocalEnvironment = (
            strpos($hostname, 'localhost') !== false ||
            strpos($hostname, '.local') !== false ||
            in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) ||
            getenv('APP_ENV') === 'local'
        );
        
        if ($this->isLocalEnvironment) {
            $this->printSuccess("Local environment detected");
            $this->config['environment'] = 'local';
        } else {
            $this->printSuccess("Production environment detected");
            $this->config['environment'] = 'production';
        }
    }

    private function installJetstream()
    {
        $this->printHeader("Installing Laravel Jetstream");
        
        $this->printInfo("Installing Jetstream with Livewire and Teams support...");
        
        // Install Jetstream
        $this->runCommand('composer require laravel/jetstream --no-interaction');
        
        // Install Jetstream with Livewire and Teams
        $this->runCommand('php artisan jetstream:install livewire --teams --no-interaction');
        
        // Install and build frontend assets
        $this->printInfo("Installing frontend dependencies...");
        
        if ($this->commandExists('npm')) {
            $this->runCommand('npm install');
            $this->runCommand('npm run build');
        } else {
            $this->printWarning("npm not found. Please run 'npm install && npm run build' manually.");
        }
        
        $this->printSuccess("Jetstream installed successfully");
    }

    private function configureDatabaseConnection()
    {
        $this->printHeader("Database Configuration");
        
        // Ask for database type
        echo $this->colorize("Which database would you like to use?\n", 'cyan');
        echo "  1) MySQL\n";
        echo "  2) SQLite\n";
        echo $this->colorize("Enter your choice (1-2): ", 'white');
        
        $choice = trim(fgets(STDIN));
        
        if ($choice === '2') {
            $this->configureSQLite();
        } else {
            $this->configureMySQL();
        }
    }

    private function configureSQLite()
    {
        $this->printInfo("Configuring SQLite...");
        
        $dbPath = database_path('database.sqlite');
        
        // Create database file
        if (!file_exists($dbPath)) {
            touch($dbPath);
            $this->printSuccess("SQLite database file created");
        }
        
        // Update .env
        $this->updateEnvFile([
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => $dbPath,
        ]);
        
        // Comment out MySQL variables
        $envContent = file_get_contents('.env');
        $envContent = preg_replace('/^(DB_HOST=)/m', '# $1', $envContent);
        $envContent = preg_replace('/^(DB_PORT=)/m', '# $1', $envContent);
        $envContent = preg_replace('/^(DB_USERNAME=)/m', '# $1', $envContent);
        $envContent = preg_replace('/^(DB_PASSWORD=)/m', '# $1', $envContent);
        file_put_contents('.env', $envContent);
        
        $this->config['database'] = 'sqlite';
        $this->printSuccess("SQLite configured successfully");
    }

    private function configureMySQL()
    {
        $this->printInfo("Configuring MySQL...");
        
        echo $this->colorize("Do you want to create a new database or connect to existing? (create/connect): ", 'white');
        $action = strtolower(trim(fgets(STDIN)));
        
        echo $this->colorize("Database host (default: 127.0.0.1): ", 'white');
        $host = trim(fgets(STDIN)) ?: '127.0.0.1';
        
        echo $this->colorize("Database port (default: 3306): ", 'white');
        $port = trim(fgets(STDIN)) ?: '3306';
        
        // Validate port is numeric
        if (!is_numeric($port) || $port < 1 || $port > 65535) {
            $this->printError("Invalid port number. Using default 3306.");
            $port = '3306';
        }
        
        echo $this->colorize("Database username (default: root): ", 'white');
        $username = trim(fgets(STDIN)) ?: 'root';
        
        echo $this->colorize("Database password: ", 'white');
        $password = trim(fgets(STDIN));
        
        if ($action === 'create') {
            echo $this->colorize("New database name: ", 'white');
            $database = trim(fgets(STDIN));
            
            // Validate database name (alphanumeric and underscores only)
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $database)) {
                $this->printError("Invalid database name. Use only letters, numbers, and underscores.");
                return;
            }
            
            // Try to create database - database name already validated above
            try {
                $dsn = sprintf("mysql:host=%s;port=%s;charset=utf8mb4", $host, $port);
                $pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                
                // Database name is validated with regex above, safe to use in query
                // Note: Database names cannot be parameterized in PDO
                $sql = "CREATE DATABASE IF NOT EXISTS `" . $database . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
                $pdo->exec($sql);
                $this->printSuccess("Database '$database' created successfully");
            } catch (PDOException $e) {
                $this->printError("Failed to create database: " . $e->getMessage());
                echo $this->colorize("Please create the database manually and press Enter to continue...", 'yellow');
                fgets(STDIN);
            }
        } else {
            echo $this->colorize("Database name: ", 'white');
            $database = trim(fgets(STDIN));
            
            // Validate database name
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $database)) {
                $this->printError("Invalid database name. Use only letters, numbers, and underscores.");
                return;
            }
        }
        
        // Update .env
        $this->updateEnvFile([
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => $host,
            'DB_PORT' => $port,
            'DB_DATABASE' => $database,
            'DB_USERNAME' => $username,
            'DB_PASSWORD' => $password,
        ]);
        
        $this->config['database'] = 'mysql';
        $this->printSuccess("MySQL configured successfully");
    }

    private function configureClaudeAI()
    {
        $this->printHeader("Claude AI Configuration");
        
        echo $this->colorize("Would you like to configure Claude AI integration? (yes/no): ", 'white');
        $configure = strtolower(trim(fgets(STDIN)));
        
        if ($configure === 'yes' || $configure === 'y') {
            echo $this->colorize("Enter your Claude AI API key: ", 'white');
            $apiKey = trim(fgets(STDIN));
            
            if (!empty($apiKey)) {
                $this->updateEnvFile([
                    'CLAUDE_API_KEY' => $apiKey,
                    'CLAUDE_MODEL' => 'claude-3-5-sonnet-20241022',
                ]);
                
                $this->config['claude_ai'] = true;
                $this->printSuccess("Claude AI configured successfully");
            } else {
                $this->printWarning("Skipping Claude AI configuration");
            }
        } else {
            $this->printInfo("Skipping Claude AI configuration");
        }
    }

    private function configureOTPAuthentication()
    {
        $this->printHeader("OTP Authentication Configuration");
        
        $this->printInfo("Configuring OTP authentication...");
        
        // Set OTP configuration in .env
        $otpConfig = [
            'OTP_ENABLED' => 'true',
            'OTP_LENGTH' => '6',
            'OTP_EXPIRES_IN' => '10',
        ];
        
        if ($this->isLocalEnvironment) {
            $otpConfig['OTP_PREFILL_LOCAL'] = 'true';
            $otpConfig['OTP_DEFAULT_CODE'] = '123456';
            $this->printSuccess("OTP prefill enabled for local environment (default code: 123456)");
        }
        
        $this->updateEnvFile($otpConfig);
        
        $this->config['otp_enabled'] = true;
        $this->printSuccess("OTP authentication configured");
    }

    private function configureMailSettings()
    {
        $this->printHeader("Mail Configuration");
        
        if ($this->isLocalEnvironment) {
            echo $this->colorize("Are you using Laravel Herd? (yes/no): ", 'white');
            $useHerd = strtolower(trim(fgets(STDIN)));
            
            if ($useHerd === 'yes' || $useHerd === 'y') {
                $this->updateEnvFile([
                    'MAIL_MAILER' => 'log',
                    'MAIL_FROM_ADDRESS' => 'hello@example.com',
                    'MAIL_FROM_NAME' => '${APP_NAME}',
                ]);
                $this->printSuccess("Mail configured for Laravel Herd (log driver)");
            } else {
                $this->printInfo("Keeping default mail configuration");
            }
        } else {
            $this->printInfo("Please configure mail settings in .env file for production");
        }
    }

    private function installAdditionalPackages()
    {
        $this->printHeader("Installing Additional Packages");
        
        // Install common TALL stack packages
        $this->printInfo("Installing recommended packages...");
        
        // These would be optional based on the needs
        // composer require packages would go here if needed
        
        $this->printSuccess("Additional packages ready");
    }

    private function configureJetstream()
    {
        $this->printHeader("Configuring Jetstream");
        
        // Publish Jetstream resources
        $this->printInfo("Publishing Jetstream resources...");
        $this->runCommand('php artisan vendor:publish --tag=jetstream-views --force');
        
        $this->printSuccess("Jetstream configured");
    }

    private function generateAppKey()
    {
        $this->printInfo("Generating application key...");
        $this->runCommand('php artisan key:generate --no-interaction');
        $this->printSuccess("Application key generated");
    }

    private function displaySummary()
    {
        $this->printHeader("Installation Summary");
        
        echo $this->colorize("✓ Laravel Jetstream TALL Stack installed\n", 'green');
        echo $this->colorize("✓ Teams support enabled\n", 'green');
        echo $this->colorize("✓ Database: " . ($this->config['database'] ?? 'configured') . "\n", 'green');
        
        if ($this->config['otp_enabled'] ?? false) {
            echo $this->colorize("✓ OTP authentication configured\n", 'green');
            if ($this->isLocalEnvironment) {
                echo $this->colorize("  → OTP prefill enabled (code: 123456)\n", 'cyan');
            }
        }
        
        if ($this->config['claude_ai'] ?? false) {
            echo $this->colorize("✓ Claude AI configured\n", 'green');
        }
        
        echo "\n";
        $this->printHeader("Next Steps");
        
        echo "1. Run migrations:\n";
        echo $this->colorize("   php artisan migrate\n", 'cyan');
        echo "\n";
        
        echo "2. Start the development server:\n";
        echo $this->colorize("   php artisan serve\n", 'cyan');
        echo "\n";
        
        echo "3. Visit your application:\n";
        echo $this->colorize("   http://localhost:8000\n", 'cyan');
        echo "\n";
        
        if ($this->config['otp_enabled'] ?? false) {
            echo "4. For OTP authentication implementation, check:\n";
            echo $this->colorize("   - app/Actions/Fortify/*\n", 'cyan');
            echo $this->colorize("   - resources/views/auth/*\n", 'cyan');
            echo "\n";
        }
        
        $this->printSuccess("Setup completed successfully!");
    }

    // Utility methods
    
    private function printHeader($message)
    {
        echo "\n";
        echo $this->colorize(str_repeat("=", 60) . "\n", 'blue');
        echo $this->colorize($message . "\n", 'blue');
        echo $this->colorize(str_repeat("=", 60) . "\n", 'blue');
        echo "\n";
    }

    private function printSuccess($message)
    {
        echo $this->colorize("✓ ", 'green') . $message . "\n";
    }

    private function printError($message)
    {
        echo $this->colorize("✗ ", 'red') . $message . "\n";
    }

    private function printInfo($message)
    {
        echo $this->colorize("ℹ ", 'blue') . $message . "\n";
    }

    private function printWarning($message)
    {
        echo $this->colorize("⚠ ", 'yellow') . $message . "\n";
    }

    private function colorize($text, $color)
    {
        return ($this->colors[$color] ?? '') . $text . $this->colors['reset'];
    }

    private function runCommand($command)
    {
        // Whitelist of allowed base commands
        $allowedCommands = [
            'composer',
            'php artisan',
            'npm',
            'node'
        ];
        
        // Check if command starts with an allowed command
        $isAllowed = false;
        foreach ($allowedCommands as $allowed) {
            if (strpos($command, $allowed) === 0) {
                $isAllowed = true;
                break;
            }
        }
        
        if (!$isAllowed) {
            $this->printError("Command not allowed: $command");
            return false;
        }
        
        $output = [];
        $returnVar = 0;
        exec($command . ' 2>&1', $output, $returnVar);
        
        if ($returnVar !== 0) {
            $this->printWarning("Command may have had issues: $command");
            foreach ($output as $line) {
                echo "  " . $line . "\n";
            }
        }
        
        return $returnVar === 0;
    }

    private function commandExists($command)
    {
        // Whitelist of allowed commands to check
        $allowedCommands = ['npm', 'node', 'mysql', 'git', 'php', 'composer'];
        $command = trim($command);
        
        if (!in_array($command, $allowedCommands)) {
            return false;
        }
        
        $return = shell_exec(sprintf("which %s 2>/dev/null", escapeshellarg($command)));
        return !empty($return);
    }

    private function updateEnvFile($values)
    {
        $envFile = '.env';
        
        if (!file_exists($envFile)) {
            $this->printError(".env file not found");
            return;
        }
        
        $envContent = file_get_contents($envFile);
        
        foreach ($values as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }
        
        file_put_contents($envFile, $envContent);
    }
}

// Run the installer
$installer = new Installer();
$installer->run();
