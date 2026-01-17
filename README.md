# Laravel TALL Stack AI-Powered Starter Kit

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Livewire-4.0-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire 4">
  <img src="https://img.shields.io/badge/Tailwind-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS 4">
  <img src="https://img.shields.io/badge/Alpine.js-3.15-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js 3.15">
  <img src="https://img.shields.io/badge/Filament-5.0-FDAE4B?style=for-the-badge&logo=laravel&logoColor=white" alt="Filament 5">
  <img src="https://img.shields.io/badge/Claude_Code-Powered-7C3AED?style=for-the-badge&logo=anthropic&logoColor=white" alt="Claude Code">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License">
</p>

<p align="center">
  <strong>The fastest way to launch your SaaS with AI-powered development</strong>
</p>

---

## Why This Starter Kit?

| Traditional Approach | With This Starter Kit |
|---------------------|----------------------|
| Days setting up auth, teams, payments | **Ready in minutes** |
| Manual coding for every feature | **AI builds features for you** |
| Separate admin panel setup | **Filament 5 pre-configured** |
| Complex role management | **Spatie + Jetstream integrated** |
| No deployment automation | **One-command deploy to Forge** |

---

## 🚀 Quick Start (2 Commands)

```bash
# 1. Create your project
composer create-project andreaskviby/laravel-jetstream-tallstack-ai-powered my-saas

# 2. Start building with AI
cd my-saas && claude
```

That's it. The beautiful installer guides you through everything, and Claude Code starts building your app.

---

## ✨ Unique Selling Points

### 🤖 Claude Code-First Development
```bash
# Add a todo and let Claude Code build it
php artisan todo:add "Build user dashboard with activity stats"

# Start Claude Code - it automatically picks up your todos
claude
```
**You describe what you want. Claude Code builds it.**

### ⚡ 10-Phase Interactive Installer
Beautiful terminal UI with progress bars, ASCII art, and guided setup for:
- Database (MySQL/PostgreSQL/SQLite)
- Authentication (OTP/Social/Password)
- Payments (Stripe/Lemon Squeezy/PayPal)
- Admin Panel (Filament 5)
- Infrastructure (CloudFlare/Mailgun/Forge)

### 🔐 Passwordless by Default
Modern OTP authentication - no passwords to remember or reset.
```
Local development: Use code 123456
Production: Secure email-based codes
```

### 👥 Enterprise-Ready Teams
Multi-tenant from day one with:
- Team branding (logos, colors)
- Role-based permissions (Spatie)
- Member invitations
- Team switching

---

## 📦 Complete Feature List

### Core Stack
| Feature | Description |
|---------|-------------|
| Laravel 12 | Latest Laravel framework (Feb 2025) |
| Jetstream 5 | Authentication scaffolding with teams |
| Livewire 4 | Full-stack components with islands & single-file |
| Tailwind CSS 4 | Next-gen utility-first styling (5x faster) |
| Alpine.js 3.15 | Lightweight reactive JavaScript |

### Authentication & Security
| Feature | Description |
|---------|-------------|
| OTP Authentication | Passwordless email codes |
| Social Login | Google, GitHub, Facebook, Apple, LinkedIn |
| Traditional Auth | Email/password option available |
| Two-Factor Auth | Built-in 2FA support |
| Team Invitations | Secure invitation system |

### Admin & Management
| Feature | Description |
|---------|-------------|
| Filament 5 Admin | Beautiful admin panel with Livewire 4 |
| User Management | CRUD for users |
| Team Management | CRUD for teams |
| Role Management | Spatie permissions UI |
| Dashboard Widgets | ARR, MRR, Users, Signups |

### Roles & Permissions
| Feature | Description |
|---------|-------------|
| Spatie Integration | Industry-standard permissions |
| Team-Scoped Roles | Different roles per team |
| System Roles | Super Admin, Team Admin, Member |
| Custom Roles | Define your own during install |
| Blade Directives | @teamRole, @superAdmin, etc. |

### Payment Providers
| Feature | Description |
|---------|-------------|
| Lemon Squeezy | Merchant of Record (recommended) |
| Stripe | Via Laravel Cashier |
| PayPal | Standard integration |
| Subscription Plans | Configure during install |
| Trial Periods | Configurable trial days |

### Infrastructure Automation
| Feature | Description |
|---------|-------------|
| CloudFlare | DNS & CDN management |
| Mailgun | Email delivery |
| Laravel Forge | Server deployment |
| One-Command Deploy | `php artisan deploy` |
| DNS Setup | `php artisan setup:dns` |

### AI Integration
| Feature | Description |
|---------|-------------|
| Claude Code Ready | Todo system integration |
| Landing Page Generator | AI creates pages from descriptions |
| Development Agents | Pre-built Claude Code agents |
| Skill Modules | OTP, Teams, Security patterns |

### Legal & Compliance
| Feature | Description |
|---------|-------------|
| Terms of Service | Generated template |
| Privacy Policy | GDPR-ready template |
| Cookie Policy | With consent banner |
| GDPR Compliance | EU regulation ready |

### Team Branding
| Feature | Description |
|---------|-------------|
| Custom Logos | Upload team logos |
| Brand Colors | Primary & secondary colors |
| Color Picker | Easy hex code selection |
| Live Preview | See changes instantly |

---

## 🛠️ Installation

### Option 1: Composer (Recommended)

```bash
composer create-project andreaskviby/laravel-jetstream-tallstack-ai-powered my-project
cd my-project
```

### Option 2: Curl Install

```bash
curl -s https://raw.githubusercontent.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/main/install.sh | bash -s -- my-project
```

