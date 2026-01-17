# AI Landing Page Generator - Visual Guide

This document describes the user interface and visual elements of the AI Landing Page Generator feature.

## Main Interface

### Navigation Menu
- **Desktop**: A new "AI Landing Pages" link appears in the top navigation bar
- **Mobile**: The link also appears in the responsive hamburger menu
- **Styling**: Matches the existing Jetstream navigation design

### Todo Management Dashboard

#### Header Section
```
┌─────────────────────────────────────────────────────────┐
│  AI Landing Page Generator                 [New Task]   │
│  Create stunning landing pages for your SaaS ideas...  │
└─────────────────────────────────────────────────────────┘
```
- Title: "AI Landing Page Generator"
- Subtitle: "Create stunning landing pages for your SaaS ideas using AI"
- Action Button: Blue "New Task" button with plus icon

#### Empty State
When no tasks exist:
```
┌─────────────────────────────────────────────────────────┐
│                                                          │
│                    📄 (document icon)                    │
│                                                          │
│                    No tasks yet                          │
│   Get started by creating your first AI-powered        │
│                  landing page.                          │
│                                                          │
│              [Create New Task]                          │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

#### Task List
When tasks exist:
```
┌─────────────────────────────────────────────────────────┐
│  TaskFlow Pro Landing Page                              │
│  [Completed] [Landing Page]                             │
│  TaskFlow Pro is a modern project management tool...   │
│  Created: 2 hours ago                  [View Result] [Delete]
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  MailGenius AI Landing Page                             │
│  [Processing] [Landing Page]                            │
│  MailGenius AI is an AI-powered email marketing...     │
│  Created: 5 minutes ago                         [Delete]
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  StreamTeam Landing Page                                │
│  [Failed] [Landing Page]                                │
│  StreamTeam is the async video collaboration...        │
│  Created: 1 day ago                    [Retry] [Delete]
└─────────────────────────────────────────────────────────┘
```

#### Status Badges
- **Completed**: Green badge (bg-green-100 text-green-800)
- **Processing**: Blue badge (bg-blue-100 text-blue-800)
- **Failed**: Red badge (bg-red-100 text-red-800)
- **Pending**: Gray badge (bg-gray-100 text-gray-800)

#### Task Type Badges
- **Landing Page**: Purple badge (bg-purple-100 text-purple-800)
- **Research**: Orange badge (bg-orange-100 text-orange-800)

## Create Task Modal

```
┌─────────────────────────────────────────────────────────┐
│  Create New Task                                    [×] │
│─────────────────────────────────────────────────────────│
│                                                          │
│  Task Type                                              │
│  [Landing Page Generator ▼]                            │
│                                                          │
│  Title                                                  │
│  [e.g., My Awesome SaaS Landing Page          ]        │
│                                                          │
│  Description                                            │
│  ┌────────────────────────────────────────────┐        │
│  │ Describe your SaaS idea in detail...       │        │
│  │                                             │        │
│  │                                             │        │
│  │                                             │        │
│  └────────────────────────────────────────────┘        │
│  Provide details about your product, target            │
│  audience, key features, and unique value...           │
│                                                          │
│                              [Cancel] [Create & Generate]
└─────────────────────────────────────────────────────────┘
```

### Modal Features
- **Overlay**: Semi-transparent gray background
- **Centered**: Modal appears in the center of the screen
- **Responsive**: Adapts to mobile screens
- **Validation**: Real-time validation with error messages
- **Dropdown**: Task type selector with options

## View Result Modal

### For Landing Pages
```
┌─────────────────────────────────────────────────────────┐
│  TaskFlow Pro Landing Page                     [×]     │
│─────────────────────────────────────────────────────────│
│                                                          │
│  Generated Landing Page                    [Copy HTML] │
│  ┌────────────────────────────────────────────┐        │
│  │  [Rendered Landing Page Preview]           │        │
│  │                                             │        │
│  │  🚀 TaskFlow Pro                           │        │
│  │  Streamline Your Team's Workflow           │        │
│  │                                             │        │
│  │  [Get Started Free]                        │        │
│  │                                             │        │
│  │  ✨ Features                               │        │
│  │  • Real-time collaboration                 │        │
│  │  • Time tracking                           │        │
│  │  • 50+ integrations                        │        │
│  │                                             │        │
│  │  💰 Pricing                                │        │
│  │  Starter | Pro | Enterprise                │        │
│  │  $29     | $79 | Custom                    │        │
│  │                                             │        │
│  └────────────────────────────────────────────┘        │
│  (Scrollable content)                                   │
│                                                          │
│                                        [Close]          │
└─────────────────────────────────────────────────────────┘
```

### For Failed Tasks
```
┌─────────────────────────────────────────────────────────┐
│  Failed Task                                   [×]     │
│─────────────────────────────────────────────────────────│
│                                                          │
│  ┌────────────────────────────────────────────┐        │
│  │  ⚠️ Error:                                 │        │
│  │  Claude API key not configured. Please set │        │
│  │  CLAUDE_API_KEY in your .env file.        │        │
│  └────────────────────────────────────────────┘        │
│                                                          │
│                                        [Close]          │
└─────────────────────────────────────────────────────────┘
```

## Color Scheme

### Primary Colors
- **Indigo/Blue**: Primary actions, links (#4F46E5)
- **Green**: Success states (#10B981)
- **Red**: Error states, delete actions (#EF4444)
- **Yellow/Orange**: Warning states (#F59E0B)
- **Gray**: Neutral elements (#6B7280)

### Background Colors
- **White**: Main content areas (#FFFFFF)
- **Light Gray**: Subtle backgrounds (#F9FAFB)
- **Gradient**: Hero sections (blue-50 to indigo-100)

## Responsive Design

### Desktop (>1024px)
- Full-width layout with max-width container
- Side-by-side task cards (if needed)
- Modals centered with appropriate width

### Tablet (768px-1024px)
- Stacked task cards
- Full-width modals with padding
- Adjusted spacing

### Mobile (<768px)
- Single column layout
- Full-screen modals
- Larger touch targets
- Hamburger menu for navigation

## Icons

Uses SVG icons (Heroicons style):
- **Plus**: New task button
- **Document**: Empty state
- **Trash**: Delete button
- **Refresh**: Retry button
- **Eye**: View button
- **Close (X)**: Modal close
- **Check**: Completed status

## Typography

- **Headers**: Font-bold, text-xl to text-2xl
- **Body**: Default font size (text-base)
- **Small text**: text-sm for metadata
- **Accent**: Font-semibold for emphasis

## Animations & Transitions

- **Hover**: Slight color change on buttons
- **Modal**: Fade-in animation
- **Loading**: Processing spinner (if implemented)
- **Transitions**: 150ms ease-in-out

## Accessibility

- **ARIA labels**: Proper modal labels
- **Keyboard navigation**: Tab through interactive elements
- **Focus states**: Visible focus rings
- **Color contrast**: WCAG AA compliant
- **Screen readers**: Semantic HTML

## User Flow Diagram

```
┌─────────────┐
│   Login     │
└─────┬───────┘
      │
      v
