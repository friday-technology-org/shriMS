# LaraCMS Theme Handbook

Welcome to the LaraCMS Theme Developer Handbook! LaraCMS themes use standard Laravel Blade templating mixed with classic WordPress-style concepts (like `theme.json`, the Template Hierarchy, and Page Templates).

## Directory Structure

Themes live inside the `cms-content/themes/` directory.

```
cms-content/
  └── themes/
      └── my-custom-theme/
          ├── theme.json
          ├── functions.php
          ├── screenshot.png
          ├── assets/
          │   ├── css/
          │   └── js/
          ├── views/
          │   ├── index.blade.php
          │   ├── single.blade.php
          │   ├── page.blade.php
          │   ├── 404.blade.php
          │   └── partials/
          │       ├── header.blade.php
          │       └── footer.blade.php
          └── templates/
              └── full-width.blade.php
```

## `theme.json`

Every theme requires a `theme.json` file in its root directory. This tells LaraCMS how to identify your theme.

```json
{
    "name": "My Custom Theme",
    "slug": "my-custom-theme",
    "description": "A beautiful, modern LaraCMS theme.",
    "version": "1.0.0",
    "author": "Your Name"
}
```

## Template Hierarchy

LaraCMS automatically routes frontend requests to the most specific Blade view available in your theme. Views are prefixed with the `theme::` namespace.

When resolving a URL (like a Post, Page, or Custom Post Type), LaraCMS checks your theme's `views/` directory in this order:

### For Pages:
1. `page-{slug}.blade.php` (e.g. `page-about-us.blade.php`)
2. `page.blade.php`
3. `single.blade.php`
4. `index.blade.php` (Fallback)

### For Other Post Types (Posts, Portfolios, etc.):
1. `single-{post_type}.blade.php` (e.g. `single-portfolio.blade.php`)
2. `single.blade.php`
3. `index.blade.php` (Fallback)

### For Archives (Categories/Tags):
1. `archive-{taxonomy}.blade.php` (e.g. `archive-category.blade.php`)
2. `archive.blade.php`
3. `index.blade.php` (Fallback)

## Page Templates

Just like WordPress, LaraCMS supports completely custom Page Templates that users can select from a dropdown in the Admin UI.

To create a Page Template, create a new `.blade.php` file anywhere in your theme (e.g. `views/templates/landing.blade.php`) and add this special comment block at the very top:

```blade
@php
/* Template Name: Landing Page */
@endphp

@extends('theme::layouts.master')
@section('content')
  <!-- Your custom layout -->
@endsection
```

LaraCMS will detect this file, populate it in the **Page Attributes** dropdown in the editor, and securely override the Template Hierarchy to load it when selected.

## `functions.php`

If you place a `functions.php` file in the root of your theme, LaraCMS will automatically load it when the theme is active. This is the perfect place to:

- Register Custom Shortcodes
- Enqueue Assets (CSS/JS)
- Add Filters and Actions

```php
<?php

// Add a custom filter
add_filter('the_content', function($content) {
    return $content . '<p>Thanks for reading!</p>';
});

// Register a custom shortcode
add_shortcode('button', function($atts, $content) {
    $url = $atts['url'] ?? '#';
    return "<a href='{$url}' class='btn'>{$content}</a>";
});
```

## Creating Themes via Artisan

LaraCMS provides an Artisan command to quickly scaffold a new theme:

```bash
php artisan cms:make-theme my-new-theme
```

This will generate the folder structure, a `theme.json`, and basic Blade templates for you!
