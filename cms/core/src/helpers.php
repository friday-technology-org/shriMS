<?php

use Cms\Core\Models\Option;

if (!function_exists('is_cms_installed')) {
    /**
     * Check if the CMS has been installed.
     */
    function is_cms_installed(): bool
    {
        if (file_exists(storage_path('app/.installed'))) {
            return true;
        }

        if (app()->runningUnitTests()) {
            return false;
        }

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('users') && \Illuminate\Support\Facades\DB::table('users')->count() > 0) {
                @file_put_contents(storage_path('app/.installed'), json_encode([
                    'installed_at' => now()->toDateTimeString(),
                    'version' => '1.0.0',
                    'note' => 'recovered'
                ]));
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }
}

if (!function_exists('cms_option')) {
    /**
     * Get a CMS option value.
     */
    function cms_option(string $name, mixed $default = null): mixed
    {
        if (!is_cms_installed()) {
            return $default;
        }

        try {
            return Option::get($name, $default);
        } catch (\Throwable $e) {
            return $default;
        }
    }
}

if (!function_exists('update_cms_option')) {
    /**
     * Update or create a CMS option value.
     */
    function update_cms_option(string $name, mixed $value, bool $autoload = true): bool
    {
        try {
            Option::set($name, $value, $autoload);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}

if (!function_exists('delete_cms_option')) {
    /**
     * Delete a CMS option.
     */
    function delete_cms_option(string $name): bool
    {
        try {
            return Option::forget($name);
        } catch (\Throwable $e) {
            return false;
        }
    }
}

if (!function_exists('get_field')) {
    /**
     * Get a custom field (post meta) value for a post.
     * Equivalent to ACF's get_field().
     *
     * @param  string    $key     Field name / meta key
     * @param  int|null  $postId  Post ID — defaults to current route post
     * @return mixed
     */
    function get_field(string $key, ?int $postId = null): mixed
    {
        if (!$postId && function_exists('get_post')) {
            $postId = get_post()?->id;
        }
        
        if (!$postId) return null;

        try {
            $post = \Cms\Core\Models\Post::find($postId);
            return $post?->getMeta($key);
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (!function_exists('get_field_media')) {
    /**
     * Get a Media model from an image-type ACF field.
     * Usage: $media = get_field_media('hero_image', $postId);
     *        echo $media?->url();
     *
     * @param  string    $key     Field name / meta key
     * @param  int|null  $postId  Post ID
     * @return \Cms\Core\Models\Media|null
     */
    function get_field_media(string $key, ?int $postId = null): ?\Cms\Core\Models\Media
    {
        if (!$postId && function_exists('get_post')) {
            $postId = get_post()?->id;
        }
        
        if (!$postId) return null;

        try {
            $post = \Cms\Core\Models\Post::find($postId);
            return $post?->getMediaMeta($key);
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (!function_exists('the_field')) {
    /**
     * Echo a custom field value directly.
     * Equivalent to ACF's the_field().
     */
    function the_field(string $key, ?int $postId = null): void
    {
        echo htmlspecialchars((string) get_field($key, $postId));
    }
}

if (!function_exists('cms_logo')) {
    /**
     * Get the public URL for a branding logo set via the Customizer.
     * $type: header | header_dark | footer | header_2x
     */
    function cms_logo(string $type = 'header'): ?string
    {
        try {
            $mediaId = cms_option('customizer_logo_' . $type);
            if (!$mediaId) return null;

            return \Cms\Core\Models\Media::find($mediaId)?->url();
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (!function_exists('cms_favicon')) {
    /**
     * Get the generated favicon/site-icon asset URLs, keyed by asset name
     * (favicon_32, apple_touch, android_192, android_512, ico, mask_icon).
     */
    function cms_favicon(): array
    {
        return cms_option('customizer_favicons', []);
    }
}

if (!function_exists('cms_nav_menu')) {
    /**
     * Render the menu assigned to a given location (primary|top_bar|footer|mobile_drawer).
     * Equivalent to WordPress's wp_nav_menu().
     */
    function cms_nav_menu(string $location): string
    {
        try {
            return (string) \Illuminate\Support\Facades\Blade::render(
                '<x-cms-menu :location="$location" />',
                ['location' => $location]
            );
        } catch (\Throwable $e) {
            return '';
        }
    }
}

if (!function_exists('cms_widget_area')) {
    /**
     * Render all active widgets assigned to a given widget area key.
     */
    function cms_widget_area(string $areaKey): string
    {
        try {
            return (string) \Illuminate\Support\Facades\Blade::render(
                '<x-cms-widget-area :area="$area" />',
                ['area' => $areaKey]
            );
        } catch (\Throwable $e) {
            return '';
        }
    }
}

if (!function_exists('cms_customizer_head')) {
    /**
     * Assemble the <head> output for the Customizer's font, color, custom
     * CSS, and header JS settings. Meant to be echoed unescaped in a theme layout.
     */
    function cms_customizer_head(): string
    {
        $font = cms_option('customizer_font', 'Instrument Sans');
        $colorPrimary = cms_option('customizer_color_primary', '#7364DB');
        $colorSecondary = cms_option('customizer_color_secondary', '#111827');
        $customCss = cms_option('customizer_custom_css', '');
        $customJsHeader = cms_option('customizer_custom_js_header', '');

        $fontUrl = 'https://fonts.googleapis.com/css2?family=' . str_replace(' ', '+', $font) . ':wght@400;500;600;700&display=swap';

        $html = '<link rel="preconnect" href="https://fonts.googleapis.com">';
        $html .= '<link rel="stylesheet" href="' . e($fontUrl) . '">';
        $html .= '<style id="cms-customizer-vars">:root{--cms-color-primary:' . e($colorPrimary) . ';--cms-color-secondary:' . e($colorSecondary) . ';--cms-font-family:\'' . e($font) . '\', sans-serif;}</style>';

        if ($customCss) {
            $html .= '<style id="cms-customizer-custom-css">' . $customCss . '</style>';
        }

        if ($customJsHeader) {
            $html .= '<script>' . $customJsHeader . '</script>';
        }

        return $html;
    }
}

if (!function_exists('cms_customizer_footer_scripts')) {
    /**
     * Assemble the Customizer's footer JS output. Echoed unescaped, right
     * before </body> in a theme layout.
     */
    function cms_customizer_footer_scripts(): string
    {
        $customJsFooter = cms_option('customizer_custom_js_footer', '');
        return $customJsFooter ? '<script>' . $customJsFooter . '</script>' : '';
    }
}

if (!function_exists('add_action')) {
    /**
     * Register an action hook callback.
     */
    function add_action(string $hook, callable $callback, int $priority = 10): void
    {
        app(\Cms\Core\Services\HookManager::class)->addAction($hook, $callback, $priority);
    }
}

if (!function_exists('do_action')) {
    /**
     * Trigger an action hook.
     */
    function do_action(string $hook, ...$args): void
    {
        app(\Cms\Core\Services\HookManager::class)->doAction($hook, ...$args);
    }
}

if (!function_exists('add_filter')) {
    /**
     * Register a filter hook callback.
     */
    function add_filter(string $hook, callable $callback, int $priority = 10): void
    {
        app(\Cms\Core\Services\HookManager::class)->addFilter($hook, $callback, $priority);
    }
}

if (!function_exists('apply_filters')) {
    /**
     * Apply filter hook callbacks to a value.
     */
    function apply_filters(string $hook, mixed $value, ...$args): mixed
    {
        return app(\Cms\Core\Services\HookManager::class)->applyFilters($hook, $value, ...$args);
    }
}

if (!function_exists('add_shortcode')) {
    /**
     * Register a shortcode tag and handler.
     */
    function add_shortcode(string $tag, callable $callback): void
    {
        app(\Cms\Core\Services\ShortcodeParser::class)->register($tag, $callback);
    }
}

if (!function_exists('do_shortcode')) {
    /**
     * Parse shortcodes inside content.
     */
    function do_shortcode(string $content): string
    {
        return app(\Cms\Core\Services\ShortcodeParser::class)->parse($content);
    }
}

if (!function_exists('set_transient')) {
    /**
     * Set a transient value with an expiration time in seconds.
     */
    function set_transient(string $key, mixed $value, int $expiration = 0): bool
    {
        $optKey = '_transient_' . $key;
        $timeoutKey = '_transient_timeout_' . $key;

        cms_option_set($optKey, json_encode($value));

        if ($expiration > 0) {
            cms_option_set($timeoutKey, time() + $expiration);
        } else {
            cms_option_set($timeoutKey, 0);
        }

        return true;
    }
}

if (!function_exists('get_transient')) {
    /**
     * Get a transient value if it has not expired yet.
     */
    function get_transient(string $key): mixed
    {
        $optKey = '_transient_' . $key;
        $timeoutKey = '_transient_timeout_' . $key;

        $timeout = (int) cms_option($timeoutKey, 0);

        if ($timeout > 0 && time() > $timeout) {
            // Expired -> clean up
            delete_transient($key);
            return null;
        }

        $value = cms_option($optKey);
        return $value !== null ? json_decode($value, true) : null;
    }
}

if (!function_exists('delete_transient')) {
    /**
     * Delete a transient value.
     */
    function delete_transient(string $key): bool
    {
        $optKey = '_transient_' . $key;
        $timeoutKey = '_transient_timeout_' . $key;

        // Delete from database directly (cms_options table has a deleteMeta or delete key? Let's use direct DB delete or option set to null)
        // Wait, does cms_option_set(key, null) delete it? Let's check how option set is implemented. Or we can just set to null, or run a query.
        // Let's run a query to delete them from options table to save space.
        \Illuminate\Support\Facades\DB::table('cms_options')->whereIn('option_name', [$optKey, $timeoutKey])->delete();
        return true;
    }
}

if (!function_exists('dispatch_cms_webhook')) {
    /**
     * Dispatch an outgoing webhook event.
     */
    function dispatch_cms_webhook(string $event, array $payload): void
    {
        try {
            $webhooks = \Cms\Core\Models\CmsWebhook::where('is_active', true)->get();
            foreach ($webhooks as $webhook) {
                if (is_array($webhook->events) && in_array($event, $webhook->events)) {
                    \Cms\Core\Jobs\WebhookJob::dispatch($webhook->url, $payload, $webhook->secret);
                }
            }
        } catch (\Throwable $e) {
            logger()->error('Webhook dispatch failed: ' . $e->getMessage());
        }
    }
}

if (!function_exists('cms_locale')) {
    /**
     * Get the active CMS locale.
     */
    function cms_locale(): string
    {
        return app()->getLocale();
    }
}

