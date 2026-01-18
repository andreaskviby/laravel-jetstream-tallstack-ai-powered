# Brand Color Palette Generator - Complete Guide

## Overview

The `create-logo` command is an AI-powered brand color palette generator built on scientific principles from neuroscience, color theory, and accessibility standards. It helps you create professional, accessible, and emotionally resonant color systems for your brand.

## Why Color Matters

### Neuroscience of Color

**Visual perception happens faster than conscious thinking.** Color is processed in the brain before shape and language, inside the visual cortex and the limbic system. This means people often *feel* something about your brand before they understand it.

Research shows:
- Color increases brand recognition by up to **80%**
- Visual stimuli reach emotional processing centers before rational analysis
- Consistent color usage speeds product identification by **40%**
- Brands with stable color systems are recognized even when logos are blurred

### Color Psychology

Different colors trigger reliable emotional responses:

| Color | Emotion | Best For |
|-------|---------|----------|
| 🔵 Blue | Trust, calm, logic | Finance, tech, healthcare |
| 🔴 Red | Energy, urgency, passion | Food, retail, entertainment |
| 🟢 Green | Growth, health, balance | Wellness, eco, finance |
| 🟡 Yellow | Optimism, attention, warning | Food, retail, energy |
| ⚫ Black | Power, luxury, sophistication | Fashion, luxury, premium |
| 🟣 Purple | Innovation, creativity, wisdom | Creative, tech, luxury |
| 🟠 Orange | Warmth, enthusiasm, friendliness | Food, creative, tech |

### Accessibility Standards

The command follows **WCAG 2.1** (Web Content Accessibility Guidelines):

- **Level AA**: 4.5:1 contrast ratio for normal text
- **Level AAA**: 7:1 contrast ratio for enhanced accessibility
- **Large Text**: 3:1 minimum (18pt+ or 14pt+ bold)

This ensures your brand is accessible to everyone, including the 300+ million people worldwide with visual impairments.

## Installation

The feature is installed during Phase 9 of the UltimateInstaller when you answer "Yes" to:

```
Install the Brand Color Palette Generator?
```

Or manually install after setup:

```bash
# Create directories
mkdir -p app/Console/Commands app/Services

# Copy files
cp setup/stubs/CreateLogoCommand.stub app/Console/Commands/CreateLogoCommand.php
cp setup/stubs/BrandColorService.stub app/Services/BrandColorService.php

# Clear cache
php artisan cache:clear
```

## Usage Guide

### Basic Usage

```bash
php artisan create-logo
```

This starts an interactive interview that asks about:

1. **Brand Name** - Your company or product name
2. **Industry** - Select from 11 industries
3. **Brand Traits** - Choose 3 descriptive traits (calm, bold, modern, etc.)
4. **Primary Emotion** - What should your brand evoke?
5. **Color Preferences** - Optional colors you like
6. **Colors to Avoid** - Optional colors to exclude

### Quick Test Mode

```bash
php artisan create-logo --skip-interview
```

Generates a palette using default values (Tech startup with trust-focused branding).

### Export Formats

#### Export to JSON
```bash
php artisan create-logo --export=json
```

Creates `brand-palette-[timestamp].json` with complete palette data.

#### Export to CSS
```bash
php artisan create-logo --export=css
```

Creates `brand-palette-[timestamp].css` with CSS custom properties:

```css
:root {
  --color-primary: #3C8CDD;
  --color-primary-rgb: 60, 140, 221;
  --color-secondary-1: #8C8CD9;
  --color-accent: #CF7317;
  --color-white: #FFFFFF;
  --color-black: #111827;
}
```

#### Export to Tailwind
```bash
php artisan create-logo --export=tailwind
```

