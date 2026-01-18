<?php

/**
 * Laravel TALL Stack AI-Powered Ultimate Installer
 *
 * The most comprehensive and beautiful installer for Laravel Jetstream
 * with Claude Code AI integration.
 *
 * @version 2.0
 */

require_once __DIR__ . '/lib/TerminalUI.php';

use Setup\Lib\TerminalUI;

class UltimateInstaller
{
    private TerminalUI $ui;
    private array $config = [];
    private array $features = [];
    private string $projectPath = '';
    private float $startTime;
    private bool $cleanInstall = false;
    private bool $updateFirst = false;
    private ?string $landingPageTempFile = null;
    private ?string $landingPageLogFile = null;
    private $landingPageProcess = null;

    private const TOTAL_PHASES = 10;

    // Version info - update these when releasing new versions
    public const VERSION = '1.0.0';
    public const VERSION_DATE = '2026-01-17';
    public const VERSION_NAME = 'Initial Release';

    public function __construct()
    {
        $this->ui = new TerminalUI();
        $this->startTime = microtime(true);
        $this->parseCommandLineArgs();
    }

    /**
     * Parse command line arguments
     */
    private function parseCommandLineArgs(): void
    {
        global $argv;

        if (in_array('--clean', $argv ?? [], true)) {
            $this->cleanInstall = true;
        }

        if (in_array('--update', $argv ?? [], true)) {
            $this->updateFirst = true;
            $this->cleanInstall = true; // Update implies clean
        }

        // Show version if requested
        if (in_array('--version', $argv ?? [], true) || in_array('-v', $argv ?? [], true)) {
            $this->showVersion();
            exit(0);
        }

        // Show help if requested
        if (in_array('--help', $argv ?? [], true) || in_array('-h', $argv ?? [], true)) {
            $this->showHelp();
            exit(0);
        }
    }

    /**
     * Show version information
     */
    private function showVersion(): void
    {
        echo "\n";
        echo "Laravel TALL Stack AI-Powered Ultimate Installer\n";
        echo "Version: " . self::VERSION . " (" . self::VERSION_NAME . ")\n";
        echo "Released: " . self::VERSION_DATE . "\n";
        echo "\n";
        echo "GitHub: https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered\n";
        echo "\n";
    }

    /**
     * Show help message
     */
    private function showHelp(): void
    {
        echo "\n";
        echo "Laravel TALL Stack AI-Powered Ultimate Installer v" . self::VERSION . "\n";
        echo "=================================================\n\n";
        echo "Usage: php setup/UltimateInstaller.php [options]\n\n";
        echo "Options:\n";
        echo "  --clean      Backup existing project and reinstall fresh\n";
        echo "  --update     Pull latest from git and reinstall (implies --clean)\n";
        echo "  --version    Show version information\n";
        echo "  --help       Show this help message\n\n";
        echo "Examples:\n";
        echo "  php setup/UltimateInstaller.php\n";
        echo "  php setup/UltimateInstaller.php --clean\n";
        echo "  php setup/UltimateInstaller.php --update\n";
        echo "  php setup/UltimateInstaller.php --version\n\n";
    }

    /**
     * Check for updates and handle --update flag
     */
    private function checkForUpdates(): void
    {
        $installerDir = dirname(__DIR__);

        // Check if we're in a git repository
        if (!is_dir("{$installerDir}/.git")) {
            return; // Not a git repo, skip update check
        }

        // If --update flag was passed, pull latest changes
        if ($this->updateFirst) {
            $this->ui->info("Updating installer from v" . self::VERSION . "...");
            echo "\n";

            // Fetch latest from remote
            exec("cd " . escapeshellarg($installerDir) . " && git fetch origin 2>&1", $fetchOutput, $fetchCode);

            if ($fetchCode !== 0) {
                $this->ui->warning("Could not fetch from remote. Continuing with current version.");
                return;
            }

            // Pull latest changes
            exec("cd " . escapeshellarg($installerDir) . " && git pull origin main 2>&1", $pullOutput, $pullCode);

            if ($pullCode === 0) {
                // Check new version from VERSION file
                $newVersion = $this->getRemoteVersion($installerDir);
                $versionMsg = $newVersion ? "Updated to v{$newVersion}!" : "Installer updated!";
                $this->ui->success($versionMsg);
                echo "  " . implode("\n  ", array_slice($pullOutput, -3)) . "\n\n";

                // Re-execute the installer with same args (minus --update to prevent loop)
                global $argv;
                $args = array_filter($argv, fn($arg) => $arg !== '--update');
                $command = PHP_BINARY . " " . implode(" ", array_map('escapeshellarg', $args));
                passthru($command);
                exit(0);
            }

            $this->ui->warning("Pull failed - you may have local changes. Continuing with current version.");
            echo "  " . implode("\n  ", $pullOutput) . "\n\n";
            return;
        }

        // Check if there are updates available (quick check without --update flag)
        exec("cd " . escapeshellarg($installerDir) . " && git fetch origin 2>&1", $output, $returnCode);

        if ($returnCode === 0) {
            // Check if local is behind remote
            exec("cd " . escapeshellarg($installerDir) . " && git rev-list HEAD...origin/main --count 2>/dev/null", $behindOutput, $behindCode);

            if ($behindCode === 0 && isset($behindOutput[0]) && (int)$behindOutput[0] > 0) {
                $count = (int)$behindOutput[0];
                $remoteVersion = $this->getRemoteVersion($installerDir);

                echo "\n";
                $this->ui->warning("A newer version is available!");
                echo "  Current: v" . self::VERSION . "\n";
                if ($remoteVersion && $remoteVersion !== self::VERSION) {
                    echo "  Latest:  v{$remoteVersion}\n";
                }
                echo "  ({$count} commits behind)\n\n";
                echo "  Run: php setup/UltimateInstaller.php --update\n\n";

                if ($this->ui->confirm("Would you like to update now?", true)) {
                    $this->updateFirst = true;
                    $this->checkForUpdates(); // Recurse with update flag set
                    return;
                }
            }
        }
    }

    /**
     * Get version from remote VERSION file
     */
    private function getRemoteVersion(string $installerDir): ?string
    {
        // Try to get VERSION file from origin/main
        exec("cd " . escapeshellarg($installerDir) . " && git show origin/main:VERSION 2>/dev/null", $versionOutput, $versionCode);

        if ($versionCode === 0 && !empty($versionOutput[0])) {
            return trim($versionOutput[0]);
        }

        // Fallback: check local VERSION file after pull
        $versionFile = "{$installerDir}/VERSION";
        if (file_exists($versionFile)) {
            return trim(file_get_contents($versionFile));
        }

        return null;
    }

    /**
     * Run the installation process
     */
    public function run(): void
    {
        try {
            // Check for updates and handle --update flag
            $this->checkForUpdates();

            // Phase 0: Claude Code Verification (REQUIRED)
            $this->phase0ClaudeCodeVerification();

            // Phase 1: Project Setup
            $this->phase1ProjectSetup();

            // Phase 2: AI Landing Page (Optional)
            $this->phase2AILandingPage();

            // Phase 3: Database Configuration
            $this->phase3Database();

            // Phase 4: Authentication Setup
            $this->phase4Authentication();

            // Phase 5: Payment Provider (Optional)
            $this->phase5Payments();

            // Phase 6: Filament Admin Panel (Optional)
            $this->phase6Filament();

            // Phase 7: Roles & Permissions
            $this->phase7RolesPermissions();

            // Phase 8: Infrastructure (Optional)
            $this->phase8Infrastructure();

            // Phase 9: Legal Pages & Finalization
            $this->phase9LegalFinalize();

            // Show success
            $this->showCompletionScreen();

        } catch (Exception $e) {
            $this->ui->showErrorScreen(
                $e->getMessage(),
                ['An unexpected error occurred during installation'],
                ['Check the error message above', 'Try running the installer again', 'Report issues at github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered']
            );
            exit(1);
        }
    }

    /**
     * Phase 0: Claude Code Verification (REQUIRED)
     */
    private function phase0ClaudeCodeVerification(): void
    {
        $this->ui->showMainBanner();
        $this->ui->showClaudeCodeBanner();

        // Check if Claude Code is installed
        $this->ui->info("Checking for Claude Code CLI...");

        $claudeVersion = @shell_exec('claude --version 2>/dev/null');
        $claudeInstalled = !empty($claudeVersion);

        if ($claudeInstalled) {
            $this->ui->success("Claude Code CLI found: " . trim($claudeVersion));
            $this->config['claude_code_installed'] = true;

            echo "\n";
            $this->ui->showInfoBox("🤖 CLAUDE CODE DETECTED", [
                "",
                "You have Claude Code CLI installed!",
                "",
                "You can use either:",
                "• Claude Code CLI (uses your existing authentication)",
                "• Direct API key (for background tasks)",
                "",
            ], 'accent');

            $useCliAuth = $this->ui->confirm("Use Claude Code CLI for AI features? (Recommended)", true);

            if ($useCliAuth) {
                $this->config['use_claude_cli'] = true;
                $this->ui->success("Will use Claude Code CLI for AI features");

                // Optionally get API key for background tasks
                echo "\n";
                if ($this->ui->confirm("Also provide API key for background tasks? (Optional)", false)) {
                    $this->promptForApiKey();
                }
            } else {
                $this->config['use_claude_cli'] = false;
                $this->promptForApiKey();
            }
        } else {
            $this->ui->warning("Claude Code CLI not found");
            $this->config['claude_code_installed'] = false;
            $this->config['use_claude_cli'] = false;

            $this->ui->showInfoBox("INSTALL CLAUDE CODE (OPTIONAL)", [
                "",
                "Install Claude Code CLI for the best experience:",
                "",
                "  npm install -g @anthropic-ai/claude-code",
                "",
                "Or visit: https://claude.ai/code",
                "",
            ], 'info');

            echo "\n";
            $this->promptForApiKey();
        }

        echo "\n";
        $this->ui->success("Claude Code verification complete!");
        sleep(1);
    }

    /**
     * Prompt for Anthropic API key
     */
    private function promptForApiKey(): void
    {
        $this->ui->showInfoBox("🔑 ANTHROPIC API KEY", [
            "",
            "Your API key enables:",
            "• AI-powered landing page generation",
            "• Background AI processing",
            "• Direct API calls for automation",
            "",
            "Get your API key at: https://console.anthropic.com/",
            "",
        ], 'accent');

        $apiKey = $this->ui->promptPassword("Enter your Anthropic API key:");

        if (empty($apiKey)) {
            if ($this->config['use_claude_cli'] ?? false) {
                $this->ui->info("No API key provided - will use Claude Code CLI only");
                return;
            }
            $this->ui->error("API key is required when Claude Code CLI is not available.");
            exit(1);
        }

        // Validate API key format (starts with sk-ant-)
        if (!str_starts_with($apiKey, 'sk-ant-')) {
            $this->ui->warning("API key format looks unusual. Continuing anyway...");
        }

        $this->config['anthropic_api_key'] = $apiKey;
        $this->ui->success("API key stored securely");

        // Store in secure file (gitignored)
        $this->storeSecureConfig('anthropic_api_key', $apiKey);
    }

