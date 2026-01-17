# Laravel Jetstream TALL Stack AI-Powered Starter Kit

## Project Overview
This is the ultimate starter kit for TALL stack developers, providing a comprehensive foundation for building SaaS applications with AI-powered features and Claude Code integration.

## Key Features
- Laravel 12 with Jetstream 5 and Livewire 4
- TALL Stack (Tailwind 4, Alpine.js 3.15, Livewire 4, Laravel 12)
- Claude Code-first development workflow
- OTP (passwordless) authentication
- Team management with branding
- Spatie roles and permissions integrated with Jetstream
- Filament 5 admin panel with ARR/MRR dashboard widgets
- Infrastructure automation (CloudFlare, Mailgun, Laravel Forge)
- Legal pages with cookie consent banner

## Installation
The installer is located at `setup/UltimateInstaller.php` with a beautiful terminal UI provided by `setup/lib/TerminalUI.php`.

## Claude Code Integration

### Todo System
This project uses an internal todo system that Claude Code should check before starting work.

### Before Starting Work
```bash
php artisan todo:list
```

### After Completing Work
```bash
php artisan todo:complete {id}
```

### Available Todo Commands
- `php artisan todo:add "description"` - Add a new todo
- `php artisan todo:list` - List all todos
- `php artisan todo:show {id}` - Show todo details
- `php artisan todo:complete {id}` - Mark as complete
- `php artisan todo:update {id} "new text"` - Update todo
- `php artisan todo:delete {id}` - Delete todo

### Workflow
1. Check todos before starting any work session
2. Pick up the highest priority uncompleted todo
3. Work on it until completion
4. Mark the todo as complete
5. Move to the next todo

## Directory Structure

### Setup Files
- `setup/UltimateInstaller.php` - Main installer with 10 phases
- `setup/lib/TerminalUI.php` - Beautiful terminal interface library
- `setup/stubs/` - Template files for installation
  - `setup/stubs/spatie/` - Spatie roles integration stubs
  - `setup/stubs/infrastructure/` - Infrastructure automation stubs
  - `setup/stubs/legal/` - Legal page templates

### Key Stubs
- **Spatie Integration**: `HasTeamRoles.stub`, `SpatieRolesSeeder.stub`, `TeamRoleMiddleware.stub`
- **Infrastructure**: `CloudFlareService.stub`, `MailgunService.stub`, `ForgeService.stub`
- **Payment**: `StripeIntegration.stub`, `LemonSqueezyIntegration.stub`, `PayPalIntegration.stub`

## Development Guidelines

### Code Standards
- Follow Laravel conventions
- Use typed properties and return types
- Follow PSR-12 coding standards
- Use constructor property promotion when possible

### Testing
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

## Important Notes

### Database
- Always use MySQL (never SQLite for production)
- Never run `migrate:fresh` without explicit permission
- Always create migrations for database changes

### Git
- Never run git commands automatically without permission
- Use the GITTA command when explicitly requested for commits

### Security
- Never commit .env files or secrets
- Super admin is the only role that can access the Filament admin panel
- API keys are stored securely in gitignored files

## Super Admin Access
The Filament admin panel is restricted to users with the `super_admin` role only. This is configured during installation.

## AI Models
- Anthropic: Use `opus-4` (claude-opus-4-5-20251101)
- OpenAI: Use `gpt-5-mini` for general tasks

## Infrastructure Services
When configured during installation, the following services are available:
- **CloudFlare**: DNS and CDN management
- **Mailgun**: Email delivery
- **Laravel Forge**: Server and deployment management

Use artisan commands for quick actions:
```bash
php artisan setup:dns example.com --ip=1.2.3.4 --mail
php artisan deploy --site=12345 --purge-cache
```
