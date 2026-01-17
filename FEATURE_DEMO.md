# Feature Demonstration Guide

## Team Branding Feature - Step-by-Step Walkthrough

This guide demonstrates the team branding feature in action.

---

## 🎯 Scenario 1: New Team Sets Up Branding

### Step 1: Navigate to Team Settings
```
User clicks: "Settings" → "Team Settings"
```

**Initial State:**
```
╔══════════════════════════════════════════════╗
║  Team Branding                               ║
║  Customize your team's appearance            ║
╠══════════════════════════════════════════════╣
║  Team Logo                                   ║
║  [ No logo uploaded yet ]                    ║
║                                              ║
║  [Select A New Logo]                         ║
║                                              ║
║  Primary Brand Color                         ║
║  [      ] [ #______ ]  (empty)               ║
║                                              ║
║  Secondary Brand Color                       ║
║  [      ] [ #______ ]  (empty)               ║
║                                              ║
║  [Save]                                      ║
╚══════════════════════════════════════════════╝
```

---

### Step 2: Upload Team Logo

**Action:** User clicks "Select A New Logo"

**System:** Opens file picker

**User:** Selects `company-logo.png` (500KB)

**Result - Preview Appears:**
```
╔══════════════════════════════════════════════╗
║  Team Logo                                   ║
║  ┌──────────┐                               ║
║  │   NEW    │  ← Preview of selected file   ║
║  │  LOGO!   │                               ║
║  └──────────┘                               ║
║                                              ║
║  [Select A New Logo]  [Remove Logo]          ║
╚══════════════════════════════════════════════╝
```

**Status:** 
- ✅ File validated (is image)
- ✅ Size validated (< 1MB)
- 🟡 Not saved yet (preview only)

---

### Step 3: Set Primary Color

**Action:** User clicks color picker

**User:** Selects orange color

**Result - Color Updates:**
```
╔══════════════════════════════════════════════╗
║  Primary Brand Color                         ║
║  ┌────┐  ┌────────────────────────────────┐ ║
║  │ 🟧 │  │ #FF5733                        │ ║
║  └────┘  └────────────────────────────────┘ ║
╚══════════════════════════════════════════════╝
```

**Alternative:** User types hex code directly
```
User types: "#FF5733" in text field
Color picker updates automatically to show orange
```

---

### Step 4: Set Secondary Color

**Action:** User selects blue from color picker

**Result:**
```
╔══════════════════════════════════════════════╗
║  Secondary Brand Color                       ║
║  ┌────┐  ┌────────────────────────────────┐ ║
║  │ 🔵 │  │ #33C3FF                        │ ║
║  └────┘  └────────────────────────────────┘ ║
╚══════════════════════════════════════════════╝
```

---

### Step 5: Save Changes

**Action:** User clicks "Save" button

**System Processing:**
```
Livewire Processing...
├─ Validating logo file... ✅
├─ Validating primary color... ✅
├─ Validating secondary color... ✅
├─ Storing logo file... ✅
│  └─ Path: team-logos/xyz123.png
├─ Updating team record... ✅
└─ Dispatching events... ✅
```

**Result - Success:**
```
╔══════════════════════════════════════════════╗
║  Team Branding              Saved. ✓         ║
╠══════════════════════════════════════════════╣
║  Team Logo                                   ║
║  ┌──────────┐                               ║
║  │   SAVED  │  ← Saved logo                 ║
║  │   LOGO   │                               ║
║  └──────────┘                               ║
║  [Select A New Logo]  [Remove Logo]          ║
║                                              ║
║  Primary: #FF5733 🟧                         ║
║  Secondary: #33C3FF 🔵                       ║
║                                              ║
║  [Save]                          Saved. ✓    ║
╚══════════════════════════════════════════════╝
```

**Database State:**
```sql
UPDATE teams 
SET logo_path = 'team-logos/xyz123.png',
    primary_color = '#FF5733',
    secondary_color = '#33C3FF'
WHERE id = 1;
```

**File System:**
```
storage/app/public/team-logos/
└── xyz123.png (saved successfully)
```

---

## 🎯 Scenario 2: Updating Existing Branding

### Current State:
```
Team already has:
- Logo: old-logo.jpg
- Primary: #FF0000 (red)
- Secondary: #0000FF (blue)
```

### User wants to:
- Change primary color only
- Keep logo and secondary color

**Action:** User changes primary color to green

```
Before:
┌────┐  ┌──────────┐
│ 🔴 │  │ #FF0000  │
└────┘  └──────────┘

After:
┌────┐  ┌──────────┐
│ 🟢 │  │ #00FF00  │
└────┘  └──────────┘
```

**User clicks "Save"**

**Result:**
- ✅ Primary color updated to #00FF00
- ✅ Logo remains unchanged
- ✅ Secondary color remains unchanged
- ✅ Success message displayed

---

## 🎯 Scenario 3: Removing Team Logo

### Current State:
```
Team has a logo uploaded
```

### Action: User clicks "Remove Logo"

**Confirmation Flow:**
```
1. User clicks [Remove Logo]
2. System removes logo immediately (no confirmation)
3. Success message appears
```

**Result:**
```
Before:
┌──────────┐
│   LOGO   │  [Select A New Logo] [Remove Logo]
└──────────┘

After:
[ No logo ]  [Select A New Logo]
                                   Saved. ✓
```