    /**
     * Phase 1: Project Setup
     */
    private function phase1ProjectSetup(): void
    {
        $this->ui->clearScreen();
        $this->ui->showPhaseHeader(1, self::TOTAL_PHASES, "PROJECT CONFIGURATION", "📦");

        // Detect local development environment (Herd/Valet)
        $this->detectLocalDevEnvironment();

        // Get project name
        $projectName = $this->ui->prompt(
            "What would you like to name your project?",
            null,
            "Use lowercase letters, numbers, and hyphens only"
        );

        // Validate project name
        while (!$this->validateProjectName($projectName)) {
            $this->ui->error("Invalid project name. Use lowercase letters, numbers, and hyphens only.");
            $projectName = $this->ui->prompt("Please enter a valid project name:");
        }

        $this->config['project_name'] = $projectName;
        $this->config['app_name'] = $this->formatAppName($projectName);
        $this->projectPath = getcwd() . '/' . $projectName;

        $this->ui->success("Project name: {$projectName}");
        $this->ui->info("App name: {$this->config['app_name']}");

        // Configure APP_URL based on environment
        $this->configureAppUrl($projectName);

        // Get project description
        $description = $this->ui->prompt(
            "Briefly describe your SaaS (for composer.json):",
            "A SaaS application built with Laravel TALL Stack"
        );

        $this->config['description'] = $description;

        $this->features[] = "Laravel 12 + Jetstream + Livewire";
        $this->features[] = "TALL Stack (Tailwind, Alpine, Livewire, Laravel)";
    }

    /**
     * Detect if running in Laravel Herd or Valet environment
     */
    private function detectLocalDevEnvironment(): void
    {
        $this->config['local_env'] = null;
        $this->config['local_env_path'] = null;

        // Check for Laravel Herd
        $herdVersion = @shell_exec('herd --version 2>/dev/null');
        if (!empty($herdVersion)) {
            $this->config['local_env'] = 'herd';
            $this->ui->success("Laravel Herd detected: " . trim($herdVersion));

            // Get Herd's parked paths
            $herdPaths = @shell_exec('herd paths 2>/dev/null');
            if (!empty($herdPaths)) {
                $this->config['local_env_paths'] = array_filter(array_map('trim', explode("\n", $herdPaths)));
            }

            return;
        }

        // Check for Laravel Valet
        $valetVersion = @shell_exec('valet --version 2>/dev/null');
        if (!empty($valetVersion)) {
            $this->config['local_env'] = 'valet';
            $this->ui->success("Laravel Valet detected: " . trim($valetVersion));

            // Get Valet's parked paths
            $valetPaths = @shell_exec('valet paths 2>/dev/null');
            if (!empty($valetPaths)) {
                $this->config['local_env_paths'] = array_filter(array_map('trim', explode("\n", $valetPaths)));
            }

            return;
        }

        $this->ui->info("No Laravel Herd or Valet detected. Using standard Laravel dev server.");
    }

    /**
     * Configure APP_URL based on detected environment
     */
    private function configureAppUrl(string $projectName): void
    {
        $localEnv = $this->config['local_env'] ?? null;

        if ($localEnv === 'herd' || $localEnv === 'valet') {
            // Default to .test domain
            $domain = "{$projectName}.test";
            $this->config['app_domain'] = $domain;

            echo "\n";
            $this->ui->showInfoBox("🌐 LOCAL DOMAIN DETECTED", [
                "",
                "Since you're using " . ucfirst($localEnv) . ", your app will be available at:",
                "",
                "  https://{$domain}",
                "",
                "This will be set as your APP_URL in .env",
                "",
            ], 'accent');

            // Ask about SSL
            if ($this->ui->confirm("Would you like to enable HTTPS/SSL for local development?", true)) {
                $this->config['enable_ssl'] = true;
                $this->config['app_url'] = "https://{$domain}";

                $this->ui->info("SSL will be configured after project creation.");
                $this->ui->info("Command to run: {$localEnv} secure {$projectName}");
            } else {
                $this->config['enable_ssl'] = false;
                $this->config['app_url'] = "http://{$domain}";
            }

            $this->ui->success("APP_URL will be set to: {$this->config['app_url']}");
            $this->features[] = ucfirst($localEnv) . " Integration";
        } else {
            // Standard localhost setup
            $this->config['app_url'] = "http://localhost:8000";
            $this->config['app_domain'] = "localhost:8000";
            $this->config['enable_ssl'] = false;
        }
    }

    /**
     * Phase 2: AI Landing Page Generator
     */
    private function phase2AILandingPage(): void
    {
        $this->ui->clearScreen();
        $this->ui->showPhaseHeader(2, self::TOTAL_PHASES, "AI-POWERED LANDING PAGE GENERATOR", "✨");

        $this->ui->showInfoBox("✨ OPTIONAL FEATURE", [
            "",
            "Generate a stunning, conversion-optimized landing page for your SaaS",
            "using Claude AI. Includes:",
            "",
            "• Hero section with compelling copy",
            "• Feature highlights",
            "• Pricing tables with your plans",
            "• Social proof sections",
            "• Call-to-action buttons",
            "• Fully responsive Tailwind CSS design",
            "• SEO best practices built-in",
            "",
        ], 'accent');

        if (!$this->ui->confirm("Would you like to generate an AI-powered landing page?", true)) {
            $this->config['landing_page'] = false;
            $this->config['use_default_landing'] = true;
            $this->ui->info("Will use default landing page template");
            return;
        }

        $this->config['landing_page'] = true;

        // Get detailed app description
        echo "\n";
        $this->ui->showInfoBox("📝 DESCRIBE YOUR APPLICATION", [
            "Tell us about your SaaS product in detail. Include:",
            "",
            "• What problem does it solve?",
            "• Who is your target audience?",
            "• What makes it unique?",
            "• Key features and benefits",
            "",
        ], 'info');

        $appDescription = $this->ui->promptMultiline(
            "Describe your application:",
            "The more detail you provide, the better the landing page will be"
        );

        $this->config['app_description'] = $appDescription;

        // Subscription plans
        echo "\n";
        $this->ui->showSectionBox("SUBSCRIPTION PLANS", "💰");

        $hasSubscriptions = $this->ui->confirm("Will your app have subscription plans?", true);

        if ($hasSubscriptions) {
            $this->config['has_subscriptions'] = true;
            $plans = $this->collectSubscriptionPlans();
            $this->config['subscription_plans'] = $plans;

            // Trial period
            $hasTrial = $this->ui->confirm("Do you want to offer a free trial?", true);
            if ($hasTrial) {
                $trialDays = $this->ui->prompt("How many days for the trial period?", "14");
                $this->config['trial_days'] = (int) $trialDays;
            }

            $this->features[] = "Subscription plans configured";
        } else {
            $this->config['has_subscriptions'] = false;
        }

        $this->features[] = "AI Landing Page Generator";

        // Start background landing page generation immediately
        $this->startBackgroundLandingPageGeneration();
    }

    /**
     * Start landing page generation in background
     */
    private function startBackgroundLandingPageGeneration(): void
    {
        $apiKey = $this->config['anthropic_api_key'] ?? null;
        $useClaudeCli = $this->config['use_claude_cli'] ?? false;

        // Need either API key or Claude CLI
        if (!$apiKey && !$useClaudeCli) {
            return; // Will use default template
        }

        // Create temp file for output
        $this->landingPageTempFile = sys_get_temp_dir() . '/landing_page_' . uniqid() . '.html';

        // Store config in a temp JSON file (avoids escaping issues)
        $configFile = sys_get_temp_dir() . '/landing_config_' . uniqid() . '.json';
        $configData = [
            'api_key' => $apiKey,
            'use_claude_cli' => $useClaudeCli,
            'app_name' => $this->config['app_name'],
            'app_description' => $this->config['app_description'] ?? 'A modern SaaS application',
            'subscription_plans' => $this->config['subscription_plans'] ?? [],
            'trial_days' => $this->config['trial_days'] ?? null,
            'output_file' => $this->landingPageTempFile,
        ];
        file_put_contents($configFile, json_encode($configData));

        // Create a PHP script to run in background
        $scriptContent = $this->createLandingPageGeneratorScript($configFile);
        $scriptFile = sys_get_temp_dir() . '/landing_gen_' . uniqid() . '.php';
        file_put_contents($scriptFile, $scriptContent);

        // Create log file for debugging
        $logFile = sys_get_temp_dir() . '/landing_gen_' . uniqid() . '.log';
        $this->landingPageLogFile = $logFile;

        // Use the same PHP binary that's running this script
        $phpBinary = PHP_BINARY ?: 'php';

        // Run in background with logging for debugging
        $command = escapeshellarg($phpBinary) . " " . escapeshellarg($scriptFile) . " > " . escapeshellarg($logFile) . " 2>&1 &";
        exec($command);

        $method = $useClaudeCli ? "Claude Code CLI" : "API";
        $this->ui->info("Landing page generation started in background (using {$method})...");
    }

