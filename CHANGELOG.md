# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-01-17

### Added
- Initial release of Laravel Jetstream TALL Stack AI-Powered starter kit
- Interactive installation script with curl support
- Automated Laravel Jetstream installation with Livewire and Teams
- Database configuration wizard (MySQL and SQLite support)
- OTP authentication system instead of password-based authentication
- Prefilled OTP codes for local development environment
- Claude AI API integration setup
- Laravel Herd mail configuration support
- Enhanced team invitation system with beautiful email templates
- Comprehensive documentation (README, QUICKSTART, CONTRIBUTING)
- Example configuration files (.env.example)
- Reusable code stubs for OTP and team features
- Automatic upgrade to latest stable Laravel version
- Frontend asset building with npm
- MIT License
- .gitignore configuration

- **Team Branding Feature**: Complete team branding customization system
  - Custom team logo upload with image validation (max 1MB)
  - Logo management: upload, preview, and delete functionality
  - Primary brand color selection with color picker and hex input
  - Secondary brand color selection with color picker and hex input
  - Real-time validation for image files and hex color codes
  - Storage management for team logos in `storage/app/public/team-logos/`
  - `UpdateTeamBrandingForm` Livewire component for managing team branding
  - Database migration adding `logo_path`, `primary_color`, and `secondary_color` to teams table
  - Team model enhancements with logo URL accessor
  - Comprehensive test suite covering all branding functionality
  - User-friendly UI with color pickers and logo preview
  - Documentation in README.md and TEAM_BRANDING.md

### Features
- **Installation Methods**:
  - Curl-based installation
  - Composer-based installation
  - Interactive setup wizard

- **Authentication**:
  - OTP (One-Time Password) authentication
  - Email-based OTP delivery
  - Configurable OTP length and expiration
  - Local development prefill (code: 123456)
  - Secure OTP verification and storage

- **Database Support**:
  - MySQL with automatic database creation
  - SQLite with automatic file creation
  - Interactive credential setup
  - Connection testing

- **Team Management**:
  - Full Jetstream teams support
  - Enhanced invitation emails
  - Team member management
  - Role-based permissions
  - Team switching
  - Team branding (logo and colors)

- **AI Integration**:
  - Claude AI API configuration
  - Ready for AI-powered features
  - OAuth login placeholder (coming soon)

- **Developer Experience**:
  - Color-coded CLI output
  - Progress indicators
  - Error handling and recovery
  - Automatic environment detection
  - Pre-configured for Laravel Herd

### Technical Details
- Requires PHP 8.2+
- Laravel 11+ support
- Livewire 3.0+
- Tailwind CSS
- Alpine.js
- PSR-12 code style

### Documentation
- Comprehensive README with all features
- Quick start guide for rapid setup
- Contributing guidelines
- Environment configuration examples
- Troubleshooting section

## [Unreleased]

### Planned Features
- OAuth integration for Claude AI
- Additional OTP delivery methods (SMS, WhatsApp)
- More AI-powered feature examples
- Automated testing suite
- Docker support
- GitHub Actions CI/CD
- Additional database drivers (PostgreSQL)
- Multi-language support
- Custom artisan commands for common tasks
- Enhanced team analytics
- Role and permission management UI

### Planned Improvements
- Better error messages
- More configuration options
- Performance optimizations
- Additional code stubs
- Video tutorials
- Example applications

---

## Version Guidelines

### Major Version (x.0.0)
- Breaking changes
- Major new features
- Complete rewrites

### Minor Version (0.x.0)
- New features
- Non-breaking changes
- Enhancements

### Patch Version (0.0.x)
- Bug fixes
- Documentation updates
- Minor improvements

---

## Links
- [Repository](https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered)
- [Issues](https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/issues)
- [Pull Requests](https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/pulls)
