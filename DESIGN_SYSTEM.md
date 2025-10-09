# HireCamp Design System

This document outlines the design system and UI patterns used throughout the HireCamp application to ensure consistency.

## Color Palette

### Primary Colors
- **Blue**: `blue-600` (Primary actions, links)
- **Purple**: `purple-600` (Secondary accent)
- **Green**: `green-600` (Success states, positive metrics)
- **Orange**: `orange-600` (Warnings, needs attention)
- **Red**: `red-600` (Errors, critical items)
- **Yellow**: `yellow-600` (Caution, medium priority)

### Background Colors
- **Page Background**: `bg-gray-50`
- **Card Background**: `bg-white`
- **Hover State**: `hover:shadow-md`
- **Border**: `border-gray-200`

## Typography

### Headings
- **Page Title (H1)**: `text-4xl font-bold text-gray-900`
- **Section Title (H2)**: `text-2xl font-bold text-gray-900`
- **Card Title (H3)**: `text-lg font-semibold text-gray-900`
- **Card Subtitle (H4)**: `text-sm font-semibold text-gray-900`

### Body Text
- **Regular**: `text-gray-600` or `text-gray-700`
- **Small**: `text-sm text-gray-600`
- **Tiny**: `text-xs text-gray-500`

## Components

### Cards
```html
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
    <!-- Card content -->
</div>
```

### Stats Cards
```html
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">Label</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">Value</p>
            <p class="text-sm text-green-600 mt-1">Metric</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <x-icon name="icon-name" class="w-6 h-6 text-blue-600" />
        </div>
    </div>
</div>
```

### Buttons (WireUI)
- **Primary**: `<x-button primary>Label</x-button>`
- **Secondary**: `<x-button secondary>Label</x-button>`
- **Outline**: `<x-button outline primary>Label</x-button>`
- **With Icon**: `<x-button primary icon="icon-name">Label</x-button>`

### Badges
```html
<!-- Success Badge -->
<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">
    Badge Text
</span>

<!-- Warning Badge -->
<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-700">
    Badge Text
</span>

<!-- Info Badge -->
<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
    Badge Text
</span>
```

### Tabs
```html
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
            <button class="py-4 px-1 border-b-2 font-medium text-sm {{ $active ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Tab Label
            </button>
        </nav>
    </div>
    <div class="p-6">
        <!-- Tab content -->
    </div>
</div>
```

### Avatar (Gradient)
```html
<!-- User/Company Avatar -->
<div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg flex items-center justify-center font-semibold text-sm">
    A
</div>

<!-- Alternate Color -->
<div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg flex items-center justify-center font-semibold text-sm">
    B
</div>
```

### Progress Bars
```html
<div>
    <div class="flex justify-between text-sm mb-2">
        <span class="text-gray-700 font-medium">Label</span>
        <span class="text-gray-900 font-semibold">75%</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2">
        <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: 75%"></div>
    </div>
</div>
```

## Spacing

### Container Padding
- **Page Container**: `py-12`
- **Card Padding**: `p-6`
- **Section Margin**: `mb-8` or `mt-8`
- **Element Gap**: `gap-6` for grids, `gap-4` for smaller items

### Grid Layouts
```html
<!-- 4-Column Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Grid items -->
</div>

<!-- 2-Column Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Grid items -->
</div>

<!-- 3-Column Sidebar Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <!-- Main content -->
    </div>
    <div>
        <!-- Sidebar -->
    </div>
</div>
```

## Border Radius
- **Cards**: `rounded-lg`
- **Buttons**: Default from WireUI
- **Badges**: `rounded` or `rounded-full`
- **Avatars**: `rounded-lg` (square) or `rounded-full` (circle)

## Shadows
- **Default Card**: `shadow-sm`
- **Hover State**: `hover:shadow-md`
- **Elevated**: `shadow-lg`
- **Navigation**: `shadow-sm`

## Transitions
- **Standard Transition**: `transition-shadow duration-200`
- **All Properties**: `transition-all duration-300`

## Icon Sizes
- **Large**: `w-6 h-6` or `w-8 h-8`
- **Medium**: `w-5 h-5`
- **Small**: `w-4 h-4`
- **Tiny**: `w-3 h-3`

## Notification Banners
```html
<!-- Success Banner -->
<div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 p-6">
    <!-- Content -->
</div>

<!-- Info Banner -->
<div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-200 p-6">
    <!-- Content -->
</div>
```

## Empty States
```html
<div class="text-center py-12">
    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
        <x-icon name="document-text" class="w-8 h-8 text-gray-400" />
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">Empty State Title</h3>
    <p class="text-gray-600 mb-6">Empty state description</p>
    <x-button primary>Call to Action</x-button>
</div>
```

## Best Practices

1. **Always use WireUI components** for buttons, cards, and form elements
2. **Maintain consistent spacing** using the spacing system above
3. **Use semantic colors** - blue for primary actions, green for success, red for errors
4. **Add hover states** to interactive elements for better UX
5. **Use consistent border radius** - `rounded-lg` for cards and containers
6. **Apply proper shadow hierarchy** - lighter shadows for static, darker for interactive
7. **Ensure responsive design** using Tailwind's responsive prefixes (sm:, md:, lg:)
8. **Use WireUI icons** with the `<x-icon>` component
9. **Maintain text hierarchy** with consistent font sizes and weights
10. **Add transitions** for smooth user interactions

## Component Library
- **WireUI**: Primary component library
- **Tailwind CSS**: Utility-first styling
- **Heroicons**: Icon system (via WireUI)

## Accessibility
- Use proper semantic HTML
- Include ARIA labels where appropriate
- Ensure sufficient color contrast (WCAG AA compliant)
- Make interactive elements keyboard-accessible
- Provide clear focus states

---

*Last Updated: {{ date('F Y') }}*