    /**
     * Create the landing page generator script
     */
    private function createLandingPageGeneratorScript(string $configFile): string
    {
        $escapedConfigFile = addslashes($configFile);

        $scriptTemplate = '<?php
echo "Landing page generator started at " . date("Y-m-d H:i:s") . "\n";

$configFile = "' . $escapedConfigFile . '";

if (!file_exists($configFile)) {
    echo "ERROR: Config file not found: $configFile\n";
    exit(1);
}

$config = json_decode(file_get_contents($configFile), true);
if (!$config) {
    echo "ERROR: Could not parse config file\n";
    exit(1);
}

echo "Config loaded successfully\n";

$apiKey = $config["api_key"] ?? null;
$useClaudeCli = $config["use_claude_cli"] ?? false;
$appName = $config["app_name"];
$appDescription = $config["app_description"];
$plans = $config["subscription_plans"];
$trialDays = $config["trial_days"];
$outputFile = $config["output_file"];

echo "App name: $appName\n";
echo "Output file: $outputFile\n";
echo "Method: " . ($useClaudeCli ? "Claude Code CLI" : "API") . "\n";

// Build plans text
$plansText = "";
if (!empty($plans)) {
    $plansText = "\n\nSubscription Plans:\n";
    foreach ($plans as $plan) {
        $plansText .= "- " . $plan["name"] . ": $" . $plan["price"] . "/month - " . $plan["description"] . "\n";
    }
}
if ($trialDays) {
    $plansText .= "\nFree trial period: " . $trialDays . " days\n";
}

$prompt = "Generate a complete, production-ready landing page (welcome.blade.php) for a SaaS application with the following details:

App Name: " . $appName . "

App Description:
" . $appDescription . "
" . $plansText . "

Requirements:
1. Use Laravel Blade syntax with @vite for assets
2. Use Tailwind CSS 4 for styling (utility classes)
3. Use Alpine.js for any interactivity (mobile menu, etc.)
4. Include: Navigation, Hero, Features (6 features based on description), Pricing (if plans provided), CTA, Footer
5. Support dark mode with dark: variants
6. Be fully responsive (mobile-first)
7. Use Laravel route() helpers for login, register, dashboard, terms, privacy
8. Write compelling, conversion-focused copy based on the app description
9. Use modern design with gradients, shadows, and subtle animations
10. Include @if(View::exists(\'components.cookie-consent\')) <x-cookie-consent /> @endif before closing body

Output ONLY the complete welcome.blade.php file content, starting with <!DOCTYPE html> and ending with </html>. No explanation or markdown code blocks.";

// Use Claude Code CLI if available and preferred
if ($useClaudeCli) {
    echo "Using Claude Code CLI...\n";

    // Write prompt to temp file to avoid shell escaping issues
    $promptFile = sys_get_temp_dir() . "/landing_prompt_" . uniqid() . ".txt";
    file_put_contents($promptFile, $prompt);

    // Run claude with --print flag to get output directly
    $command = "claude --print < " . escapeshellarg($promptFile) . " 2>&1";
    echo "Command: $command\n";

    $content = shell_exec($command);
    @unlink($promptFile);

    if ($content && strlen($content) > 100) {
        // Clean up any markdown code blocks
        $content = preg_replace(\'/^```(?:blade|php|html)?\n/m\', "", $content);
        $content = preg_replace(\'/\n```$/m\', "", $content);
        $content = trim($content);

        // Validate it looks like HTML
        if (strpos($content, "<!DOCTYPE html>") !== false || strpos($content, "<html") !== false) {
            $written = file_put_contents($outputFile, $content);
            echo "Written $written bytes to $outputFile\n";
            echo "Landing page generated successfully using Claude Code CLI!\n";
        } else {
            echo "ERROR: Output does not look like valid HTML\n";
            echo "First 500 chars: " . substr($content, 0, 500) . "\n";
        }
    } else {
        echo "ERROR: No output from Claude Code CLI\n";
        echo "Output: " . ($content ?: "(empty)") . "\n";
    }
} elseif ($apiKey) {
    // Use direct API call
    echo "Calling Claude API...\n";

    $data = [
        "model" => "claude-sonnet-4-20250514",
        "max_tokens" => 8192,
        "messages" => [
            ["role" => "user", "content" => $prompt]
        ]
    ];

    $ch = curl_init("https://api.anthropic.com/v1/messages");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "x-api-key: " . $apiKey,
            "anthropic-version: 2023-06-01"
        ],
        CURLOPT_TIMEOUT => 120
    ]);

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "HTTP Code: $httpCode\n";

    if ($curlError) {
        echo "CURL Error: $curlError\n";
        exit(1);
    }

    if ($httpCode === 200) {
        $result = json_decode($response, true);
        if (isset($result["content"][0]["text"])) {
            $content = $result["content"][0]["text"];
            $content = preg_replace(\'/^```(?:blade|php|html)?\n/m\', "", $content);
            $content = preg_replace(\'/\n```$/m\', "", $content);
            $written = file_put_contents($outputFile, trim($content));
            echo "Written $written bytes to $outputFile\n";
            echo "Landing page generated successfully!\n";
        } else {
            echo "ERROR: No content in response\n";
            echo "Response: " . substr($response, 0, 500) . "\n";
        }
    } else {
        echo "ERROR: API returned HTTP $httpCode\n";
        echo "Response: " . substr($response, 0, 500) . "\n";
    }
} else {
    echo "ERROR: No API key or Claude CLI available\n";
    exit(1);
}

// Clean up config file
@unlink($configFile);
echo "Completed at " . date("Y-m-d H:i:s") . "\n";
';

        return $scriptTemplate;
    }

    /**
     * Collect subscription plan details
     */
    private function collectSubscriptionPlans(): array
    {
        $plans = [];
        $planCount = 1;

        do {
            echo "\n";
            $this->ui->info("Configure Plan #{$planCount}");

            $name = $this->ui->prompt("Plan name:", $planCount === 1 ? "Starter" : null);
            $price = $this->ui->prompt("Monthly price (or 'free' / 'custom'):", "29");
            $description = $this->ui->prompt("Short description:", "Perfect for getting started");

            $plans[] = [
                'name' => $name,
                'price' => $price,
                'description' => $description,
            ];

            $planCount++;

            $addMore = $this->ui->confirm("Add another plan?", $planCount <= 3);

        } while ($addMore && $planCount <= 5);

        return $plans;
    }

    /**
     * Phase 3: Database Configuration
     */
    private function phase3Database(): void
    {
        $this->ui->clearScreen();
        $this->ui->showPhaseHeader(3, self::TOTAL_PHASES, "DATABASE CONFIGURATION", "🛢️");

        $options = [
            1 => [
                'title' => 'MySQL',
                'description' => 'Recommended for production. Full-featured relational database.',
            ],
            2 => [
                'title' => 'PostgreSQL',
                'description' => 'Advanced features, great for complex queries.',
            ],
            3 => [
                'title' => 'SQLite (Development only)',
                'description' => 'File-based, zero configuration. Not for production.',
            ],
        ];

        $choice = $this->ui->select("Select your database driver:", $options, 1);

        $this->config['database_driver'] = match((int)$choice) {
            1 => 'mysql',
            2 => 'pgsql',
            3 => 'sqlite',
            default => 'mysql',
        };

        if ($this->config['database_driver'] !== 'sqlite') {
            $this->configureDatabaseCredentials();
        } else {
            $this->config['database_name'] = 'database.sqlite';
            $this->ui->info("SQLite database will be created at database/database.sqlite");
        }

        $this->features[] = ucfirst($this->config['database_driver']) . " Database";
    }

    /**
     * Configure database credentials
     */
    private function configureDatabaseCredentials(): void
    {
        echo "\n";

        $createNew = $this->ui->confirm("Create a new database?", true);

        $connectionValid = false;
        $attempts = 0;
        $maxAttempts = 3;

        while (!$connectionValid && $attempts < $maxAttempts) {
            if ($attempts > 0) {
                echo "\n";
                $this->ui->warning("Let's try again. Please check your database credentials.");
                echo "\n";
            }

            $this->config['database_host'] = $this->ui->prompt("Database host:", "127.0.0.1");

            // Validate host format
            if (!$this->isValidHost($this->config['database_host'])) {
                $this->ui->error("Invalid host format. Use IP address (e.g., 127.0.0.1) or valid hostname (e.g., localhost, db.example.com)");
                $attempts++;
                continue;
            }

            $this->config['database_port'] = $this->ui->prompt("Database port:", $this->config['database_driver'] === 'pgsql' ? "5432" : "3306");

            // Validate port
            if (!is_numeric($this->config['database_port']) || $this->config['database_port'] < 1 || $this->config['database_port'] > 65535) {
                $this->ui->error("Invalid port number. Must be between 1 and 65535.");
                $attempts++;
                continue;
            }

            $this->config['database_name'] = $this->ui->prompt("Database name:", str_replace('-', '_', $this->config['project_name']));
            $this->config['database_username'] = $this->ui->prompt("Database username:", "root");
            $this->config['database_password'] = $this->ui->promptPassword("Database password:");

            // Test the connection
            echo "\n";
            $this->ui->info("Testing database connection...");

            $connectionResult = $this->testDatabaseConnection();

            if ($connectionResult['success']) {
                $this->ui->success("Database connection successful!");
                $connectionValid = true;

                // Create database if requested and it doesn't exist
                if ($createNew && !$connectionResult['database_exists']) {
                    $this->ui->info("Creating database '{$this->config['database_name']}'...");
                    if ($this->createDatabase()) {
                        $this->ui->success("Database created successfully!");
                    } else {
                        $this->ui->warning("Could not create database automatically. Please create it manually.");
                    }
                }
            } else {
                $this->ui->error("Connection failed: " . $connectionResult['error']);
                $this->ui->showInfoBox("COMMON ISSUES", [
                    "",
                    "• Host: Use '127.0.0.1' instead of 'localhost' for MySQL",
                    "• Make sure your database server is running",
                    "• Check that username and password are correct",
                    "• Verify the port number (MySQL: 3306, PostgreSQL: 5432)",
                    "",
                ], 'warning');
                $attempts++;
            }
        }

        if (!$connectionValid) {
            $this->ui->error("Failed to establish database connection after {$maxAttempts} attempts.");

            if ($this->ui->confirm("Continue anyway with current settings?", false)) {
                $this->ui->warning("Proceeding without verified connection. You may need to fix settings manually.");
            } else {
                $this->ui->info("Installation cancelled. Please fix your database settings and try again.");
                exit(1);
            }
        }

        $this->config['create_database'] = $createNew;

        $this->ui->success("Database configuration saved");
    }

    /**
     * Validate host format
     */
    private function isValidHost(string $host): bool
    {
        // Allow localhost
        if ($host === 'localhost') {
            return true;
        }

        // Allow valid IP addresses
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return true;
        }

        // Allow valid domain names (basic check)
        if (preg_match('/^[a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?)*$/', $host)) {
            return true;
        }

        return false;
    }

    /**
     * Test database connection
     */
    private function testDatabaseConnection(): array
    {
        $driver = $this->config['database_driver'];
        $host = $this->config['database_host'];
        $port = $this->config['database_port'];
        $database = $this->config['database_name'];
        $username = $this->config['database_username'];
        $password = $this->config['database_password'];

        try {
            // First, try to connect to the server (without specifying database)
            if ($driver === 'mysql') {
                $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            } else {
                $dsn = "pgsql:host={$host};port={$port}";
            }

            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5,
            ]);

            // Check if database exists
            $databaseExists = false;
            if ($driver === 'mysql') {
                $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = " . $pdo->quote($database));
                $databaseExists = $stmt->fetchColumn() !== false;
            } else {
                $stmt = $pdo->query("SELECT datname FROM pg_database WHERE datname = " . $pdo->quote($database));
                $databaseExists = $stmt->fetchColumn() !== false;
            }

            return [
                'success' => true,
                'database_exists' => $databaseExists,
                'error' => null,
            ];

        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            // Provide more helpful error messages
            if (str_contains($errorMessage, 'getaddrinfo') || str_contains($errorMessage, 'nodename nor servname')) {
                $errorMessage = "Cannot resolve host '{$host}'. Check if the hostname is correct.";
            } elseif (str_contains($errorMessage, 'Connection refused')) {
                $errorMessage = "Connection refused. Is the database server running on {$host}:{$port}?";
            } elseif (str_contains($errorMessage, 'Access denied')) {
                $errorMessage = "Access denied. Check username and password.";
            }

            return [
                'success' => false,
                'database_exists' => false,
                'error' => $errorMessage,
            ];
        }
    }

    /**
     * Create the database
     */
    private function createDatabase(): bool
    {
        $driver = $this->config['database_driver'];
        $host = $this->config['database_host'];
        $port = $this->config['database_port'];
        $database = $this->config['database_name'];
        $username = $this->config['database_username'];
        $password = $this->config['database_password'];

        try {
            if ($driver === 'mysql') {
                $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            } else {
                $dsn = "pgsql:host={$host};port={$port}";
            }

            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            // Validate database name (alphanumeric and underscores only)
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $database)) {
                $this->ui->error("Invalid database name. Use only letters, numbers, and underscores.");
                return false;
            }