┌─────────────────────┐
│  Click "AI Landing  │
│     Pages" Menu     │
└─────────┬───────────┘
          │
          v
┌─────────────────────┐
│   View Dashboard    │◄───────────┐
│  (List of tasks)    │            │
└─────────┬───────────┘            │
          │                        │
          v                        │
┌─────────────────────┐            │
│  Click "New Task"   │            │
└─────────┬───────────┘            │
          │                        │
          v                        │
┌─────────────────────┐            │
│   Fill Task Form    │            │
│  - Type             │            │
│  - Title            │            │
│  - Description      │            │
└─────────┬───────────┘            │
          │                        │
          v                        │
┌─────────────────────┐            │
│ Click "Create &     │            │
│    Generate"        │            │
└─────────┬───────────┘            │
          │                        │
          v                        │
┌─────────────────────┐            │
│   AI Processing     │            │
│  (Status: Processing)│           │
└─────────┬───────────┘            │
          │                        │
          v                        │
┌─────────────────────┐            │
│  Task Completed     │            │
│ (Status: Completed) │            │
└─────────┬───────────┘            │
          │                        │
          v                        │
┌─────────────────────┐            │
│ Click "View Result" │            │
└─────────┬───────────┘            │
          │                        │
          v                        │
┌─────────────────────┐            │
│  View Generated     │            │
│   Landing Page      │            │
└─────────┬───────────┘            │
          │                        │
          v                        │
┌─────────────────────┐            │
│  Copy HTML Code     │            │
└─────────┬───────────┘            │
          │                        │
          v                        │
┌─────────────────────┐            │
│  Close Modal        │────────────┘
└─────────────────────┘
```

## Success Messages

Flash messages appear at the top of the dashboard:
```
┌─────────────────────────────────────────────────────────┐
│  ✓ Todo created successfully! Processing will start    │
│     shortly.                                            │
└─────────────────────────────────────────────────────────┘
```

## Loading States

While processing:
- Status badge shows "Processing" in blue
- Buttons are disabled for that task
- Optional: Spinner animation in the task card

## Error States

When tasks fail:
- Status badge shows "Failed" in red
- Error message stored in metadata
- "Retry" button appears
- Error details shown in view modal

This visual guide provides a complete overview of the user interface for the AI Landing Page Generator feature.
