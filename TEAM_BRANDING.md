# Team Branding Feature - Visual Guide

## Feature Overview

The Team Branding feature adds powerful customization options to Laravel Jetstream teams, allowing each team to establish their unique visual identity.

## Screenshots

### Team Settings Page
The team branding section appears on the team settings page alongside other team management options:

```
┌─────────────────────────────────────────────────┐
│  Team Settings                                  │
├─────────────────────────────────────────────────┤
│                                                 │
│  Team Name                                      │
│  [Update team name section]                     │
│                                                 │
│  ─────────────────────────────────────────     │
│                                                 │
│  Team Branding  ⭐ NEW                          │
│  Customize your team's appearance with a        │
│  logo and brand colors.                         │
│                                                 │
│  Team Logo                                      │
│  ┌──────┐                                       │
│  │ LOGO │  [Select A New Logo] [Remove Logo]   │
│  └──────┘                                       │
│                                                 │
│  Primary Brand Color                            │
│  [■ #FF5733] [#FF5733              ]          │
│                                                 │
│  Secondary Brand Color                          │
│  [■ #33C3FF] [#33C3FF              ]          │
│                                                 │
│  [Save]                              Saved. ✓   │
│                                                 │
└─────────────────────────────────────────────────┘
```

## Features in Detail

### 1. Logo Upload
- **Supported formats**: JPG, PNG, GIF, SVG
- **Maximum size**: 1MB
- **Real-time preview**: See your logo before saving
- **Easy management**: Remove logo with one click

### 2. Color Customization
- **Two color options**: Primary and Secondary brand colors
- **Dual input methods**:
  - Visual color picker for easy selection
  - Text input for precise hex codes
- **Live validation**: Ensures valid hex color format (#RRGGBB or #RGB)

### 3. Usage Examples

#### Example 1: Tech Startup
- Logo: Modern geometric logo
- Primary Color: `#6366F1` (Indigo)
- Secondary Color: `#8B5CF6` (Purple)

#### Example 2: Creative Agency
- Logo: Artistic brush stroke
- Primary Color: `#EC4899` (Pink)
- Secondary Color: `#F59E0B` (Amber)

#### Example 3: Financial Services
- Logo: Professional emblem
- Primary Color: `#0EA5E9` (Sky Blue)
- Secondary Color: `#10B981` (Emerald)

## Technical Details

### Database Schema
```sql
ALTER TABLE teams ADD COLUMN logo_path VARCHAR(2048) NULL;
ALTER TABLE teams ADD COLUMN primary_color VARCHAR(7) NULL;
ALTER TABLE teams ADD COLUMN secondary_color VARCHAR(7) NULL;
```

### Validation Rules
- **Logo**: Must be an image file, max 1MB
- **Colors**: Must match hex color pattern `#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})`

### Storage
- Logos stored in: `storage/app/public/team-logos/`
- Accessible via: `public/storage/team-logos/`

## Integration

The team model provides convenient accessors:

```php
// Get the logo URL
$team->logo_url;  // Returns: /storage/team-logos/xyz.jpg

// Get colors
$team->primary_color;    // Returns: #FF5733
$team->secondary_color;  // Returns: #33C3FF
```

## Use Cases

1. **Multi-tenant Applications**: Each team can have its own branding
2. **White-label Solutions**: Customize appearance per client
3. **Brand Consistency**: Maintain brand identity across the platform
4. **Team Identity**: Help teams establish visual presence

## Future Enhancements

Potential extensions to this feature:
- Custom fonts for team content
- Additional accent colors
- Logo variants (light/dark mode)
- Brand guidelines storage
- CSS variable generation from colors