Creates `brand-palette-[timestamp].tailwind` ready to paste into `tailwind.config.js`:

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: '#3C8CDD',
        'secondary-1': '#8C8CD9',
        accent: '#CF7317',
        // ... more colors
      },
    },
  },
}
```

## The Science Behind the Colors

### Color Harmony Algorithms

The command uses proven color harmony principles:

**Analogous Harmony (Secondary Colors)**
- Colors adjacent on the color wheel (±30°)
- Creates smooth, natural transitions
- Professional and cohesive feeling
- Example: Blue → Blue-Purple → Purple

**Complementary Harmony (Accent Color)**
- Opposite on color wheel (180°)
- Creates dynamic contrast
- Draws attention to CTAs
- Example: Blue ↔ Orange

### Industry-Based Hue Selection

Each industry has scientifically-proven color preferences:

```php
Technology & Software → Blue (210°) - Trust, innovation
Finance & Banking → Deep Blue (220°) - Security, stability
Healthcare & Wellness → Green (140°) - Health, healing
E-commerce & Retail → Red (10°) - Energy, urgency
Creative & Design → Purple (280°) - Creativity, originality
```

### Emotion-Based Color Mapping

Your chosen emotion influences the base hue:

```php
Trust & Security → Blue (210°)
Excitement & Energy → Red (0°)
Calm & Relaxation → Cyan (180°)
Innovation & Progress → Purple (270°)
```

### Trait-Based Lightness

Brand traits adjust color lightness:

```php
Calm & Peaceful → Lighter (65%)
Bold & Energetic → Medium (50%)
Luxurious & Premium → Darker (40%)
Minimal & Clean → Very Light (70%)
```

## Output Examples

### Primary Color
```
PRIMARY COLOR
The core of your brand identity
┌────────────────────────────────────────┐
│  HEX:  #3C8CDD                         │
│  RGB:  rgb(60, 140, 221)               │
│  HSL:  hsl(210°, 70%, 55%)             │
└────────────────────────────────────────┘
```

### Accessibility Report
```
━━━ ACCESSIBILITY REPORT ━━━

✓ PASS Primary color on black background
    Contrast Ratio: 5.06:1  (Required: 4.5:1)

✗ FAIL Primary color on white background
    Contrast Ratio: 3.51:1  (Required: 4.5:1)

✓ PASS Body text (dark on light)
    Contrast Ratio: 17.74:1  (Required: 4.5:1)
```

### Usage Recommendations

The command provides guidance for:
- Logo design
- Website headers
- Button/CTA styling
- Body text contrast
- Background colors

## Recommended Design Tools

The command links to these professional tools for further refinement:

### 1. Color Contrast Checker
**URL**: https://colourcontrast.cc/

Test any two colors against WCAG standards. Essential for ensuring accessibility.

### 2. Khroma
**URL**: https://www.khroma.co/train

AI-powered tool that learns your color preferences and generates unlimited palettes. Shows previews in realistic layouts.

### 3. Color Spectrum
**URL**: https://colorspectrum.design/

Explore harmonious color systems from a single base color. Real-time visualization and direct integration into design workflows.

### 4. Pigment by Shapefactory
**URL**: https://pigment.shapefactory.co/

Creative palette generator based on light and pigment harmony. Great for discovering vibrant, balanced combinations.

### 5. Realtime Colors
**URL**: https://www.realtimecolors.com/

See your palette applied to a realistic website with text, backgrounds, buttons, and headings. Export to CSS variables.

## Testing Your Palette

### Grayscale Test
Convert your design to grayscale. If important elements disappear, your contrast is too low.

```bash
# Use browser DevTools or design software
Filter: grayscale(100%)
```

### Small Screen Test
View on a phone. Text and icons should remain clear and readable.

### User Emotion Test
Show the palette to 3-5 people and ask: "What kind of brand does this feel like?"

Their answers should match your chosen traits.

### Different Lighting Test
Check your colors:
- Outdoor sunlight
- Indoor office lighting
- Night mode / dark backgrounds
- On different devices

## Common Mistakes to Avoid

❌ **Too Many Bright Colors**
Creates visual noise and cognitive fatigue. Stick to the generated palette structure.

❌ **Following Trends Too Closely**
Trends fade quickly. Your brand colors should last years.

❌ **Low Contrast Text**
Makes content hard to read. Always maintain 4.5:1 minimum.

❌ **Copying Competitor Colors**
Their colors work due to years of exposure, not just good design.

❌ **Ignoring Accessibility**
Excluding users with visual impairments is both unethical and bad business.

## Best Practices

### Palette Structure
```
✓ 1 Primary Color    - Brand identity
✓ 2-3 Secondary     - Flexibility
✓ 1 Accent Color    - CTAs, highlights
✓ 5 Neutrals        - Text, backgrounds
```

### Application Guidelines

**Logo**: Primary color dominant, accent for highlights
**Headers**: Primary color with neutral backgrounds
**Buttons**: Accent color to draw attention
**Body Text**: Dark neutral on light neutral (4.5:1+)
**Backgrounds**: Light neutrals with strategic accents

### Color Consistency

Use your palette consistently across:
- Website
- Mobile app
- Marketing materials
- Social media
- Product packaging
- Email templates
- Presentations

## Technical Details

### Color Calculations

**HSL to RGB Conversion**
```php
// Handles hue, saturation, lightness properly
// Maintains color accuracy across formats
```

**Relative Luminance**
```php
// WCAG formula for perceived brightness
// Used in contrast ratio calculations
```

**Contrast Ratio**
```php
// (lighter + 0.05) / (darker + 0.05)
// Scores from 1:1 (no contrast) to 21:1 (maximum)
```

### Dependencies

**None!** The command uses:
- Pure PHP color mathematics
- Built-in Laravel Console components
- No external packages required

### Performance

- Generation time: < 0.1 seconds
- Memory usage: < 2MB
- File exports: < 5KB each

## Integration with Your Project

### Using CSS Variables

```html
<style>
  @import url('brand-palette.css');
  
  .btn-primary {
    background-color: var(--color-primary);
    color: var(--color-white);
  }
  
  .btn-accent {
    background-color: var(--color-accent);
  }