**System Actions:**
```
1. Delete file from storage ✅
   rm storage/app/public/team-logos/xyz123.png
   
2. Update database ✅
   UPDATE teams SET logo_path = NULL WHERE id = 1
   
3. Dispatch saved event ✅
```

---

## ❌ Scenario 4: Validation Errors

### Error 1: File Too Large

**User:** Uploads 2MB image

**Result:**
```
╔══════════════════════════════════════════════╗
║  Team Logo                                   ║
║  [Upload failed]                             ║
║                                              ║
║  ❌ The logo must not be greater than 1MB.  ║
╚══════════════════════════════════════════════╝
```

---

### Error 2: Invalid File Type

**User:** Uploads PDF document

**Result:**
```
╔══════════════════════════════════════════════╗
║  Team Logo                                   ║
║  [Upload failed]                             ║
║                                              ║
║  ❌ The logo must be an image.              ║
╚══════════════════════════════════════════════╝
```

---

### Error 3: Invalid Color Code

**User:** Types "red" in color field

**Result:**
```
╔══════════════════════════════════════════════╗
║  Primary Brand Color                         ║
║  ┌────┐  ┌────────────────────────────────┐ ║
║  │ ?? │  │ red                         ❌ │ ║
║  └────┘  └────────────────────────────────┘ ║
║                                              ║
║  ❌ The primary color must be a valid hex   ║
║     color code (e.g., #FF5733).             ║
╚══════════════════════════════════════════════╝
```

**Valid alternatives:**
- ✅ #FF0000
- ✅ #F00
- ❌ red
- ❌ rgb(255,0,0)

---

## 📱 Scenario 5: Mobile Experience

### Mobile Layout (< 640px):

```
┌─────────────────────────┐
│  Team Branding          │
├─────────────────────────┤
│                         │
│  Team Logo              │
│  ┌─────────┐           │
│  │  LOGO   │           │
│  └─────────┘           │
│                         │
│  [Select New Logo]      │
│  [Remove Logo]          │
│                         │
│  Primary Color          │
│  [🎨]                  │
│  [#FF5733        ]     │
│                         │
│  Secondary Color        │
│  [🎨]                  │
│  [#33C3FF        ]     │
│                         │
│  [Save]      Saved. ✓   │
└─────────────────────────┘
```

**Differences from Desktop:**
- Stacked layout
- Full-width inputs
- Larger touch targets
- Same functionality

---

## 🔄 Scenario 6: Real-time Preview

### Logo Preview Flow:

```
Step 1: Initial State
┌─────────────┐
│  No Logo    │
└─────────────┘

Step 2: File Selected (not uploaded yet)
┌─────────────┐
│  PREVIEW!   │  ← Temporary URL
└─────────────┘

Step 3: After Save
┌─────────────┐
│  SAVED!     │  ← Permanent URL
└─────────────┘
```

### Color Picker Sync:

```
When user changes color picker:
Color Picker: 🟧 → Text Input: "#FF5733"

When user types hex code:
Text Input: "#FF5733" → Color Picker: 🟧

Both stay synchronized in real-time!
```

---

## 💾 Data Flow

### Complete Flow Diagram:

```
User Interface
     ↓
  Livewire Component
     ↓
  Validation
     ↓
  ┌─────────────┬─────────────┐
  ↓             ↓             ↓
Storage      Database     Events
(logo)      (colors)    (refresh)
```

### Detailed Steps:

1. **User Interaction**
   - File select / color pick / form submit

2. **Livewire Processing**
   - Wire:model binds inputs
   - Temporary file upload

3. **Validation**
   - Image type check
   - Size limit check
   - Hex color format

4. **Storage**
   - Save to storage/app/public/team-logos/
   - Generate unique filename

5. **Database**
   - Update team record
   - Save logo_path, colors

6. **Events**
   - Dispatch 'saved' event
   - Dispatch 'refresh-navigation-menu'

7. **UI Feedback**
   - Show success message
   - Update preview

---

## 🎨 Visual Examples

### Example Team 1: Tech Startup
```
Logo: [Modern Tech Icon]
Primary: #6366F1 (Indigo) ████████
Secondary: #8B5CF6 (Purple) ████████
Use: Professional, innovative look
```

### Example Team 2: Creative Agency
```
Logo: [Artistic Brush Stroke]
Primary: #EC4899 (Pink) ████████
Secondary: #F59E0B (Amber) ████████
Use: Creative, energetic vibe
```

### Example Team 3: Finance Company
```
Logo: [Shield/Lock Icon]
Primary: #0EA5E9 (Blue) ████████
Secondary: #10B981 (Green) ████████
Use: Trust, stability, growth
```

---

## ✅ Success Criteria

After completing all steps:

✅ Team has custom logo uploaded
✅ Team has primary brand color set
✅ Team has secondary brand color set
✅ Logo displays correctly across app
✅ Colors can be used for theming
✅ Changes saved to database
✅ Files stored securely
✅ User receives clear feedback

---

## 🎓 Learning Points

**For Users:**
- Easy visual customization
- Immediate preview feedback
- Clear error messages
- Intuitive interface

**For Developers:**
- Clean Livewire integration
- Proper validation
- Secure file handling
- Event-driven architecture

This completes the feature demonstration guide!
