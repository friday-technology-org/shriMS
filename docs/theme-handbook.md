# LaraCMS Theme Handbook

Welcome to the LaraCMS Theme Handbook! If you're coming from a WordPress background, you'll feel right at home. LaraCMS offers a deeply familiar, WordPress-style templating system powered by Laravel Blade.

## Table of Contents
1. [Theme Structure](#theme-structure)
2. [Activation](#activation)
3. [The Loop](#the-loop)
4. [Theme Helpers Reference](#theme-helpers-reference)
5. [Menus and Widgets](#menus-and-widgets)

---

## Theme Structure

All themes must reside in the `cms-content/themes/` directory.

A standard theme directory looks like this:

```
cms-content/themes/my-theme/
├── theme.json           # Theme metadata
├── assets/              # CSS, JS, Images
└── views/
    ├── index.blade.php  # The main template
    ├── single.blade.php # Single post template
    ├── page.blade.php   # Static page template
    └── layouts/
        └── main.blade.php
```

---

## The Loop

The cornerstone of LaraCMS theming is "The Loop". Instead of manually looping over arrays of `$posts` passed from controllers, you can use the familiar `have_posts()` and `the_post()` global state managers.

**Example `index.blade.php`:**

```blade
@extends('layouts.main')

@section('content')
    @if (have_posts())
        <div class="post-list">
            @while (have_posts())
                @php the_post(); @endphp
                
                <article class="post">
                    <h2><a href="{{ the_permalink() }}">{{ the_title() }}</a></h2>
                    
                    @if(has_post_thumbnail())
                        <img src="{{ the_post_thumbnail_url() }}" alt="{{ the_title() }}">
                    @endif
                    
                    <div class="excerpt">
                        {{ the_excerpt() }}
                    </div>
                    
                    <div class="meta">
                        Posted by {{ the_author() }} on {{ the_date('Y-m-d') }}
                    </div>
                </article>
            @endwhile
        </div>
        
        <div class="pagination">
            {!! the_posts_navigation() !!}
        </div>
    @else
        <p>No posts found.</p>
    @endif
@endsection
```

---

## Theme Helpers Reference

LaraCMS provides a massive suite of WordPress-compatible global helpers that you can use anywhere in your Blade templates.

### Core Helpers
*   `cms_logo(string $type = 'header')` – Returns the URL for a customizer logo.
*   `cms_favicon(?string $type = null)` – Returns the URL for the favicon.
*   `bloginfo(string $show = 'name')` – Retrieves site information ('name', 'description', 'url').
*   `home_url()` - Retrieves the site's home URL.

### The Loop Helpers
*   `cms_loop()` – Retrieves the global ThemeLoop instance.
*   `have_posts()` – Checks if there are posts left in the loop.
*   `the_post()` – Advances the loop to the next post.
*   `wp_reset_postdata()` – Resets global post data back to the main query.
*   `get_post($post = null)` – Returns a Post model instance.

### Post Data Helpers
*   `get_the_title($post = null)` / `the_title($post = null)` – Post Title.
*   `get_the_content($post = null)` / `the_content($post = null)` – Parsed Post Content (executes shortcodes).
*   `get_the_excerpt($post = null)` / `the_excerpt($post = null)` – Post Excerpt.
*   `get_the_permalink($post = null)` / `the_permalink($post = null)` – Post URL.
*   `the_date(string $format = 'F j, Y', $post = null)` – Post Publish Date.

### Media & Thumbnails
*   `has_post_thumbnail($post = null)` – Boolean check for featured image.
*   `get_the_post_thumbnail_url($post = null)` / `the_post_thumbnail_url($post = null)` – Featured Image URL.

### Author Helpers
*   `get_the_author($post = null)` / `the_author($post = null)` – Author's display name.
*   `get_the_author_avatar_url($post = null)` / `the_author_avatar_url($post = null)` – Author Avatar.
*   `the_author_posts_link($post = null)` – HTML link to author's archive.

### Taxonomy Helpers
*   `get_the_category($post = null)` / `the_category($separator = ', ', $post = null)` – Categories.
*   `get_the_tags($post = null)` / `the_tags($before = '', $separator = ', ', $after = '', $post = null)` – Tags.

### Archive Context Helpers
*   `is_singular($post_type = null)` – Check if viewing a single post.
*   `is_post_type_archive($post_type = null)` – Check if viewing an archive.
*   `get_post_type_archive_link(string $post_type)` – Returns the URL for an archive.
*   `the_archive_title()` – Outputs the auto-generated archive title.
*   `the_archive_description()` – Outputs the archive description.

### Comments
*   `get_comments_number($post = null)` – Returns comment count.
*   `the_comments($post = null)` – Renders the comment list view.
*   `comment_form($post = null)` – Outputs the comment submission form.

### SEO & Open Graph
*   `get_page_seo(string $slug): array` – Returns generated SEO meta tags.
*   `og_title()`, `og_description()`, `og_image()`, `og_url()`, `render_og_tags()` - Open Graph tag generators.

---

## Menus and Widgets

### Menus
You can render dynamic navigation menus managed from the Admin Dashboard using:
```blade
{!! cms_nav_menu('primary-menu') !!}
```
If you need raw data to build a custom HTML loop:
```blade
@php $items = cms_menu_items('primary-menu'); @endphp
@foreach($items as $item)
    <a href="{{ $item->url }}">{{ $item->title }}</a>
@endforeach
```

### Widgets
To render a sidebar or widget area managed from the backend:
```blade
<aside class="sidebar">
    {!! dynamic_sidebar('main-sidebar') !!}
</aside>
```