</style>
```

### Using in Tailwind

```javascript
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        // Paste generated Tailwind config here
      },
    },
  },
}
```

Then use:
```html
<button class="bg-primary text-white">
  Click Me
</button>
```

### Using in Laravel Views

```php
// Store in config/brand.php
return [
    'colors' => [
        'primary' => '#3C8CDD',
        'accent' => '#CF7317',
        // ...
    ],
];
```

```blade
<div style="background-color: {{ config('brand.colors.primary') }}">
    Brand Header
</div>
```

## FAQ

**Q: Can I regenerate if I don't like the results?**
A: Yes! Run the command again. Change your emotion or traits to get different results.

**Q: What if my brand already has colors?**
A: Use the "preferred colors" field during the interview, or manually adjust the generated palette.

**Q: Can I modify the generated colors?**
A: Absolutely. The palette is a starting point. Refine with the recommended design tools.

**Q: How often should I update my brand colors?**
A: Rarely. Consistent colors build recognition. Only update during major rebrands.

**Q: What about dark mode?**
A: The neutral palette includes colors for dark backgrounds. Test your primary/accent colors on dark backgrounds using the accessibility report.

**Q: Can I use this for client projects?**
A: Yes! The generated palettes are yours to use commercially.

## Support & Resources

### Documentation
- Installation guide: `setup/stubs/CREATE_LOGO_README.stub`
- This guide: `BRAND_COLOR_GUIDE.md`
- Main README: `README.md`

### Code
- Command: `app/Console/Commands/CreateLogoCommand.php`
- Service: `app/Services/BrandColorService.php`

### Related Features
- Team Branding: Upload logos and set colors per team
- Filament Admin: Manage brand assets in admin panel
- Landing Pages: AI-generated pages with your colors

## Credits

Based on research from:
- W3C Web Content Accessibility Guidelines (WCAG) 2.1
- Color theory and harmony principles
- Neuroscience studies on visual perception
- Marketing psychology research
- Industry best practices and case studies

## License

MIT License - Use freely in personal and commercial projects.

---

**Need Help?**

Open an issue: https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/issues

Or reach out to the community!