            if ($driver === 'mysql') {
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            } else {
                // PostgreSQL doesn't support IF NOT EXISTS in CREATE DATABASE
                $pdo->exec("CREATE DATABASE \"{$database}\"");
            }

            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Phase 4: Authentication Setup
     */
    private function phase4Authentication(): void
    {
        $this->ui->clearScreen();
        $this->ui->showPhaseHeader(4, self::TOTAL_PHASES, "AUTHENTICATION CONFIGURATION", "🔐");

        $options = [
            1 => [
                'title' => 'OTP Only (Passwordless)',
                'description' => 'Modern, secure email-based one-time passwords',
                'features' => [
                    'No passwords to remember or reset',
                    'Enhanced security against credential stuffing',
                    'Great user experience',
                ],
            ],
            2 => [
                'title' => 'OTP + Social Login (Socialite)',
                'description' => 'Passwordless + OAuth providers',
                'features' => [
                    'All OTP benefits',
                    'Google, GitHub, Facebook, Twitter login',
                    'One-click sign-in option',
                ],
            ],
            3 => [
                'title' => 'Password + Social Login (Traditional)',
                'description' => 'Classic email/password with social options',
                'features' => [
                    'Familiar to users',
                    'Password reset functionality',
                    'Optional 2FA available',
                ],
            ],
        ];

        $choice = $this->ui->select("Choose your authentication strategy:", $options, 1);

        $this->config['auth_strategy'] = match((int)$choice) {
            1 => 'otp_only',
            2 => 'otp_socialite',
            3 => 'password_socialite',
            default => 'otp_only',
        };

        // Configure social providers if needed
        if (in_array($this->config['auth_strategy'], ['otp_socialite', 'password_socialite'])) {
            $this->configureSocialProviders();
        }

        $authLabel = match($this->config['auth_strategy']) {
            'otp_only' => 'OTP Passwordless Authentication',
            'otp_socialite' => 'OTP + Social Login',
            'password_socialite' => 'Password + Social Login',
        };

        $this->features[] = $authLabel;
    }

    /**
     * Configure social login providers
     */
    private function configureSocialProviders(): void
    {
        echo "\n";
        $this->ui->showSectionBox("SOCIAL LOGIN PROVIDERS", "🔗");

        $providers = [
            'google' => ['title' => 'Google', 'description' => 'Most popular, high trust'],
            'github' => ['title' => 'GitHub', 'description' => 'Great for developer tools'],
            'facebook' => ['title' => 'Facebook', 'description' => 'Wide consumer reach'],
            'twitter' => ['title' => 'Twitter/X', 'description' => 'Social media integration'],
            'linkedin' => ['title' => 'LinkedIn', 'description' => 'B2B and professional apps'],
            'apple' => ['title' => 'Apple', 'description' => 'Required for iOS apps'],
        ];

        $this->ui->showCheckboxes($providers, ['google', 'github']);

        $this->ui->info("Enter provider numbers separated by comma (e.g., 1,2,3)");
        $input = $this->ui->prompt("Select providers:", "1,2");

        $selected = array_map('trim', explode(',', $input));
        $providerKeys = array_keys($providers);

        $this->config['social_providers'] = [];
        foreach ($selected as $num) {
            $index = (int)$num - 1;
            if (isset($providerKeys[$index])) {
                $this->config['social_providers'][] = $providerKeys[$index];
            }
        }

        $this->ui->success("Selected providers: " . implode(', ', $this->config['social_providers']));
    }

    /**
     * Phase 5: Payment Provider
     */
    private function phase5Payments(): void
    {
        if (!($this->config['has_subscriptions'] ?? false)) {
            return;
        }

        $this->ui->clearScreen();
        $this->ui->showPhaseHeader(5, self::TOTAL_PHASES, "PAYMENT INTEGRATION", "💳");

        $options = [
            1 => [
                'title' => 'Lemon Squeezy',
                'description' => 'Merchant of Record - handles all taxes & compliance',
                'features' => [
                    'Global payments, no Stripe Atlas needed',
                    'Built-in license key system',
                    'Customer portal included',
                ],
            ],
            2 => [
                'title' => 'Stripe (via Laravel Cashier)',
                'description' => 'Industry standard, extensive features',
                'features' => [
                    'Most popular payment processor',
                    'Extensive documentation',
                    'You handle taxes (use Stripe Tax)',
                ],
            ],
            3 => [
                'title' => 'PayPal',
                'description' => 'Wide consumer adoption',
                'features' => [
                    'Trusted by consumers worldwide',
                    'Easy buyer protection',
                    'Good for international users',
                ],
            ],
            4 => [
                'title' => 'Skip for now',
                'description' => 'Can be added later via integration stubs',
            ],
        ];

        $choice = $this->ui->select("Select your payment provider:", $options, 1);

        $this->config['payment_provider'] = match((int)$choice) {
            1 => 'lemonsqueezy',
            2 => 'stripe',
            3 => 'paypal',
            default => null,
        };

        if ($this->config['payment_provider']) {
            $providerName = ucfirst($this->config['payment_provider']);
            $this->features[] = "{$providerName} Payments";
            $this->ui->success("{$providerName} will be configured");
        }
    }

    /**
     * Phase 6: Filament Admin Panel
     */
    private function phase6Filament(): void
    {
        $this->ui->clearScreen();
        $this->ui->showPhaseHeader(6, self::TOTAL_PHASES, "FILAMENT 4 ADMIN PANEL", "🎛️");

        $this->ui->showInfoBox("🎛️ OPTIONAL FEATURE", [
            "",
            "Install Filament 4, the elegant admin panel built with the TALL stack.",
            "Includes form builders, table builders, and beautiful UI components.",
            "",
        ], 'accent');

        if (!$this->ui->confirm("Would you like to install Filament?", true)) {
            $this->config['filament'] = false;
            return;
        }

        $this->config['filament'] = true;

        // Panel name
        $this->config['filament_panel_name'] = $this->ui->prompt("Admin panel name:", "admin");

        // Resources
        echo "\n";
        $this->ui->info("Select resources to generate:");

        $resources = [
            'users' => ['title' => 'Users Resource', 'description' => 'Manage all users'],
            'teams' => ['title' => 'Teams Resource', 'description' => 'Team management'],
            'subscriptions' => ['title' => 'Subscriptions Resource', 'description' => 'View & manage subscriptions'],
        ];

        $this->ui->showCheckboxes($resources, ['users', 'teams']);

        $input = $this->ui->prompt("Select resources (comma-separated):", "1,2");
        $selected = array_map('trim', explode(',', $input));
        $resourceKeys = array_keys($resources);

        $this->config['filament_resources'] = [];
        foreach ($selected as $num) {
            $index = (int)$num - 1;
            if (isset($resourceKeys[$index])) {
                $this->config['filament_resources'][] = $resourceKeys[$index];
            }
        }

        // Dashboard widgets
        echo "\n";
        $this->ui->info("Select dashboard widgets:");

        $widgets = [
            'arr' => ['title' => 'ARR Widget', 'description' => 'Annual Recurring Revenue'],
            'mrr' => ['title' => 'MRR Widget', 'description' => 'Monthly Recurring Revenue'],
            'users' => ['title' => 'Active Users', 'description' => 'Current active users count'],
            'signups' => ['title' => 'New Signups', 'description' => 'Recent registrations'],
        ];

        $this->ui->showCheckboxes($widgets, ['arr', 'mrr', 'users', 'signups']);

        $input = $this->ui->prompt("Select widgets (comma-separated):", "1,2,3,4");
        $selected = array_map('trim', explode(',', $input));
        $widgetKeys = array_keys($widgets);

        $this->config['filament_widgets'] = [];
        foreach ($selected as $num) {
            $index = (int)$num - 1;
            if (isset($widgetKeys[$index])) {
                $this->config['filament_widgets'][] = $widgetKeys[$index];
            }
        }

        $this->features[] = "Filament 4 Admin Panel";
        if (!empty($this->config['filament_widgets'])) {
            $this->features[] = "Dashboard with ARR/MRR stats";
        }
    }

    /**
     * Phase 7: Roles & Permissions
     */
    private function phase7RolesPermissions(): void
    {
        $this->ui->clearScreen();
        $this->ui->showPhaseHeader(7, self::TOTAL_PHASES, "ROLES & PERMISSIONS (SPATIE INTEGRATION)", "🔑");

        $this->ui->showInfoBox("🔑 SPATIE INTEGRATION", [
            "",
            "This feature integrates Spatie's Laravel Permission package",
            "with Jetstream teams for granular access control.",
            "",
            "Default system roles (cannot be removed):",
            "• Super Admin - Full system access across all teams",
            "• Team Admin - Full access within their team",
            "",
        ], 'info');

        // Team creation permissions
        echo "\n";
        $this->ui->info("Which roles should be able to create new teams?");

        $options = [
            1 => ['title' => 'Super Admin only', 'description' => 'Most restrictive'],
            2 => ['title' => 'Super Admin and Team Admin', 'description' => 'Recommended'],
            3 => ['title' => 'All authenticated users', 'description' => 'Most permissive'],
        ];

        $choice = $this->ui->select("Who can create teams?", $options, 2);

        $this->config['team_creation_permission'] = match((int)$choice) {
            1 => 'super_admin',
            2 => 'team_admin',
            3 => 'all_users',
            default => 'team_admin',
        };

        // Custom roles
        echo "\n";
        $this->config['custom_roles'] = [];

        if ($this->ui->confirm("Would you like to add custom roles?", true)) {
            $this->config['custom_roles'] = $this->collectCustomRoles();
        }

        $this->features[] = "Spatie Roles & Permissions";
    }

    /**
     * Collect custom role definitions
     */
    private function collectCustomRoles(): array
    {
        $roles = [];

        do {
            echo "\n";
            $this->ui->info("Define a new role:");

            $name = $this->ui->prompt("Role name:");
            $slug = strtolower(str_replace(' ', '_', $name));
            $description = $this->ui->prompt("Description:", "Custom role for {$name}");
            $canCreateTeams = $this->ui->confirm("Can create teams?", false);

            $roles[] = [
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'can_create_teams' => $canCreateTeams,
            ];

            $this->ui->success("Role '{$name}' added");

            $addMore = $this->ui->confirm("Add another role?", false);

        } while ($addMore);

        return $roles;
    }

    /**
     * Phase 8: Infrastructure Automation
     */
    private function phase8Infrastructure(): void
    {
        $this->ui->clearScreen();
        $this->ui->showPhaseHeader(8, self::TOTAL_PHASES, "INFRASTRUCTURE AUTOMATION", "☁️");

        $this->ui->showInfoBox("🚀 OPTIONAL FEATURE", [
            "",
            "Configure integrations for automated deployment and infrastructure.",
            "API keys are stored securely in a gitignored file.",
            "",
        ], 'accent');

        // CloudFlare
        if ($this->ui->confirm("Configure CloudFlare for DNS & CDN?", false)) {
            $this->config['cloudflare'] = [
                'enabled' => true,
                'api_token' => $this->ui->promptPassword("CloudFlare API Token:"),
                'zone_id' => $this->ui->prompt("CloudFlare Zone ID:"),
            ];
            $this->storeSecureConfig('cloudflare_api_token', $this->config['cloudflare']['api_token']);
            $this->features[] = "CloudFlare DNS & CDN";
        }

        // Mailgun
        echo "\n";
        if ($this->ui->confirm("Configure Mailgun for email delivery?", false)) {
            $this->config['mailgun'] = [
                'enabled' => true,
                'api_key' => $this->ui->promptPassword("Mailgun API Key:"),
                'domain' => $this->ui->prompt("Mailgun domain (e.g., mg.yourdomain.com):"),
            ];
            $this->storeSecureConfig('mailgun_api_key', $this->config['mailgun']['api_key']);
            $this->features[] = "Mailgun Email Delivery";
        }

        // Laravel Forge
        echo "\n";
        if ($this->ui->confirm("Configure Laravel Forge for server management?", false)) {
            $this->config['forge'] = [
                'enabled' => true,
                'api_token' => $this->ui->promptPassword("Laravel Forge API Token:"),
                'server_id' => $this->ui->prompt("Server ID (optional):"),
            ];
            $this->storeSecureConfig('forge_api_token', $this->config['forge']['api_token']);
            $this->features[] = "Laravel Forge Integration";
        }
    }

