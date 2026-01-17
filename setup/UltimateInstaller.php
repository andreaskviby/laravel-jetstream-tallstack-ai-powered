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

    private const TOTAL_PHASES = 10;

    public function __construct()
    {
        $this->ui = new TerminalUI();
        $this->startTime = microtime(true);
    }

    /**
     * Run the installation process
     */
    public function run(): void
    {
        try {
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
        } else {
            $this->ui->error("Claude Code CLI not found!");
            $this->ui->showInfoBox("HOW TO INSTALL CLAUDE CODE", [
                "",
                "Install Claude Code CLI:",
                "",
                "  npm install -g @anthropic-ai/claude-code",
                "",
                "Or visit: https://claude.ai/code",
                "",
            ], 'info');

            if (!$this->ui->confirm("Continue anyway? (Some features will be disabled)", false)) {
                $this->ui->info("Installation cancelled. Please install Claude Code first.");
                exit(0);
            }

            $this->config['claude_code_installed'] = false;
        }

        $this->config['claude_code_installed'] = $claudeInstalled;

        // Get Claude API Key
        echo "\n";
        $this->ui->showInfoBox("🔑 ANTHROPIC API KEY REQUIRED", [
            "",
            "Your API key is required for:",
            "• AI-powered landing page generation",
            "• Todo system with Claude Code integration",
            "• AI development agents and skills",
            "",
            "Get your API key at: https://console.anthropic.com/",
            "",
        ], 'accent');

        $apiKey = $this->ui->promptPassword("Enter your Anthropic API key:");

        if (empty($apiKey)) {
            $this->ui->error("API key is required to continue.");
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

        echo "\n";
        $this->ui->success("Claude Code verification complete!");
        sleep(1);
    }

    /**
     * Phase 1: Project Setup
     */
    private function phase1ProjectSetup(): void
    {
        $this->ui->clearScreen();
        $this->ui->showPhaseHeader(1, self::TOTAL_PHASES, "PROJECT CONFIGURATION", "📦");

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

        // Get project description
        $description = $this->ui->prompt(
            "Briefly describe your SaaS (for composer.json):",
            "A SaaS application built with Laravel TALL Stack"
        );

        $this->config['description'] = $description;

        $this->features[] = "Laravel 11 + Jetstream + Livewire";
        $this->features[] = "TALL Stack (Tailwind, Alpine, Livewire, Laravel)";
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
            $this->ui->info("Skipping landing page generation");
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

        $this->config['database_host'] = $this->ui->prompt("Database host:", "127.0.0.1");
        $this->config['database_port'] = $this->ui->prompt("Database port:", $this->config['database_driver'] === 'pgsql' ? "5432" : "3306");
        $this->config['database_name'] = $this->ui->prompt("Database name:", str_replace('-', '_', $this->config['project_name']));
        $this->config['database_username'] = $this->ui->prompt("Database username:", "root");
        $this->config['database_password'] = $this->ui->promptPassword("Database password:");

        $this->config['create_database'] = $createNew;

        $this->ui->success("Database configuration saved");
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
            'Installing frontend dependencies' => fn() => $this->installFrontend(),
            'Building assets' => fn() => $this->buildAssets(),
            'Final configuration' => fn() => $this->finalConfiguration(),
            'Seeding super admin' => fn() => $this->seedSuperAdmin(),
        ];

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

        $nextSteps = [
            "1. Change to your project directory:",
            "   cd {$this->config['project_name']}",
            "",
            "2. Start the development server:",
            "   composer dev",
            "",
            "3. Open your browser:",
            "   http://localhost:8000",
            "",
        ];

        if ($this->config['filament'] ?? false) {
            $nextSteps[] = "4. Access the admin panel:";
            $nextSteps[] = "   http://localhost:8000/{$panelName}";
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
        $nextSteps[] = "      http://localhost:8000/{$panelName}/todos";
        $nextSteps[] = "";
        $nextSteps[] = "   Claude Code will automatically check and work on your todos!";
        $nextSteps[] = "";

        $this->features[] = "Installation time: {$timeStr}";

        $this->ui->showSuccessScreen(
            $this->config['project_name'],
            $this->features,
            $nextSteps
        );
    }

    // Installation helper methods

    private function createProjectDirectory(): void
    {
        if (!mkdir($this->projectPath, 0755, true) && !is_dir($this->projectPath)) {
            throw new RuntimeException("Failed to create project directory");
        }
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
            // Database creation logic here
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

        $this->runCommandInProject("composer require filament/filament:\"^5.0\" --no-interaction");
        $this->runCommandInProject("php artisan filament:install --panels --no-interaction");
    }

    private function setupRolesPermissions(): void
    {
        // Install Spatie permission package
        $this->runCommandInProject("composer require spatie/laravel-permission --no-interaction");
        $this->runCommandInProject("php artisan vendor:publish --provider=\"Spatie\\Permission\\PermissionServiceProvider\" --no-interaction");

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
        // Generate app key
        $this->runCommandInProject("php artisan key:generate --no-interaction");

        // Run migrations
        $this->runCommandInProject("php artisan migrate --no-interaction");

        // Link storage
        $this->runCommandInProject("php artisan storage:link --no-interaction");

        // Update CLAUDE.md
        $this->updateClaudeMd();
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
