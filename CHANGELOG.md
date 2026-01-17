# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-01-17

### Added
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

### Technical Details
- Laravel 11.x with Jetstream 5.x
- Livewire 3.x for reactive components
- Tailwind CSS for styling
- PHPUnit tests for feature validation

### Files Added
- `app/Models/Team.php` - Enhanced Team model with branding attributes
- `app/Http/Livewire/Teams/UpdateTeamBrandingForm.php` - Livewire component for branding management
- `database/migrations/2026_01_17_000001_add_branding_to_teams_table.php` - Database migration
- `resources/views/livewire/teams/update-team-branding-form.blade.php` - Branding form UI
- `resources/views/teams/show.blade.php` - Team settings page with branding section
- `tests/Feature/UpdateTeamBrandingTest.php` - Comprehensive test coverage

### Configuration
- Added Jetstream configuration in `config/jetstream.php`
- Configured storage for team logos
- Set up Tailwind CSS and Vite for asset building