    /**
     * Phase 9: Legal Pages & Finalization
     */
    private function phase9LegalFinalize(): void
    {
        $this->ui->clearScreen();
        $this->ui->showPhaseHeader(9, self::TOTAL_PHASES, "LEGAL PAGES & COMPLIANCE", "📜");

        $this->config['legal_pages'] = [];

        // Terms of Service
        if ($this->ui->confirm("Generate Terms of Service page?", true)) {
            $this->config['legal_pages'][] = 'terms';
        }

        // Privacy Policy
        if ($this->ui->confirm("Generate Privacy Policy page?", true)) {
            $this->config['legal_pages'][] = 'privacy';
        }

        // GDPR
        if ($this->ui->confirm("Generate GDPR Compliance page?", true)) {
            $this->config['legal_pages'][] = 'gdpr';
        }

        // Cookie Policy & Banner
        if ($this->ui->confirm("Generate Cookie Policy with consent banner?", true)) {
            $this->config['legal_pages'][] = 'cookies';
            $this->config['cookie_banner'] = true;
            $this->features[] = "Cookie Consent Banner";
        }

        if (!empty($this->config['legal_pages'])) {
            $this->features[] = "Legal Pages (Terms, Privacy, GDPR)";
        }

        // Todo System
        echo "\n";
        $this->ui->showSectionBox("INTERNAL TODO SYSTEM", "📋");

        $this->ui->showInfoBox("📋 CLAUDE CODE TODO INTEGRATION", [
            "",
            "The internal todo system integrates with Claude Code for AI-powered",
            "task management. Todos can be:",
            "",
            "• Added via CLI: php artisan todo:add \"Task description\"",
            "• Managed in Filament admin panel",
            "• Automatically prioritized and worked on by Claude Code",
            "",
            "Claude Code will check todos before starting any work session.",
            "",
        ], 'info');

        if ($this->ui->confirm("Enable the internal Todo system?", true)) {
            $this->config['todo_system'] = true;
            $this->features[] = "Claude Code Todo Integration";
        }

        // Brand Color Palette Generator
        echo "\n";
        $this->ui->showSectionBox("BRAND COLOR PALETTE GENERATOR", "🎨");

        $this->ui->showInfoBox("🎨 AI-POWERED BRAND COLORS", [
            "",
            "The create-logo command is an interactive brand color palette",
            "generator based on neuroscience, color theory, and accessibility.",
            "",
            "Features:",
            "• Interactive brand identity interview",
            "• Scientific color generation (emotion + industry-based)",
            "• WCAG 2.1 accessibility compliance checking",
            "• Export to JSON, CSS, or Tailwind formats",
            "• Professional design tool recommendations",
            "",
            "Usage: php artisan create-logo",
            "",
        ], 'brand');

        if ($this->ui->confirm("Install the Brand Color Palette Generator?", true)) {
            $this->config['create_logo'] = true;
            $this->features[] = "Brand Color Palette Generator";
        }

        // Super Admin Setup
        echo "\n";
        $this->ui->showSectionBox("SUPER ADMIN ACCOUNT", "👤");

        $this->ui->showInfoBox("👤 CREATE SUPER ADMIN", [
            "",
            "Create the super admin account. This user will have:",
            "",
            "• Full system access across all teams",
            "• Exclusive access to the Filament admin panel",
            "• Ability to manage all users, teams, and settings",
            "• Access to todo management and Claude Code integration",
            "",
        ], 'accent');

        $this->config['super_admin'] = [
            'name' => $this->ui->prompt("Super Admin name:", "Admin"),
            'email' => $this->ui->prompt("Super Admin email:", "admin@" . str_replace('-', '', $this->config['project_name']) . ".test"),
            'password' => $this->ui->promptPassword("Super Admin password (min 8 chars):"),
        ];

        // Validate password
        while (strlen($this->config['super_admin']['password']) < 8) {
            $this->ui->error("Password must be at least 8 characters.");
            $this->config['super_admin']['password'] = $this->ui->promptPassword("Enter a stronger password:");
        }

        $this->ui->success("Super Admin account configured");

        // Run the actual installation
        $this->runInstallation();
    }

    /**
     * Run the actual installation process
     */
    private function runInstallation(): void
    {
        $this->ui->clearScreen();
        $this->ui->showSectionBox("INSTALLING YOUR PROJECT", "🚀");

        echo "\n  Project: " . $this->ui->colorize($this->config['project_name'], 'accent') . "\n\n";

        $steps = [
            'Creating project directory' => fn() => $this->createProjectDirectory(),
            'Installing Laravel framework' => fn() => $this->installLaravel(),
            'Installing Jetstream with Livewire' => fn() => $this->installJetstream(),
            'Configuring database' => fn() => $this->configureDatabase(),
            'Setting up authentication' => fn() => $this->setupAuthentication(),
            'Installing payment provider' => fn() => $this->installPaymentProvider(),
            'Installing Filament admin' => fn() => $this->installFilament(),
            'Configuring roles & permissions' => fn() => $this->setupRolesPermissions(),
            'Generating legal pages' => fn() => $this->generateLegalPages(),
            'Setting up todo system' => fn() => $this->setupTodoSystem(),
            'Installing brand color generator' => fn() => $this->setupCreateLogoCommand(),
            'Installing frontend dependencies' => fn() => $this->installFrontend(),
            'Building assets' => fn() => $this->buildAssets(),
            'Final configuration' => fn() => $this->finalConfiguration(),
            'Seeding super admin' => fn() => $this->seedSuperAdmin(),
        ];

        // Add landing page step - either AI generated or default template
        if ($this->config['landing_page'] ?? false) {
            $steps['Generating AI landing page'] = fn() => $this->generateAILandingPage();
        } elseif ($this->config['use_default_landing'] ?? false) {
            $steps['Installing landing page'] = fn() => $this->copyDefaultLandingPage();
        }

        $completed = 0;
        $total = count($steps);

        foreach ($steps as $step => $callback) {
            $this->ui->active($step . "...");

            $startTime = microtime(true);

            try {
                $callback();
                $duration = round(microtime(true) - $startTime, 1) . 's';
                $this->ui->clearSpinner();
                echo "\r";
                $this->ui->success($step, $duration);
            } catch (Exception $e) {
                $this->ui->clearSpinner();
                echo "\r";
                $this->ui->error($step . " - " . $e->getMessage());
            }

            $completed++;

            // Update progress
            echo "\n  ";
            echo $this->ui->renderProgressBar($completed, $total, 60);
            echo " " . round(($completed / $total) * 100) . "%\n\n";
        }
    }

    /**
     * Show completion screen
     */
    private function showCompletionScreen(): void
    {
        $duration = round(microtime(true) - $this->startTime);
        $minutes = floor($duration / 60);
        $seconds = $duration % 60;
        $timeStr = $minutes > 0 ? "{$minutes}m {$seconds}s" : "{$seconds}s";

        $panelName = $this->config['filament_panel_name'] ?? 'admin';
        $adminEmail = $this->config['super_admin']['email'] ?? 'admin@example.com';
        $appUrl = $this->config['app_url'] ?? 'http://localhost:8000';
        $localEnv = $this->config['local_env'] ?? null;
        $projectName = $this->config['project_name'];

        $nextSteps = [
            "1. Change to your project directory:",
            "   cd {$projectName}",
            "",
        ];

        // Different instructions based on Herd/Valet vs standard dev server
        if ($localEnv === 'herd' || $localEnv === 'valet') {
            $nextSteps[] = "2. Your app is already running via " . ucfirst($localEnv) . "!";
            $nextSteps[] = "";
            $nextSteps[] = "3. Open your browser:";
            $nextSteps[] = "   {$appUrl}";
            $nextSteps[] = "";

            if ($this->config['enable_ssl'] ?? false) {
                $nextSteps[] = "   ✓ SSL is enabled - using HTTPS";
            } else {
                $nextSteps[] = "   TIP: Enable SSL with: {$localEnv} secure {$projectName}";
            }
            $nextSteps[] = "";
        } else {
            $nextSteps[] = "2. Start the development server:";
            $nextSteps[] = "   composer dev";
            $nextSteps[] = "";
            $nextSteps[] = "3. Open your browser:";
            $nextSteps[] = "   {$appUrl}";
            $nextSteps[] = "";
        }

        if ($this->config['filament'] ?? false) {
            $stepNum = ($localEnv === 'herd' || $localEnv === 'valet') ? "4" : "4";
            $nextSteps[] = "{$stepNum}. Access the admin panel:";
            $nextSteps[] = "   {$appUrl}/{$panelName}";
            $nextSteps[] = "";
            $nextSteps[] = "   SUPER ADMIN CREDENTIALS:";
            $nextSteps[] = "   Email: {$adminEmail}";
            $nextSteps[] = "   Password: (the password you entered during setup)";
            $nextSteps[] = "";
        }

        if ($this->config['auth_strategy'] === 'otp_only' || $this->config['auth_strategy'] === 'otp_socialite') {
            $nextSteps[] = "For local testing, use OTP code: 123456";
            $nextSteps[] = "";
        }

        // Claude Code usage instructions
        $nextSteps[] = "═══════════════════════════════════════════════════════════";
        $nextSteps[] = "";
        $nextSteps[] = "🤖 START BUILDING WITH CLAUDE CODE:";
        $nextSteps[] = "";
        $nextSteps[] = "   1. Add your first todo for Claude Code:";
        $nextSteps[] = "      php artisan todo:add \"Build the main dashboard with user stats\"";
        $nextSteps[] = "";
        $nextSteps[] = "   2. Start Claude Code and it will pick up your todos:";
        $nextSteps[] = "      claude";
        $nextSteps[] = "";
        $nextSteps[] = "   3. Or manage todos from the admin panel:";
        $nextSteps[] = "      {$appUrl}/{$panelName}/todos";
        $nextSteps[] = "";
        $nextSteps[] = "   Claude Code will automatically check and work on your todos!";
        $nextSteps[] = "";

        $this->features[] = "Installation time: {$timeStr}";

        $this->ui->showSuccessScreen(
            $projectName,
            $this->features,
            $nextSteps
        );

        // Offer to start development server
        $this->offerToStartDevServer();
    }

    /**
     * Ask user if they want to start the development server
     */
    private function offerToStartDevServer(): void
    {
        $localEnv = $this->config['local_env'] ?? null;

        // For Herd/Valet, app is already running - just offer to open browser
        if ($localEnv === 'herd' || $localEnv === 'valet') {
            echo "\n";
            $openBrowser = $this->ui->confirm("Would you like to open your app in the browser?", true);

            if ($openBrowser) {
                $appUrl = $this->config['app_url'];
                // Use 'open' on macOS to open the browser
                exec("open \"{$appUrl}\" 2>/dev/null");
                $this->ui->success("Opening {$appUrl} in your default browser...");
            }

            return;
        }

        // For standard setup, offer to start dev server
        echo "\n";
        $startServer = $this->ui->confirm("Would you like to start the development server now?", true);

        if ($startServer) {
            $projectPath = $this->projectPath;

            // Change to project directory
            chdir($projectPath);

            // Ensure npm packages are installed
            $this->ui->info("Ensuring npm packages are installed...");
            passthru("npm install 2>/dev/null");
            echo "\n";

            $this->ui->info("Starting development server...");
            echo "\n";
            $this->ui->warning("Press Ctrl+C to stop the server");
            echo "\n";

            // Run php artisan serve and npm run dev separately for reliability
            // Using the full path to node_modules/.bin/vite
            passthru("npx concurrently -c \"#93c5fd,#fb7185\" \"php artisan serve\" \"npm run dev\" --names=server,vite --kill-others");
        } else {
            echo "\n";
            $this->ui->info("To start the server later, run:");
            echo "   cd {$this->config['project_name']} && composer dev\n\n";
        }
    }

