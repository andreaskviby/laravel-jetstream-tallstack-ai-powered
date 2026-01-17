# Quick Reference Guide - Team Branding

## Quick Start

### Setup
```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan storage:link

# Build assets
npm run build
```

### Usage in Code

#### Access Team Branding
```php
$team->logo_url          // Full URL to logo
$team->primary_color     // e.g., "#FF5733"
$team->secondary_color   // e.g., "#33C3FF"
```

#### Update Team Branding
```php
$team->update([
    'primary_color' => '#FF5733',
    'secondary_color' => '#33C3FF',
]);
```

#### Upload Logo
```php
$path = $request->file('logo')->store('team-logos', 'public');
$team->update(['logo_path' => $path]);
```

### Usage in Views

#### Display Team Logo
```blade
@if($team->logo_url)
    <img src="{{ $team->logo_url }}" alt="{{ $team->name }}">
@endif
```

#### Use Brand Colors
```blade
<div style="background-color: {{ $team->primary_color }}">
    <!-- Content -->
</div>
```

#### Include Branding Form
```blade
@livewire('teams.update-team-branding-form', ['team' => $team])
```

## Validation

### Logo
- ✅ Image files only
- ✅ Max 1MB
- ✅ Formats: JPG, PNG, GIF, SVG

### Colors
- ✅ Hex format: `#RGB` or `#RRGGBB`
- ✅ Examples: `#FFF`, `#FF5733`

## File Structure

```
app/
├── Http/Livewire/Teams/
│   └── UpdateTeamBrandingForm.php
└── Models/
    └── Team.php

database/migrations/
└── 2026_01_17_000001_add_branding_to_teams_table.php

resources/views/
├── livewire/teams/
│   └── update-team-branding-form.blade.php
└── teams/
    └── show.blade.php

storage/app/public/
└── team-logos/
    └── [uploaded logos]

tests/Feature/
└── UpdateTeamBrandingTest.php
```

## Common Tasks

### Change Logo
1. Go to Team Settings
2. Click "Select A New Logo"
3. Choose image file
4. Click "Save"

### Change Colors
1. Go to Team Settings
2. Use color picker or enter hex code
3. Click "Save"

### Remove Logo
1. Go to Team Settings
2. Click "Remove Logo"

## Testing

```bash
# Run all tests
php artisan test

# Run branding tests only
php artisan test --filter UpdateTeamBrandingTest

# Run specific test
php artisan test --filter test_team_branding_can_be_updated
```

## Troubleshooting

### Logo Not Showing
```bash
php artisan storage:link
```

### Upload Fails
Check:
- File size < 1MB
- File is image
- Storage writable
- PHP upload limits

### Color Not Saving
Check format:
- Must start with `#`
- Use hex characters: 0-9, A-F
- Valid: `#F00`, `#FF0000`
- Invalid: `red`, `rgb(255,0,0)`

## API Reference

### Team Model Methods
```php
$team->logo_url              // Accessor for logo URL
$team->primary_color         // Primary brand color
$team->secondary_color       // Secondary brand color
```

### Livewire Component Methods
```php
updateTeamBranding()    // Save branding changes
deleteLogo()            // Remove team logo
```

### Events Dispatched
```php
'saved'                      // After successful update
'refresh-navigation-menu'    // Trigger navigation refresh
```

## Configuration

### Storage (config/filesystems.php)
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

### Jetstream (config/jetstream.php)
```php
'stack' => 'livewire',
'features' => [
    // Features::teams(['invitations' => true]),
],
```

## Security Notes

✅ File validation prevents malicious uploads
✅ Size limits prevent DOS attacks
✅ Hex validation prevents injection
✅ Authorization via Jetstream
✅ Storage in controlled directory

## Performance Tips

💡 Cache logo URLs for better performance
💡 Optimize images before upload
💡 Use CDN for production
💡 Implement cleanup jobs for old files

## Additional Resources

- [README.md](README.md) - Full feature overview
- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - Detailed API docs
- [TEAM_BRANDING.md](TEAM_BRANDING.md) - Visual guide
- [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md) - Complete implementation details

## Support

For issues or questions:
1. Check documentation files
2. Review test cases for examples
3. Inspect code comments
4. Review Laravel/Jetstream docs
