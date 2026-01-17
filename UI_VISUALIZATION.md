# UI Component Visualization

## Team Branding Form - HTML Structure

This document shows the HTML structure and visual appearance of the team branding form.

```html
<!-- Team Branding Section -->
<div class="team-branding-section">
    
    <!-- Section Header -->
    <div class="section-header">
        <h3>Team Branding</h3>
        <p>Customize your team's appearance with a logo and brand colors.</p>
    </div>

    <!-- Logo Upload Section -->
    <div class="logo-section">
        <label>Team Logo</label>
        
        <!-- Current Logo Display -->
        <div class="current-logo">
            <img src="/storage/team-logos/abc123.jpg" 
                 class="h-20 w-20 rounded-lg object-cover" 
                 alt="Team Logo">
        </div>
        
        <!-- Buttons -->
        <button class="btn-secondary">Select A New Logo</button>
        <button class="btn-secondary">Remove Logo</button>
        
        <!-- Hidden File Input -->
        <input type="file" class="hidden" wire:model="logo">
    </div>

    <!-- Primary Color Section -->
    <div class="color-section">
        <label>Primary Brand Color</label>
        <div class="flex items-center">
            <!-- Color Picker -->
            <input type="color" 
                   value="#FF5733"
                   class="h-10 w-20 rounded">
            
            <!-- Hex Input -->
            <input type="text" 
                   value="#FF5733"
                   placeholder="#FF5733"
                   class="ml-2 flex-1 rounded-md">
        </div>
    </div>

    <!-- Secondary Color Section -->
    <div class="color-section">
        <label>Secondary Brand Color</label>
        <div class="flex items-center">
            <!-- Color Picker -->
            <input type="color" 
                   value="#33C3FF"
                   class="h-10 w-20 rounded">
            
            <!-- Hex Input -->
            <input type="text" 
                   value="#33C3FF"
                   placeholder="#33C3FF"
                   class="ml-2 flex-1 rounded-md">
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="actions">
        <span class="success-message">Saved. ✓</span>
        <button class="btn-primary">Save</button>
    </div>
    
</div>
```

## ASCII Art Representation

```
╔══════════════════════════════════════════════════════════════╗
║                       TEAM BRANDING                          ║
║  Customize your team's appearance with a logo and colors     ║
╠══════════════════════════════════════════════════════════════╣
║                                                              ║
║  Team Logo                                                   ║
║  ┌──────────┐                                               ║
║  │          │                                               ║
║  │   LOGO   │   [Select A New Logo]  [Remove Logo]         ║
║  │          │                                               ║
║  └──────────┘                                               ║
║                                                              ║
║  ────────────────────────────────────────────────────────   ║
║                                                              ║
║  Primary Brand Color                                         ║
║  ┌────┐  ┌──────────────────────────────────────────────┐  ║
║  │ 🎨 │  │ #FF5733                                      │  ║
║  └────┘  └──────────────────────────────────────────────┘  ║
║                                                              ║
║  ────────────────────────────────────────────────────────   ║
║                                                              ║
║  Secondary Brand Color                                       ║
║  ┌────┐  ┌──────────────────────────────────────────────┐  ║
║  │ 🎨 │  │ #33C3FF                                      │  ║
║  └────┘  └──────────────────────────────────────────────┘  ║
║                                                              ║
║  ────────────────────────────────────────────────────────   ║
║                                                              ║
║                                    Saved. ✓      [  Save  ] ║
║                                                              ║
╚══════════════════════════════════════════════════════════════╝
```

## Component Breakdown

### 1. Form Section Component
```blade
<x-form-section submit="updateTeamBranding">
    <!-- Title and description slots -->
    <!-- Form fields slot -->
    <!-- Actions slot -->
</x-form-section>
```

### 2. Logo Upload Field
- **Type**: File input with preview
- **Features**: 
  - Hidden file input
  - Button triggers file selector
  - Shows current logo
  - Shows preview of new selection
  - Delete button when logo exists

### 3. Color Picker Fields
- **Type**: Combined color picker + text input
- **Features**:
  - Native color picker widget
  - Text input for manual hex entry
  - Synced values between both inputs
  - Real-time validation

### 4. Action Buttons
- **Primary Action**: Save button
- **Secondary Actions**: Logo selection/removal
- **Feedback**: Success message appears after save

## Styling Classes (Tailwind CSS)

### Logo Image
```css
.rounded-lg      /* Rounded corners */
.h-20            /* Height: 5rem */
.w-20            /* Width: 5rem */
.object-cover    /* Cover fit */
```

### Color Picker
```css
.h-10            /* Height: 2.5rem */
.w-20            /* Width: 5rem */
.rounded         /* Rounded corners */
.border-gray-300 /* Border color */
```

### Text Input
```css
.flex-1          /* Flex grow */
.rounded-md      /* Medium rounded corners */
.border-gray-300 /* Border color */
.shadow-sm       /* Small shadow */
```

### Buttons
```css
.btn-secondary   /* Secondary button style */
.btn-primary     /* Primary button style */
.mt-2            /* Top margin */
.me-2            /* End margin */
```

## Responsive Behavior

### Desktop (≥ 640px)
```blade
col-span-6 sm:col-span-4
```
- Full width on mobile
- 4/6 columns on small screens and up

### Mobile (< 640px)
- Stacked layout
- Full-width inputs
- Reduced spacing

## Interactive States

### Normal State
```
┌────────────────┐
│ Select A Logo  │
└────────────────┘
```

### Hover State
```
┌────────────────┐
│ Select A Logo  │ ← Slightly darker
└────────────────┘
```

### Loading State (with Livewire)
```
┌────────────────┐
│ ⌛ Uploading... │
└────────────────┘
```

### Success State
```
Saved. ✓
```

### Error State
```
❌ The logo must not be greater than 1MB.
```

## Color Picker Widget Appearance

### Browser Native Color Picker

**Chrome/Edge:**
```
┌──────┐
│ ████ │ ← Shows selected color
└──────┘
```

**Firefox:**
```
┌──────┐
│ ████ │ ← Shows selected color + picker icon
└──────┘
```

**Safari:**
```
┌──────┐
│ ████ │ ← Shows selected color
└──────┘
```

## Form Validation Visual Feedback

### Valid Input
```
Primary Brand Color
┌────┐  ┌──────────────┐
│ 🟧 │  │ #FF5733      │ ← No error message
└────┘  └──────────────┘
```

### Invalid Input
```
Primary Brand Color
┌────┐  ┌──────────────┐
│ 🟧 │  │ invalid      │ ← Red border
└────┘  └──────────────┘
❌ The primary color must be a valid hex color code
```

## Real-World Examples

### Example 1: Tech Company
```
Logo: Modern geometric icon
Primary:   #6366F1 (Indigo)   ████████
Secondary: #8B5CF6 (Purple)   ████████
```

### Example 2: Creative Agency
```
Logo: Artistic brush stroke
Primary:   #EC4899 (Pink)     ████████
Secondary: #F59E0B (Amber)    ████████
```

### Example 3: Financial Services
```
Logo: Professional shield
Primary:   #0EA5E9 (Sky Blue) ████████
Secondary: #10B981 (Emerald)  ████████
```

## Accessibility Features

✅ Proper label associations
✅ Semantic HTML structure
✅ Keyboard navigation support
✅ Screen reader friendly
✅ Focus indicators
✅ Error message announcements

## Browser Compatibility

✅ Chrome/Edge (Chromium)
✅ Firefox
✅ Safari
✅ Mobile browsers

Native color picker appearance varies by browser but functionality is consistent.