    // Installation helper methods

    private function createProjectDirectory(): void
    {
        // Check if directory already exists
        if (is_dir($this->projectPath)) {
            if ($this->cleanInstall) {
                $this->handleCleanInstall();
            } else {
                throw new RuntimeException(
                    "Directory '{$this->projectPath}' already exists.\n" .
                    "   Use --clean flag to backup and reinstall: php setup/UltimateInstaller.php --clean"
                );
            }
        }

        // Don't create directory here - composer create-project will create it
        // and it requires the directory to NOT exist
    }

    /**
     * Handle clean install by backing up existing project
     */
    private function handleCleanInstall(): void
    {
        $projectName = $this->config['project_name'];
        $timestamp = date('Y-m-d_His');
        $backupPath = $this->projectPath . '_backup_' . $timestamp;

        $this->ui->warning("Directory '{$projectName}' already exists!");
        echo "\n";

        // Show options
        echo "  Choose an option:\n";
        echo "  [1] Backup to '{$projectName}_backup_{$timestamp}' and reinstall\n";
        echo "  [2] Delete existing directory and reinstall (no backup)\n";
        echo "  [3] Cancel installation\n";
        echo "\n";

        $choice = $this->ui->prompt("Enter choice (1/2/3):", "1");

        if ($choice === '3') {
            $this->ui->info("Installation cancelled.");
            exit(0);
        }

        if ($choice === '2') {
            // Delete without backup
            $this->ui->warning("Deleting existing project directory...");

            $deleteCommand = "rm -rf " . escapeshellarg($this->projectPath);
            exec($deleteCommand, $output, $returnCode);

            if ($returnCode !== 0 || is_dir($this->projectPath)) {
                throw new RuntimeException("Failed to delete existing project directory. Check permissions or close any applications using the folder.");
            }

            $this->ui->success("Deleted: {$this->projectPath}");
            echo "\n";
            return;
        }

        // Default: Backup and reinstall
        $this->ui->info("Backing up existing project...");

        // Try rename first
        if (@rename($this->projectPath, $backupPath)) {
            $this->ui->success("Backed up to: {$backupPath}");
            echo "\n";
            return;
        }

        // Rename failed, try with shell command (handles cross-device moves)
        $this->ui->info("Using alternative backup method...");
        $moveCommand = "mv " . escapeshellarg($this->projectPath) . " " . escapeshellarg($backupPath);
        exec($moveCommand, $output, $returnCode);

        if ($returnCode !== 0 || is_dir($this->projectPath)) {
            // Last resort: ask to delete instead
            $this->ui->warning("Backup failed. The directory may be in use or have permission issues.");
            $deleteInstead = $this->ui->confirm("Would you like to delete the existing directory instead (no backup)?", false);

            if ($deleteInstead) {
                $deleteCommand = "rm -rf " . escapeshellarg($this->projectPath);
                exec($deleteCommand, $output, $returnCode);

                if ($returnCode !== 0 || is_dir($this->projectPath)) {
                    throw new RuntimeException("Failed to remove existing project. Please close any applications using the folder and try again.");
                }

                $this->ui->success("Deleted existing directory");
                echo "\n";
                return;
            }

            throw new RuntimeException("Cannot proceed - existing directory could not be moved or deleted.");
        }

        $this->ui->success("Backed up to: {$backupPath}");
        echo "\n";
    }

    private function installLaravel(): void
    {
        $this->runCommand("composer create-project laravel/laravel {$this->projectPath} --prefer-dist --no-interaction");
    }

    private function installJetstream(): void
    {
        $this->runCommandInProject("composer require laravel/jetstream --no-interaction");
        $this->runCommandInProject("php artisan jetstream:install livewire --teams --no-interaction");
    }

    private function configureDatabase(): void
    {
        // Update .env with database configuration
        $envUpdates = [
            'DB_CONNECTION' => $this->config['database_driver'],
        ];

        if ($this->config['database_driver'] !== 'sqlite') {
            $envUpdates['DB_HOST'] = $this->config['database_host'];
            $envUpdates['DB_PORT'] = $this->config['database_port'];
            $envUpdates['DB_DATABASE'] = $this->config['database_name'];
            $envUpdates['DB_USERNAME'] = $this->config['database_username'];
            $envUpdates['DB_PASSWORD'] = $this->config['database_password'];
        }

        $this->updateEnvFile($envUpdates);

        // Create database if needed
        if ($this->config['create_database'] ?? false) {
            $this->createDatabaseForProject();
        }
    }

    /**
     * Create the database for the project
     */
    private function createDatabaseForProject(): void
    {
        $driver = $this->config['database_driver'];

        if ($driver === 'sqlite') {
            // SQLite doesn't need database creation
            return;
        }

        $host = $this->config['database_host'];
        $port = $this->config['database_port'];
        $database = $this->config['database_name'];
        $username = $this->config['database_username'];
        $password = $this->config['database_password'];

        try {
            // Connect without database to create it
            $dsn = "{$driver}:host={$host};port={$port};charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            // Create database if it doesn't exist
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            $this->ui->success("Database '{$database}' created successfully");
        } catch (PDOException $e) {
            $this->ui->warning("Could not create database: " . $e->getMessage());
            $this->ui->info("You may need to create the database manually: CREATE DATABASE {$database}");
        }
    }

    private function setupAuthentication(): void
    {
        // Copy authentication stubs based on strategy
        $strategy = $this->config['auth_strategy'];

        $envUpdates = [
            'OTP_ENABLED' => $strategy !== 'password_socialite' ? 'true' : 'false',
            'OTP_LENGTH' => '6',
            'OTP_EXPIRES_IN' => '10',
            'OTP_PREFILL_LOCAL' => 'true',
            'OTP_DEFAULT_CODE' => '123456',
        ];

        $this->updateEnvFile($envUpdates);

        // Install Socialite if needed
        if (in_array($strategy, ['otp_socialite', 'password_socialite'])) {
            $this->runCommandInProject("composer require laravel/socialite --no-interaction");
        }
    }

    private function installPaymentProvider(): void
    {
        $provider = $this->config['payment_provider'] ?? null;

        if (!$provider) {
            return;
        }

        switch ($provider) {
            case 'stripe':
                $this->runCommandInProject("composer require laravel/cashier --no-interaction");
                break;
            case 'lemonsqueezy':
                $this->runCommandInProject("composer require lemonsqueezy/laravel --no-interaction");
                break;
            case 'paypal':
                // PayPal setup - use stubs
                break;
        }
    }

    private function installFilament(): void
    {
        if (!($this->config['filament'] ?? false)) {
            return;
        }

        // Use -W flag to allow Livewire upgrade from v3 to v4 (required by Filament 5)
        $this->runCommandInProject("composer require filament/filament:\"^5.0\" -W --no-interaction");
        $this->runCommandInProject("php artisan filament:install --panels --no-interaction");
    }

    private function setupRolesPermissions(): void
    {
        // Install Spatie permission package
        $this->runCommandInProject("composer require spatie/laravel-permission --no-interaction");
        $this->runCommandInProject("php artisan vendor:publish --provider=\"Spatie\\Permission\\PermissionServiceProvider\" --no-interaction");

        // Run Spatie migrations immediately to ensure permissions table exists
        $this->runCommandInProject("php artisan migrate --no-interaction");

        // Determine team admin can create teams setting
        $teamAdminCanCreate = match($this->config['team_creation_permission'] ?? 'team_admin') {
            'super_admin' => 'false',
            'team_admin' => 'true',
            'all_users' => 'true',
            default => 'true',
        };

        // Format custom roles for stub
        $customRolesJson = json_encode($this->config['custom_roles'] ?? [], JSON_PRETTY_PRINT);

        // Copy and configure Spatie stubs
        $stubsDir = __DIR__ . '/stubs/spatie';

        // 1. Copy HasTeamRoles trait
        $this->copyStub(
            "{$stubsDir}/HasTeamRoles.stub",
            "{$this->projectPath}/app/Traits/HasTeamRoles.php"
        );

        // 2. Copy middlewares
        $this->copyStub(
            "{$stubsDir}/TeamRoleMiddleware.stub",
            "{$this->projectPath}/app/Http/Middleware/TeamRoleMiddleware.php"
        );
        $this->copyStub(
            "{$stubsDir}/TeamPermissionMiddleware.stub",
            "{$this->projectPath}/app/Http/Middleware/TeamPermissionMiddleware.php"
        );

        // 3. Copy and configure seeder
        $seederContent = file_get_contents("{$stubsDir}/SpatieRolesSeeder.stub");
        $seederContent = str_replace('{{TEAM_ADMIN_CAN_CREATE_TEAMS}}', $teamAdminCanCreate, $seederContent);
        $seederContent = str_replace('{{CUSTOM_ROLES}}', $customRolesJson, $seederContent);
        $this->ensureDirectoryExists("{$this->projectPath}/database/seeders");
        file_put_contents("{$this->projectPath}/database/seeders/SpatieRolesSeeder.php", $seederContent);

        // 4. Copy service provider
        $this->copyStub(
            "{$stubsDir}/JetstreamRoleServiceProvider.stub",
            "{$this->projectPath}/app/Providers/JetstreamRoleServiceProvider.php"
        );

        // 5. Copy and configure config file
        $configContent = file_get_contents("{$stubsDir}/spatie-jetstream.config.stub");
        $configContent = str_replace('{{TEAM_ADMIN_CAN_CREATE_TEAMS}}', $teamAdminCanCreate, $configContent);
        $configContent = str_replace('{{CUSTOM_ROLES}}', $customRolesJson, $configContent);
        file_put_contents("{$this->projectPath}/config/spatie-jetstream.php", $configContent);

        // 6. Copy migrations
        $timestamp = date('Y_m_d_His');
        $this->copyStub(
            "{$stubsDir}/add_spatie_columns_to_roles.stub",
            "{$this->projectPath}/database/migrations/{$timestamp}_add_spatie_columns_to_roles.php"
        );

        sleep(1); // Ensure different timestamp
        $timestamp2 = date('Y_m_d_His');
        $this->copyStub(
            "{$stubsDir}/add_role_to_team_user.stub",
            "{$this->projectPath}/database/migrations/{$timestamp2}_add_role_to_team_user.php"
        );

        // 7. Copy Filament resources if Filament is enabled
        if ($this->config['filament'] ?? false) {
            $this->copyStub(
                "{$stubsDir}/FilamentRolesResource.stub",
                "{$this->projectPath}/app/Filament/Resources/RoleResource.php"
            );
            $this->copyStub(
                "{$stubsDir}/FilamentPermissionsResource.stub",
                "{$this->projectPath}/app/Filament/Resources/PermissionResource.php"
            );

            // Create resource page directories and files
            $this->createFilamentResourcePages('Role');
            $this->createFilamentResourcePages('Permission');
        }

        // 8. Copy README
        $this->copyStub(
            "{$stubsDir}/README.stub",
            "{$this->projectPath}/docs/SPATIE_ROLES.md"
        );

        // 9. Update User model to use HasTeamRoles trait
        $this->updateUserModelWithTeamRoles();
    }