### What the Installer Asks

The beautiful 10-phase installer will guide you through:

| Phase | What You'll Configure |
|-------|----------------------|
| 0 | Claude Code API key (required) |
| 1 | Project name and description |
| 2 | AI landing page & subscription plans |
| 3 | Database (MySQL/PostgreSQL/SQLite) |
| 4 | Authentication (OTP/Social/Password) |
| 5 | Payment provider (Stripe/Lemon Squeezy/PayPal) |
| 6 | Filament admin panel & widgets |
| 7 | Roles & permissions |
| 8 | Infrastructure (CloudFlare/Mailgun/Forge) |
| 9 | Legal pages & super admin account |

---

## 🎯 After Installation

### Start Development Server

```bash
composer dev
```

This starts Laravel, queue worker, Vite, and log viewer concurrently.

### Access Your App

| URL | Description |
|-----|-------------|
| http://localhost:8000 | Main application |
| http://localhost:8000/admin | Filament admin panel |
| Use OTP code: `123456` | For local development |

### Start Building with Claude Code

```bash
# Add your first feature request
php artisan todo:add "Create a pricing page with 3 tiers"

# Start Claude Code
claude

# Claude Code automatically:
# 1. Reads your todos
# 2. Picks up the task
# 3. Builds the feature
# 4. Marks it complete
```

---

## 📋 Todo Commands

Manage your development tasks that Claude Code will work on:

```bash
# Add a new todo
php artisan todo:add "Build user dashboard"

# List all todos
php artisan todo:list

# Show todo details
php artisan todo:show 1

# Mark as complete
php artisan todo:complete 1

# Update todo
php artisan todo:update 1 "Updated description"

# Delete todo
php artisan todo:delete 1
```

---

## 🚀 Deployment Commands

### Deploy to Laravel Forge

```bash
# Deploy with cache purge
php artisan deploy --site=12345 --purge-cache
```

### Setup DNS on CloudFlare

```bash
# Configure domain with mail records
php artisan setup:dns example.com --ip=1.2.3.4 --mail --verify
```

---

## 📁 Project Structure

```
├── app/
│   ├── Filament/              # Admin panel resources
│   ├── Livewire/              # Livewire components
│   ├── Models/                # Eloquent models
│   ├── Policies/              # Authorization policies
│   ├── Services/
│   │   └── Infrastructure/    # CloudFlare, Mailgun, Forge
│   └── Traits/
│       └── HasTeamRoles.php   # Spatie + Jetstream integration
├── config/
│   └── spatie-jetstream.php   # Roles configuration
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/
│       ├── SpatieRolesSeeder.php
│       └── SuperAdminSeeder.php
├── resources/views/
│   ├── legal/                 # Terms, Privacy, GDPR, Cookies
│   └── components/
│       └── cookie-consent.blade.php
├── setup/
│   ├── UltimateInstaller.php  # Main installer
│   ├── lib/
│   │   └── TerminalUI.php     # Beautiful terminal interface
│   └── stubs/
│       ├── spatie/            # Roles & permissions stubs
│       ├── infrastructure/    # CloudFlare, Mailgun, Forge stubs
│       └── legal/             # Legal page templates
└── CLAUDE.md                  # Claude Code instructions
```

---

## ⚙️ Configuration

### Environment Variables

```env
# Authentication
OTP_ENABLED=true
OTP_LENGTH=6
OTP_EXPIRES_IN=10
OTP_PREFILL_LOCAL=true
OTP_DEFAULT_CODE=123456

# AI Integration
ANTHROPIC_API_KEY=your-api-key

# Infrastructure (optional)
CLOUDFLARE_API_TOKEN=your-token
CLOUDFLARE_ZONE_ID=your-zone-id
MAILGUN_SECRET=your-secret
MAILGUN_DOMAIN=mg.yourdomain.com
FORGE_API_TOKEN=your-token
FORGE_SERVER_ID=your-server-id
```

---

## 🔒 Super Admin Access

The Filament admin panel is restricted to `super_admin` role only:

1. Super admin is created during installation
2. Only super admins can access `/admin`
3. Manage roles in admin panel under User Management

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific tests
php artisan test --filter TeamBrandingTest

# Code style
./vendor/bin/pint
```

---

## 📚 Documentation

| Document | Description |
|----------|-------------|
| [CLAUDE.md](CLAUDE.md) | Claude Code integration guide |
| [docs/SPATIE_ROLES.md](docs/SPATIE_ROLES.md) | Roles & permissions documentation |
| [.github/agents/README.md](.github/agents/README.md) | Claude Code agents |

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 💖 Credits

Built with:
- [Laravel](https://laravel.com) - The PHP Framework
- [Jetstream](https://jetstream.laravel.com) - Authentication Scaffolding
- [Livewire](https://livewire.laravel.com) - Full-Stack Components
- [Tailwind CSS](https://tailwindcss.com) - Utility-First CSS
- [Alpine.js](https://alpinejs.dev) - Lightweight JavaScript
- [Filament](https://filamentphp.com) - Admin Panel
- [Spatie Permissions](https://spatie.be/docs/laravel-permission) - Roles & Permissions
- [Claude Code](https://claude.ai/code) - AI-Powered Development

---

<p align="center">
  <strong>Built for TALL stack developers who want to ship faster</strong>
</p>

<p align="center">
  <a href="https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered">⭐ Star on GitHub</a>
  ·
  <a href="https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/issues">Report Bug</a>
  ·
  <a href="https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/issues">Request Feature</a>
</p>
