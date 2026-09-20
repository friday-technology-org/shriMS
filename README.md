# LaraCMS
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://opensource.org/licenses/MIT)

**LaraCMS** is a powerful, lightweight, and highly modular content management system built on top of [Laravel](https://laravel.com). Designed for developers who love Laravel but miss the rapid, hook-based extensible ecosystem of WordPress, LaraCMS bridges the gap by offering a WordPress-style theming API, native shortcodes, and a robust plugin architecture, all while strictly adhering to modern Laravel best practices.

## 🚀 Key Features

*   **WordPress-Style Theming API:** Familiar helper functions (`the_title()`, `have_posts()`, `the_content()`) allowing rapid theme development without steep learning curves.
*   **Modular Plugin System:** Build standalone features in a localized plugin architecture. Includes auto-discovery of migrations, routes, and views.
*   **Action Hooks & Filters:** A powerful event-driven architecture (`add_action`, `apply_filters`) that lets plugins inject UI elements and mutate data across the core system without modifying core files.
*   **Built-in Customizer & Visual Settings:** White-label your dashboard instantly. Manage logos, favicons, site metadata, and layout configurations out-of-the-box.
*   **Advanced Shortcode API:** Dynamically parse `[my_shortcode]` tags directly within post content.
*   **Native Translation Manager:** A visual backend translation editor that scans blade files for `__()` and `@lang()` directives and allows seamless JSON-based localization.
*   **Robust Custom Fields & Taxonomies:** Assign complex meta-data and hierarchical categories to any custom post type.

---

## 🛠️ Server Requirements

LaraCMS relies on modern PHP and Laravel ecosystem requirements:

*   PHP >= 8.2
*   Composer
*   MySQL 8.0+ / PostgreSQL / SQLite
*   Node.js & NPM (For asset compilation)

---

## 📦 Installation & Setup

1. **Clone the Repository**
   ```bash
   git clone https://github.com/friday-technology-org/shriMS.git lara-cms
   cd lara-cms
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update the `.env` file with your database credentials and `APP_URL`.

4. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

5. **Serve the Application**
   ```bash
   php artisan serve
   ```
   *The admin dashboard is available at `http://localhost:8000/admin`.*

---

## 🏗️ Architecture Overview

LaraCMS strictly separates the core framework from user-generated content, ensuring smooth, non-destructive updates.

*   `cms/core/` – The protected core package. Contains the central CMS logic, admin dashboard views, and base models. **(Do not modify directly)**
*   `cms-content/themes/` – Where your custom frontend themes reside.
*   `cms-content/plugins/` – Where custom plugins are installed.
*   `cms-content/languages/` – Where localized JSON translation files are generated and stored.

---

## 🎨 Theme Development

Themes in LaraCMS are incredibly intuitive, borrowing the best templating patterns from the PHP ecosystem.

1. **Create your theme directory:** `cms-content/themes/my-theme/`
2. **Set up standard views:** Create an `index.blade.php`.
3. **Activate the theme:** Navigate to **Appearance -> Themes** in the Admin Dashboard and activate it.
4. **Utilize Helpers:**

```blade
@extends('layouts.main')

@section('content')
    @while (have_posts())
        @php the_post(); @endphp
        
        <article>
            <h1>{{ the_title() }}</h1>
            <p>{{ the_excerpt() }}</p>
            <a href="{{ the_permalink() }}">Read More</a>
        </article>
    @endwhile
@endsection
```

*(See the **Developer Documentation** below for a full list of Theme Helpers).*

---

## 🔌 Plugin Development

Plugins extend the core functionality of LaraCMS. A plugin is essentially a standard Laravel package loaded dynamically.

### Scaffolding Plugins via Artisan
LaraCMS ships with dedicated Artisan commands to rapidly generate plugin boilerplate:

*   **Generate a Controller:** `php artisan cms:plugin:make-controller plugin-slug Admin/DashboardController`
*   **Generate a Model:** `php artisan cms:plugin:make-model plugin-slug Invoice`
*   **Generate a Migration:** `php artisan cms:plugin:make-migration plugin-slug create_invoices_table`

*(Note: Adding `-m` to `make-model` will automatically scaffold the corresponding migration).*

### Auto-Discovery
*   **Migrations:** Any migration placed in `cms-content/plugins/{slug}/migrations/` is automatically discovered by Laravel when running `php artisan migrate`.
*   **Hooks:** Use `cms_plugins.php` within your plugin root to register `add_action` and `add_filter` hooks globally.

---

## 🪝 Extending the Dashboard (Hooks & Filters)

LaraCMS uses an event-driven architecture that prevents the need for core modifications.

### 1. Data Injection Filters
You can modify the core data arrays before they are sent to the Blade views. For example, to add custom statistics to the main dashboard:

```php
add_filter('cms_dashboard_data', function($data) {
    $data['custom_metric'] = 1500;
    return $data;
});
```

### 2. UI Injection Actions
You can dynamically inject HTML widgets into the Admin Dashboard layout using predefined hooks:

*   `cms_dashboard_widgets_top`: Injects HTML at the very top of the dashboard.
*   `cms_dashboard_widgets`: Injects HTML in the secondary widget area.

```php
add_action('cms_dashboard_widgets', function() {
    echo '<div class="bg-white p-4 rounded-xl border border-neutral"><h3>My Plugin Widget</h3></div>';
});
```

---

## 🌍 Translations & Localization

LaraCMS includes a fully integrated UI for managing localized strings.

1. Write your blade templates using standard Laravel helpers: `{{ __('Welcome') }}` or `@lang('Submit')`.
2. Navigate to **Settings -> Translations** in the Admin Dashboard.
3. Add a language locale (e.g., `es` or `fr`).
4. Click **Edit**. LaraCMS will auto-scan your themes and plugins, extract every translatable string, and present a visual editor to provide translations.
5. Translations are securely saved to `cms-content/languages/` and persist across core updates.

---

## 📚 Developer Documentation

For a comprehensive overview of how to build themes and plugins for LaraCMS, please refer to our official handbooks:

*   📘 **[The Theme Handbook](docs/theme-handbook.md)** - Learn about The Loop, global helpers, and structure.
*   📗 **[The Plugin Handbook](docs/plugin-handbook.md)** - Learn about Hooks, Filters, Shortcodes, and scaffolding commands.

### Page SEO Helper

*   `get_page_seo(string $slug): array` – Returns SEO meta tags for a page identified by its slug. It uses the `SeoHelper` service to build the meta array (title, description, keywords, robots, url, image, og_type). Returns defaults if the page does not exist.

### Global Theme Helpers

*   `cms_logo(string $type = 'header')` – Returns the URL for a customizer logo ('header', 'header_dark', 'footer', 'header_2x').
*   `cms_favicon(?string $type = null)` – Returns the URL for a specific favicon type, or the full array if no type is given.
*   `cms_nav_menu(string $location)` – Renders the menu assigned to a location as HTML.
*   `cms_menu_items(string $location)` – Retrieves menu items as an array/Collection for custom loop rendering.
*   `cms_widget_area(string $areaKey)` – Renders active widgets assigned to the given widget area.
*   `bloginfo(string $show = 'name')` – Retrieves site information ('name', 'description', 'url', etc.) configured in settings.
*   `cms_loop()` – Retrieves the global ThemeLoop instance.
*   `have_posts()` – Checks if there are posts left in the loop.
*   `the_post()` – Advances the loop to the next post.
*   `wp_reset_postdata()` – Resets global post data.
*   `get_post($post = null)` – Returns a Post model instance by object, ID, or current loop.
*   `get_the_title($post = null)` – Retrieves the title.
*   `the_title($post = null)` – Echoes the title.
*   `get_the_content($post = null)` – Retrieves the content.
*   `the_content($post = null)` – Echoes the content.
*   `get_the_excerpt($post = null)` – Retrieves an excerpt.
*   `the_excerpt($post = null)` – Echoes the excerpt.
*   `get_the_permalink($post = null)` – Retrieves the URL.
*   `the_permalink($post = null)` – Echoes the URL.
*   `the_date(string $format = 'F j, Y', $post = null)` – Echoes the formatted publish date.
*   `the_author($post = null)` – Echoes the author's name.
*   `has_post_thumbnail($post = null)` – Checks for a featured image.
*   `get_the_post_thumbnail_url($post = null)` – Returns the thumbnail URL.
*   `the_post_thumbnail_url($post = null)` – Echoes the thumbnail URL.
*   `get_menu(string $location)` – Returns rendered menu HTML.
*   `is_singular($post_type = null)` – Determines if the current view is a single post of a type.
*   `is_post_type_archive($post_type = null)` – Checks if the request is a post‑type archive.
*   `get_post_type_archive_link(string $post_type)` – Returns archive URL.
*   `the_archive_title()` – Echoes an appropriate archive title.
*   `the_archive_description()` – Echoes archive description.
*   `get_comments_number($post = null)` – Returns comment count.
*   `the_comments($post = null)` – Echoes comment list.
*   `comment_form($post = null)` – Outputs a comment form.
*   `esc_html($value)`, `esc_url($value)`, `esc_attr($value)` – Escape helpers.
*   `get_the_author($post = null)`, `the_author($post = null)` – Author helpers.
*   `get_the_author_avatar_url($post = null)`, `the_author_avatar_url($post = null)`, `the_author_posts_link($post = null)` – Author avatar and link.
*   `get_the_category($post = null)`, `the_category($separator = ', ', $post = null)` – Category helpers.
*   `get_the_tags($post = null)`, `the_tags($before = '', $separator = ', ', $after = '', $post = null)` – Tag helpers.
*   `apply_filters(string $tag, $value)` – Placeholder filter system.
*   `dynamic_sidebar(string $area)` – Renders widget area.
*   `og_title`, `og_description`, `og_image`, `og_url`, `render_og_tags` - Open Graph helpers.
*   `home_url`, `get_bloginfo` - Site helpers.
*   `the_posts_navigation($paginator = null)` - Pagination.

### Shortcode API

LaraCMS provides a fully WordPress-compatible Shortcode API for registering dynamic content tags.

*   `add_shortcode(string $tag, callable $callback)` – Registers a new shortcode. The callback receives `$atts`, `$content`, and `$tag`.
*   `do_shortcode(string $content)` – Parses and renders all registered shortcodes within the given content. (This is automatically applied to `the_content()`).
*   `shortcode_atts(array $pairs, array $atts)` – Combines user attributes with known attributes and fills in defaults.
*   `strip_shortcodes(string $content)` – Removes all registered shortcode tags from the content (useful for excerpts).

**Example:**
```php
add_shortcode('greeting', function($atts, $content = null) {
    $a = shortcode_atts(['name' => 'World'], $atts);
    return "Hello {$a['name']}! " . ($content ? "Message: $content" : "");
});
```

---

## 🤝 Contributing

We welcome contributions! Please follow standard PSR-12 coding guidelines.

1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/amazing-feature`).
3. Commit your changes (`git commit -m 'Add amazing feature'`).
4. Push to the branch (`git push origin feature/amazing-feature`).
5. Open a Pull Request.

---

## 📄 License

LaraCMS is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

Crafted with ❤️ by [Friday Technology](https://fridaytechnology.net).