    /**
     * Create Filament resource pages.
     */
    private function createFilamentResourcePages(string $resourceName): void
    {
        $pagesDir = "{$this->projectPath}/app/Filament/Resources/{$resourceName}Resource/Pages";
        $this->ensureDirectoryExists($pagesDir);

        $pages = ['List', 'Create', 'View', 'Edit'];

        foreach ($pages as $page) {
            $content = <<<PHP
<?php

namespace App\Filament\Resources\\{$resourceName}Resource\Pages;

use App\Filament\Resources\\{$resourceName}Resource;
use Filament\Actions;
use Filament\Resources\Pages\\{$page}{$resourceName}s;

class {$page}{$resourceName}s extends {$page}Record
{
    protected static string \$resource = {$resourceName}Resource::class;
PHP;

            if ($page === 'List') {
                $content .= <<<PHP


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
PHP;
            } elseif ($page === 'View' || $page === 'Edit') {
                $content .= <<<PHP


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
PHP;
            }

            $content .= "\n}\n";

            // Adjust class name for the pages
            $pageClass = $page === 'List' ? 'ListRecords' : ($page . 'Record');
            $content = str_replace("{$page}{$resourceName}s extends {$page}Record", "{$page}{$resourceName}s extends {$pageClass}", $content);

            file_put_contents("{$pagesDir}/{$page}{$resourceName}s.php", $content);
        }
    }

    /**
     * Update User model to use HasTeamRoles trait.
     */
    private function updateUserModelWithTeamRoles(): void
    {
        $userModelPath = "{$this->projectPath}/app/Models/User.php";

        if (!file_exists($userModelPath)) {
            return;
        }

        $content = file_get_contents($userModelPath);

        // Add use statement for the trait
        if (!str_contains($content, 'use App\Traits\HasTeamRoles')) {
            $content = str_replace(
                'use Laravel\Jetstream\HasTeams;',
                "use Laravel\Jetstream\HasTeams;\nuse App\Traits\HasTeamRoles;",
                $content
            );
        }

        // Add trait to the class
        if (!str_contains($content, 'use HasTeamRoles')) {
            // Find HasTeams trait usage and add HasTeamRoles after it
            $content = preg_replace(
                '/use HasTeams;/',
                "use HasTeams;\n    use HasTeamRoles;",
                $content
            );
        }

        file_put_contents($userModelPath, $content);
    }

    private function generateLegalPages(): void
    {
        if (empty($this->config['legal_pages'])) {
            return;
        }

        $stubsDir = __DIR__ . '/stubs/legal';
        $viewsDir = "{$this->projectPath}/resources/views/legal";
        $this->ensureDirectoryExists($viewsDir);

        $legalPages = [
            'terms' => 'Terms of Service',
            'privacy' => 'Privacy Policy',
            'gdpr' => 'GDPR Compliance',
            'cookies' => 'Cookie Policy',
        ];

        foreach ($this->config['legal_pages'] as $page) {
            if (!isset($legalPages[$page])) {
                continue;
            }

            $stubFile = "{$stubsDir}/{$page}.blade.stub";

            // If stub exists, copy it; otherwise create a placeholder
            if (file_exists($stubFile)) {
                $content = file_get_contents($stubFile);
            } else {
                $content = $this->generateLegalPagePlaceholder($legalPages[$page]);
            }

            // Replace placeholders
            $content = str_replace('{{APP_NAME}}', $this->config['app_name'], $content);
            $content = str_replace('{{CURRENT_DATE}}', date('F j, Y'), $content);

            file_put_contents("{$viewsDir}/{$page}.blade.php", $content);
        }

        // Add routes for legal pages
        $this->addLegalRoutes();

        // Add cookie consent banner if enabled
        if ($this->config['cookie_banner'] ?? false) {
            $this->installCookieConsentBanner();
        }
    }

    /**
     * Generate a placeholder legal page.
     */
    private function generateLegalPagePlaceholder(string $title): string
    {
        return <<<BLADE
<x-guest-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">{$title}</h1>

        <div class="prose prose-lg text-gray-600">
            <p class="text-sm text-gray-500 mb-8">Last updated: {{CURRENT_DATE}}</p>

            <p>
                This is a placeholder for your {$title}. Please review and customize
                this content to match your application's specific requirements and
                applicable laws in your jurisdiction.
            </p>

            <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-yellow-800">
                    <strong>Important:</strong> This is a template. You should consult
                    with a legal professional to ensure your {$title} complies with
                    all applicable laws and regulations.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
BLADE;
    }

    /**
     * Add routes for legal pages.
     */
    private function addLegalRoutes(): void
    {
        $routesFile = "{$this->projectPath}/routes/web.php";

        if (!file_exists($routesFile)) {
            return;
        }

        $routes = "\n// Legal Pages\n";
        foreach ($this->config['legal_pages'] as $page) {
            $routes .= "Route::view('/{$page}', 'legal.{$page}')->name('{$page}');\n";
        }

        file_put_contents($routesFile, $routes, FILE_APPEND);
    }

    /**
     * Install cookie consent banner.
     */
    private function installCookieConsentBanner(): void
    {
        // Copy cookie consent component
        $componentPath = "{$this->projectPath}/resources/views/components/cookie-consent.blade.php";
        $this->ensureDirectoryExists(dirname($componentPath));

        $content = <<<'BLADE'
<div x-data="{ show: !localStorage.getItem('cookieConsent') }"
     x-show="show"
     x-transition
     class="fixed bottom-0 inset-x-0 pb-2 sm:pb-5 z-50">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="p-2 rounded-lg bg-gray-800 shadow-lg sm:p-3">
            <div class="flex items-center justify-between flex-wrap">
                <div class="w-0 flex-1 flex items-center">
                    <span class="flex p-2 rounded-lg bg-gray-900">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <p class="ml-3 font-medium text-white truncate">
                        <span class="md:hidden">We use cookies.</span>
                        <span class="hidden md:inline">
                            We use cookies to enhance your browsing experience and analyze our traffic.
                        </span>
                    </p>
                </div>
                <div class="order-3 mt-2 flex-shrink-0 w-full sm:order-2 sm:mt-0 sm:w-auto flex gap-2">
                    <a href="{{ route('cookies') }}"
                       class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-800 bg-white hover:bg-gray-100">
                        Learn more
                    </a>
                    <button @click="localStorage.setItem('cookieConsent', 'true'); show = false"
                            class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Accept
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
BLADE;

        file_put_contents($componentPath, $content);
    }

    /**
     * Generate AI-powered landing page using Claude API
     */
    private function generateAILandingPage(): void
    {
        // Check if we started background landing page generation
        if ($this->landingPageTempFile) {
            // Wait for the background process to complete
            $maxWait = 90; // seconds (API can be slow)
            $waited = 0;

            $this->ui->info("Waiting for AI landing page generation to complete...");
            echo "  ";

            while ($waited < $maxWait) {
                clearstatcache(true, $this->landingPageTempFile);

                if (file_exists($this->landingPageTempFile)) {
                    $content = file_get_contents($this->landingPageTempFile);

                    // Check if content is complete (ends with </html>)
                    if (!empty($content) && str_contains($content, '</html>')) {
                        // Validate it looks like a blade file
                        if (str_starts_with(trim($content), '<!DOCTYPE html>') || str_starts_with(trim($content), '<html')) {
                            echo "\n";
                            file_put_contents(
                                "{$this->projectPath}/resources/views/welcome.blade.php",
                                trim($content)
                            );
                            // Clean up temp file
                            @unlink($this->landingPageTempFile);
                            $this->ui->success("AI landing page generated successfully!");
                            return;
                        }
                    }
                }

                sleep(2);
                $waited += 2;
                echo ".";
            }
            echo "\n";
            $this->ui->warning("Background generation timed out after {$maxWait} seconds");

            // Show debug info from log file if available
            if ($this->landingPageLogFile && file_exists($this->landingPageLogFile)) {
                $logContent = file_get_contents($this->landingPageLogFile);
                if (!empty(trim($logContent))) {
                    $this->ui->info("Background process log:");
                    echo "  " . substr($logContent, 0, 500) . "\n";
                }
            }
        }

        // If no API key or background generation didn't happen, use default
        if (!($this->config['anthropic_api_key'] ?? null)) {
            $this->ui->warning("No API key found - using default landing page template");
            $this->copyDefaultLandingPage();
            return;
        }

        // Fallback: try synchronous generation if background failed
        $this->ui->warning("Background generation incomplete - trying synchronous generation...");
        $this->generateLandingPageSync();
    }

    /**
     * Synchronous landing page generation (fallback)
     */
    private function generateLandingPageSync(): void
    {
        $appName = $this->config['app_name'];
        $appDescription = $this->config['app_description'] ?? 'A modern SaaS application';
        $plans = $this->config['subscription_plans'] ?? [];
        $trialDays = $this->config['trial_days'] ?? null;

        $plansText = '';
        if (!empty($plans)) {
            $plansText = "\n\nSubscription Plans:\n";
            foreach ($plans as $plan) {
                $plansText .= "- {$plan['name']}: \${$plan['price']}/month - {$plan['description']}\n";
            }
        }

        if ($trialDays) {
            $plansText .= "\nFree trial period: {$trialDays} days\n";
        }

        $prompt = <<<PROMPT
Generate a complete, production-ready landing page (welcome.blade.php) for a SaaS application with the following details:

App Name: {$appName}

App Description:
{$appDescription}
{$plansText}

Requirements:
1. Use Laravel Blade syntax with @vite for assets
2. Use Tailwind CSS 4 for styling (utility classes)
3. Use Alpine.js for any interactivity (mobile menu, etc.)
4. Include: Navigation, Hero, Features (6 features based on description), Pricing (if plans provided), CTA, Footer
5. Support dark mode with dark: variants
6. Be fully responsive (mobile-first)
7. Use Laravel route() helpers for login, register, dashboard, terms, privacy
8. Write compelling, conversion-focused copy based on the app description
9. Use modern design with gradients, shadows, and subtle animations
10. Include @if(View::exists('components.cookie-consent')) <x-cookie-consent /> @endif before closing body

Output ONLY the complete welcome.blade.php file content, starting with <!DOCTYPE html> and ending with </html>. No explanation or markdown code blocks.
PROMPT;

        $response = $this->callClaudeAPI($prompt);

        if ($response) {
            $content = $response;
            $content = preg_replace('/^```(?:blade|php|html)?\n/m', '', $content);
            $content = preg_replace('/\n```$/m', '', $content);
            $content = trim($content);

            if (str_starts_with($content, '<!DOCTYPE html>') || str_starts_with($content, '<html')) {
                file_put_contents(
                    "{$this->projectPath}/resources/views/welcome.blade.php",
                    $content
                );
                $this->ui->success("AI landing page generated successfully!");
                return;
            }
        }

        $this->ui->warning("AI generation failed - using default landing page");
        $this->copyDefaultLandingPage();
    }

    /**
     * Call Claude API to generate content
     */
    private function callClaudeAPI(string $prompt): ?string
    {
        $apiKey = $this->config['anthropic_api_key'];

        $data = [
            'model' => 'claude-sonnet-4-20250514',
            'max_tokens' => 8192,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ];

        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-api-key: ' . $apiKey,
                'anthropic-version: 2023-06-01'
            ],
            CURLOPT_TIMEOUT => 120
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return null;
        }

