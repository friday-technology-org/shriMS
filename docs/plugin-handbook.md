# LaraCMS Plugin Handbook

Welcome to the LaraCMS Plugin Handbook! Plugins are the most powerful way to extend the functionality of LaraCMS without modifying the core system. 

Since LaraCMS is built on Laravel, a plugin is essentially a modularized Laravel Package that hooks into our auto-discovery systems.

## Table of Contents
1. [Plugin Structure](#plugin-structure)
2. [Artisan Scaffolding](#artisan-scaffolding)
3. [Auto-Discovery](#auto-discovery)
4. [Hooks and Filters API](#hooks-and-filters-api)
5. [Shortcode API](#shortcode-api)

---

## Plugin Structure

Plugins live in the `cms-content/plugins/` directory.

A typical plugin structure looks like this:

```
cms-content/plugins/my-plugin/
├── plugin.json                 # Plugin metadata (name, description, version)
├── cms_plugins.php             # Optional file for registering global hooks/filters
├── src/
│   ├── Providers/
│   │   └── MyPluginServiceProvider.php
│   ├── Http/
│   │   └── Controllers/
│   └── Models/
├── migrations/                 # Standard Laravel migrations
├── routes/
│   └── web.php
└── resources/
    └── views/
```

---

## Artisan Scaffolding

LaraCMS provides dedicated Artisan commands to rapidly scaffold plugin files. This ensures your files have the correct namespace and are placed in the correct `cms-content/plugins/` subdirectory.

*   **Create a Plugin Controller:**
    ```bash
    php artisan cms:plugin:make-controller your-plugin-slug Admin/DashboardController
    ```
*   **Create a Plugin Model:**
    ```bash
    php artisan cms:plugin:make-model your-plugin-slug Invoice
    ```
    *Tip: Add `-m` to automatically generate a linked migration!*
    
*   **Create a standalone Migration:**
    ```bash
    php artisan cms:plugin:make-migration your-plugin-slug create_invoices_table --create=invoices
    ```

---

## Auto-Discovery

To make development as frictionless as possible, LaraCMS automatically discovers specific files in your plugin directory when the plugin is activated:

### Migrations
Any standard Laravel migration placed inside the `migrations/` folder (e.g., `cms-content/plugins/your-plugin-slug/migrations/`) will automatically be executed when you run the global `php artisan migrate` command. There is no need to manually register migration paths in your Service Provider.

### Service Providers
If your `plugin.json` specifies a primary service provider, LaraCMS will boot it automatically. Inside your provider, you can register standard Laravel routes and views:

```php
public function boot()
{
    $this->loadViewsFrom(__DIR__.'/../resources/views', 'myplugin');
    $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
}
```

---

## Hooks and Filters API

The Hooks and Filters API is how plugins communicate with the LaraCMS core and with other plugins. 

*   **Action Hooks (`add_action`)**: Inject code or HTML at specific points during execution.
*   **Filters (`add_filter`)**: Intercept and modify data arrays or strings before they are processed.

### Modifying Dashboard Data
To modify the core statistics data or to inject your own variables into the admin dashboard:

```php
add_filter('cms_dashboard_data', function($data) {
    // Modify existing core data
    $data['postsCount'] = 999;
    
    // Inject your custom data
    $data['active_invoices'] = 15;
    
    return $data;
});
```

### Injecting Dashboard Widgets
You can inject HTML or Blade Views directly into the dashboard without editing core files.

*   `cms_dashboard_widgets_top`: Injects widgets at the very top of the dashboard.
*   `cms_dashboard_widgets`: Injects widgets below the main statistics section.

```php
add_action('cms_dashboard_widgets', function() {
    // Render a view from your plugin
    echo view('myplugin::admin.dashboard-widget')->render();
});
```

---

## Shortcode API

LaraCMS provides a fully WordPress-compatible Shortcode API for registering dynamic content tags that users can place in the WYSIWYG editor.

### Registering a Shortcode
Use `add_shortcode` in your `cms_plugins.php` or Service Provider:

```php
add_shortcode('invoice_tracker', function($atts, $content = null) {
    // Merge user attributes with defaults
    $a = shortcode_atts([
        'id' => '0',
        'theme' => 'light'
    ], $atts);
    
    // Query database or render view
    return view('myplugin::shortcodes.invoice', ['id' => $a['id']])->render();
});
```

When a user types `[invoice_tracker id="592"]` into a post, LaraCMS will automatically parse it and render your blade view!

### Manual Parsing
If you have a string of text from a custom database column and want to parse shortcodes inside it:
```php
$html = do_shortcode($customText);
```
