# Laravel Jetstream TALL Stack with AI-Powered Features

A Laravel application built with Jetstream using the TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire) featuring enhanced team management with custom branding capabilities.

## Features

### Team Branding
This application includes comprehensive team branding features that allow teams to customize their appearance:

- **Custom Team Logo**: Upload and manage custom logos for each team
  - Support for image uploads (JPG, PNG, GIF, SVG)
  - Maximum file size: 1MB
  - Automatic storage management with easy logo deletion

- **Brand Colors**: Set primary and secondary brand colors for your team
  - Primary Color: Main brand color for your team
  - Secondary Color: Complementary brand color
  - Hex color code validation (e.g., #FF5733)
  - Color picker UI for easy selection

### Technical Implementation

#### Database Schema
The team branding feature adds three columns to the `teams` table:
- `logo_path` (string, nullable): Stores the path to the uploaded team logo
- `primary_color` (string, nullable): Stores the team's primary brand color (hex code)
- `secondary_color` (string, nullable): Stores the team's secondary brand color (hex code)

#### Components
- **UpdateTeamBrandingForm**: Livewire component for managing team branding
  - Logo upload with file validation
  - Color selection with hex code validation
  - Real-time preview of logo changes
  - Logo deletion functionality

#### Models
- **Team Model**: Extended with branding attributes
  - `logo_path`, `primary_color`, `secondary_color` are mass assignable
  - `logo_url` accessor provides the full URL to the team logo

## Installation

1. Clone the repository
2. Install PHP dependencies: `composer install`
3. Install NPM dependencies: `npm install`
4. Copy `.env.example` to `.env` and configure your database
5. Generate application key: `php artisan key:generate`
6. Run migrations: `php artisan migrate`
7. Link storage: `php artisan storage:link`
8. Build assets: `npm run build`

## Usage

### Team Settings
Navigate to your team settings page to access the branding options. You can:

1. **Upload a Team Logo**:
   - Click "Select A New Logo"
   - Choose an image file (max 1MB)
   - Preview the logo before saving
   - Click "Save" to apply changes

2. **Set Brand Colors**:
   - Use the color picker or enter hex codes directly
   - Primary color: Main brand identity color
   - Secondary color: Complementary color
   - Click "Save" to apply changes

3. **Remove Logo**:
   - Click "Remove Logo" to delete the current team logo
   - Changes are saved immediately

## Testing

Run the test suite:
```bash
php artisan test
```

Or run specific feature tests:
```bash
php artisan test --filter UpdateTeamBrandingTest
```

## Requirements

- PHP 8.2 or higher
- Laravel 11.x
- Laravel Jetstream 5.x
- Livewire 3.x

## License

MIT License