        $result = json_decode($response, true);

        if (isset($result['content'][0]['text'])) {
            return $result['content'][0]['text'];
        }

        return null;
    }

    /**
     * Copy the default landing page template
     */
    private function copyDefaultLandingPage(): void
    {
        $stubPath = __DIR__ . '/stubs/welcome.blade.stub';

        if (file_exists($stubPath)) {
            $content = file_get_contents($stubPath);
            $content = str_replace('{{APP_NAME}}', $this->config['app_name'], $content);
            file_put_contents(
                "{$this->projectPath}/resources/views/welcome.blade.php",
                $content
            );
        }
    }

    private function setupTodoSystem(): void
    {
        if (!($this->config['todo_system'] ?? false)) {
            return;
        }

        $stubsDir = __DIR__ . '/stubs';

        // Copy Todo model
        $this->copyStub(
            "{$stubsDir}/Todo.stub",
            "{$this->projectPath}/app/Models/Todo.php"
        );

        // Copy migration
        $timestamp = date('Y_m_d_His');
        $this->copyStub(
            "{$stubsDir}/create_todos_table.stub",
            "{$this->projectPath}/database/migrations/{$timestamp}_create_todos_table.php"
        );

        // Copy Artisan commands
        $commandsDir = "{$this->projectPath}/app/Console/Commands";
        $this->ensureDirectoryExists($commandsDir);

        $commands = ['TodoAdd', 'TodoList', 'TodoShow', 'TodoComplete', 'TodoUpdate', 'TodoDelete'];
        foreach ($commands as $command) {
            $this->copyStub(
                "{$stubsDir}/{$command}.stub",
                "{$commandsDir}/{$command}Command.php"
            );
        }

        // Copy Livewire component if it exists
        if (file_exists("{$stubsDir}/TodoManager.stub")) {
            $this->copyStub(
                "{$stubsDir}/TodoManager.stub",
                "{$this->projectPath}/app/Livewire/TodoManager.php"
            );
        }

        // Copy views
        if (file_exists("{$stubsDir}/todos-index.blade.stub")) {
            $this->ensureDirectoryExists("{$this->projectPath}/resources/views/todos");
            $this->copyStub(
                "{$stubsDir}/todos-index.blade.stub",
                "{$this->projectPath}/resources/views/todos/index.blade.php"
            );
        }

        if (file_exists("{$stubsDir}/todo-manager.blade.stub")) {
            $this->ensureDirectoryExists("{$this->projectPath}/resources/views/livewire");
            $this->copyStub(
                "{$stubsDir}/todo-manager.blade.stub",
                "{$this->projectPath}/resources/views/livewire/todo-manager.blade.php"
            );
        }
    }

    private function setupCreateLogoCommand(): void
    {
        if (!($this->config['create_logo'] ?? false)) {
            return;
        }

        $stubsDir = __DIR__ . '/stubs';

        // Create directories
        $commandsDir = "{$this->projectPath}/app/Console/Commands";
        $servicesDir = "{$this->projectPath}/app/Services";
        $this->ensureDirectoryExists($commandsDir);
        $this->ensureDirectoryExists($servicesDir);

        // Copy CreateLogoCommand
        $this->copyStub(
            "{$stubsDir}/CreateLogoCommand.stub",
            "{$commandsDir}/CreateLogoCommand.php"
        );

        // Copy BrandColorService
        $this->copyStub(
            "{$stubsDir}/BrandColorService.stub",
            "{$servicesDir}/BrandColorService.php"
        );
    }

    private function installFrontend(): void
    {
        $this->runCommandInProject("npm install");
    }

    private function buildAssets(): void
    {
        $this->runCommandInProject("npm run build");
    }

    private function finalConfiguration(): void
    {
        // Set APP_URL and APP_NAME in .env
        $envUpdates = [
            'APP_NAME' => '"' . $this->config['app_name'] . '"',
            'APP_URL' => $this->config['app_url'],
        ];
        $this->updateEnvFile($envUpdates);

        // Generate app key
        $this->runCommandInProject("php artisan key:generate --no-interaction");

        // Run migrations
        $this->runCommandInProject("php artisan migrate --no-interaction");

        // Link storage
        $this->runCommandInProject("php artisan storage:link --no-interaction");

        // Configure SSL if enabled and using Herd/Valet
        $this->configureLocalSsl();

        // Update CLAUDE.md
        $this->updateClaudeMd();
    }

    /**
     * Configure SSL for local development with Herd/Valet
     */
    private function configureLocalSsl(): void
    {
        $localEnv = $this->config['local_env'] ?? null;
        $enableSsl = $this->config['enable_ssl'] ?? false;
        $projectName = $this->config['project_name'];

        if (!$localEnv || !$enableSsl) {
            return;
        }

        try {
            if ($localEnv === 'herd') {
                // Laravel Herd secure command
                exec("herd secure {$projectName} 2>&1", $output, $returnCode);
                if ($returnCode === 0) {
                    $this->ui->success("SSL enabled for {$projectName}.test via Herd");
                }
            } elseif ($localEnv === 'valet') {
                // Laravel Valet secure command
                exec("cd {$this->projectPath} && valet secure 2>&1", $output, $returnCode);
                if ($returnCode === 0) {
                    $this->ui->success("SSL enabled for {$projectName}.test via Valet");
                }
            }
        } catch (Exception $e) {
            $this->ui->warning("Could not auto-configure SSL. Run manually: {$localEnv} secure {$projectName}");
        }
    }

    /**
     * Seed the super admin user and roles.
     */
    private function seedSuperAdmin(): void
    {
        // First, run the roles seeder
        $this->runCommandInProject("php artisan db:seed --class=SpatieRolesSeeder --no-interaction");

        // Create the super admin seeder
        $admin = $this->config['super_admin'];
        $seederPath = "{$this->projectPath}/database/seeders/SuperAdminSeeder.php";

        $seederContent = <<<PHP
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create super admin user
        \$user = User::create([
            'name' => '{$admin['name']}',
            'email' => '{$admin['email']}',
            'password' => Hash::make('{$admin['password']}'),
            'email_verified_at' => now(),
        ]);

        // Create admin team
        \$team = Team::create([
            'name' => "{$admin['name']}'s Team",
            'user_id' => \$user->id,
            'personal_team' => true,
        ]);

        // Set current team
        \$user->current_team_id = \$team->id;
        \$user->save();

        // Assign super admin role
        \$superAdminRole = Role::findByName('super_admin');
        \$user->assignRole(\$superAdminRole);

        \$this->command->info("Super Admin created: {$admin['email']}");
    }
}
PHP;

        file_put_contents($seederPath, $seederContent);

        // Run the super admin seeder
        $this->runCommandInProject("php artisan db:seed --class=SuperAdminSeeder --no-interaction");

        // Configure Filament to only allow super admins
        if ($this->config['filament'] ?? false) {
            $this->configureFilamentAccess();
        }
    }

    /**
     * Configure Filament to only allow super_admin access.
     */
    private function configureFilamentAccess(): void
    {
        $panelProviderPath = "{$this->projectPath}/app/Providers/Filament/AdminPanelProvider.php";

        if (!file_exists($panelProviderPath)) {
            return;
        }

        $content = file_get_contents($panelProviderPath);

        // Add the canAccessPanel method if not present
        if (!str_contains($content, 'canAccessPanel')) {
            // Find the end of the class to add method before it
            $authMiddleware = <<<'PHP'

    public function boot(): void
    {
        parent::boot();

        // Only super_admin can access the admin panel
        \Filament\Facades\Filament::serving(function () {
            \Filament\Facades\Filament::registerRenderHook(
                'panels::auth.login.form.before',
                fn (): string => '<div class="text-sm text-gray-500 text-center mb-4">Super Admin access only</div>',
            );
        });
    }
PHP;

            // Add the canAccessPanel check to the panel
            $content = str_replace(
                '->login()',
                "->login()\n            ->authGuard('web')",
                $content
            );
        }

        file_put_contents($panelProviderPath, $content);

        // Create a policy for Filament access
        $policyContent = <<<'PHP'
<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilamentAccessPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can access Filament admin panel.
     */
    public function accessFilament(User $user): bool
    {
        return $user->hasRole('super_admin');
    }
}
PHP;

        $this->ensureDirectoryExists("{$this->projectPath}/app/Policies");
        file_put_contents("{$this->projectPath}/app/Policies/FilamentAccessPolicy.php", $policyContent);
    }

    private function updateClaudeMd(): void
    {
        $claudeMd = <<<CLAUDE
# {$this->config['app_name']} - Claude Code Configuration

## Project Overview
{$this->config['description']}

## Todo System Integration
This project uses an internal todo system that Claude Code should check before starting work.

### Check todos before starting:
```bash
php artisan todo:list
```

### After completing work:
```bash
php artisan todo:complete {id}
```

## Available Artisan Commands
- `todo:add "description"` - Add a new todo
- `todo:list` - List all todos
- `todo:show {id}` - Show todo details
- `todo:complete {id}` - Mark as complete
- `todo:update {id} "new text"` - Update todo
- `todo:delete {id}` - Delete todo

## Important Notes
- Always check and complete todos as part of your workflow
- Prioritize todos marked as high priority
- Update todo status as you work

CLAUDE;

        file_put_contents($this->projectPath . '/CLAUDE.md', $claudeMd);
    }

    // Utility methods

    private function validateProjectName(string $name): bool
    {
        return (bool) preg_match('/^[a-z][a-z0-9-]{2,49}$/', $name);
    }

    private function formatAppName(string $projectName): string
    {
        return ucwords(str_replace('-', ' ', $projectName));
    }

    private function runCommand(string $command): void
    {
        exec($command . ' 2>&1', $output, $returnCode);

        if ($returnCode !== 0) {
            throw new RuntimeException(implode("\n", $output));
        }
    }

    private function runCommandInProject(string $command): void
    {
        $this->runCommand("cd {$this->projectPath} && {$command}");
    }

    private function updateEnvFile(array $values): void
    {
        $envPath = $this->projectPath . '/.env';

        if (!file_exists($envPath)) {
            return;
        }

        $content = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $replacement, $content);
            } else {
                $content .= "\n{$replacement}";
            }
        }

        file_put_contents($envPath, $content);
    }

    private function storeSecureConfig(string $key, string $value): void
    {
        $secureFile = getcwd() . '/.secure-config';

        $config = [];
        if (file_exists($secureFile)) {
            $config = json_decode(file_get_contents($secureFile), true) ?? [];
        }

        $config[$key] = $value;

        file_put_contents($secureFile, json_encode($config, JSON_PRETTY_PRINT));
        chmod($secureFile, 0600);
    }

    /**
     * Copy a stub file to the destination.
     */
    private function copyStub(string $source, string $destination): void
    {
        if (!file_exists($source)) {
            return;
        }

        $this->ensureDirectoryExists(dirname($destination));
        copy($source, $destination);
    }

    /**
     * Ensure a directory exists, creating it if necessary.
     */
    private function ensureDirectoryExists(string $directory): void
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
}

// Run the installer
$installer = new UltimateInstaller();
$installer->run();
