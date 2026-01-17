# Team Branding API Documentation

## Overview

This document describes how to programmatically interact with the Team Branding feature.

## Model: Team

### Attributes

| Attribute | Type | Description | Validation |
|-----------|------|-------------|------------|
| `logo_path` | string\|null | Relative path to the team logo file | Max 2048 characters |
| `primary_color` | string\|null | Primary brand color in hex format | Valid hex color (#RGB or #RRGGBB) |
| `secondary_color` | string\|null | Secondary brand color in hex format | Valid hex color (#RGB or #RRGGBB) |

### Computed Attributes

| Attribute | Type | Description |
|-----------|------|-------------|
| `logo_url` | string\|null | Full URL to the team logo |

### Usage Examples

```php
// Get a team
$team = Team::find(1);

// Access branding attributes
$logoPath = $team->logo_path;           // "team-logos/abc123.jpg"
$logoUrl = $team->logo_url;             // "http://example.com/storage/team-logos/abc123.jpg"
$primaryColor = $team->primary_color;   // "#FF5733"
$secondaryColor = $team->secondary_color; // "#33C3FF"

// Update branding
$team->update([
    'primary_color' => '#FF5733',
    'secondary_color' => '#33C3FF',
]);

// Upload a new logo (using Storage)
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

$file = request()->file('logo');
$path = $file->store('team-logos', 'public');
$team->update(['logo_path' => $path]);

// Delete logo
if ($team->logo_path) {
    Storage::disk('public')->delete($team->logo_path);
    $team->update(['logo_path' => null]);
}
```

## Livewire Component: UpdateTeamBrandingForm

### Public Properties

| Property | Type | Description |
|----------|------|-------------|
| `team` | Team | The team instance being edited |
| `state` | array | Form state containing color values |
| `logo` | mixed | The uploaded logo file (Livewire temporary file) |

### Methods

#### `mount($team)`
Initializes the component with the team instance.

```php
@livewire('teams.update-team-branding-form', ['team' => $team])
```

#### `updateTeamBranding()`
Updates the team's branding information including logo and colors.

**Validation Rules:**
- `logo`: nullable, must be an image, max 1024 KB
- `primary_color`: nullable, must match hex color pattern
- `secondary_color`: nullable, must match hex color pattern

**Events Dispatched:**
- `saved`: Triggered after successful update
- `refresh-navigation-menu`: Triggers navigation refresh

#### `deleteLogo()`
Deletes the team's current logo from storage.

**Events Dispatched:**
- `saved`: Triggered after successful deletion

### Frontend Integration

```blade
<!-- Include in your team settings view -->
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    @livewire('teams.update-team-branding-form', ['team' => $team])
</div>
```

## Validation

### Logo Validation

```php
'logo' => ['nullable', 'image', 'max:1024']
```

- **nullable**: Logo is optional
- **image**: Must be a valid image file (jpg, jpeg, png, gif, svg)
- **max:1024**: Maximum file size is 1MB (1024 KB)

### Color Validation

```php
'primary_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/']
'secondary_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/']
```

- **nullable**: Colors are optional
- **regex**: Must be a valid hex color code
  - Short format: `#RGB` (e.g., `#F00`)
  - Long format: `#RRGGBB` (e.g., `#FF0000`)

### Valid Color Examples

✅ Valid:
- `#FF5733`
- `#fff`
- `#000000`
- `#ABC`

❌ Invalid:
- `FF5733` (missing #)
- `#GGGGGG` (invalid hex characters)
- `rgb(255, 87, 51)` (wrong format)
- `red` (named colors not supported)

## Storage Configuration

Team logos are stored in the `public` disk under the `team-logos` directory.

### Storage Structure

```
storage/
└── app/
    └── public/
        └── team-logos/
            ├── abc123xyz.jpg
            ├── def456uvw.png
            └── ...
```

### Public Access

Ensure the storage link is created:

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`, making uploaded files accessible via URLs like:

```
http://your-app.com/storage/team-logos/abc123xyz.jpg
```

## Events

### Listening to Branding Updates

You can listen to team update events in your application:

```php
use Laravel\Jetstream\Events\TeamUpdated;

Event::listen(TeamUpdated::class, function ($event) {
    $team = $event->team;
    
    // Check if branding was updated
    if ($team->wasChanged(['logo_path', 'primary_color', 'secondary_color'])) {
        // Handle branding update
        // e.g., clear caches, notify team members, etc.
    }
});
```

## Security Considerations

1. **File Validation**: All uploaded logos are validated to ensure they are images
2. **Size Limits**: Maximum upload size is 1MB to prevent abuse
3. **Storage**: Files are stored in a controlled directory with proper permissions
4. **Authorization**: Only authorized team members can update branding (handled by Jetstream)
5. **Sanitization**: Color codes are validated with regex to prevent injection attacks

## Best Practices

1. **Image Optimization**: Consider resizing/optimizing logos before display
2. **Caching**: Cache logo URLs for better performance
3. **CDN**: Consider serving logos from a CDN in production
4. **Cleanup**: Implement a cleanup job for orphaned logo files
5. **Backup**: Include the `team-logos` directory in your backup strategy

## Troubleshooting

### Logo Upload Fails

**Issue**: File upload returns validation error

**Solutions**:
- Check file size (must be ≤ 1MB)
- Verify file is a valid image format
- Ensure `storage/app/public` directory is writable
- Check PHP upload limits in `php.ini`:
  - `upload_max_filesize`
  - `post_max_size`

### Logo Not Displaying

**Issue**: Logo URL returns 404

**Solutions**:
- Run `php artisan storage:link`
- Verify file exists in `storage/app/public/team-logos/`
- Check file permissions
- Verify web server configuration allows accessing `/storage/` directory

### Color Validation Fails

**Issue**: Valid hex color is rejected

**Solutions**:
- Ensure color starts with `#`
- Use only valid hex characters (0-9, A-F, a-f)
- Use correct format: `#RGB` or `#RRGGBB`
