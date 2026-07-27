<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-cms-seo :post="$post ?? null" />

    <x-cms-favicon />
    <link rel="stylesheet" href="{{ asset('themes/default/assets/theme.css') }}?v=1.0">
    {!! cms_customizer_head() !!}
</head>
<body>
    <header class="cms-site-header">
        @if(\Cms\Core\Models\Menu::where('location', 'top_bar')->exists())
        <div class="cms-header-top-bar">
            <div class="cms-container">
                {!! cms_nav_menu('top_bar') !!}
            </div>
        </div>
        @endif
        <div class="cms-container cms-header-main">
            <a href="{{ url('/') }}" class="cms-logo-link">
                <x-cms-logo type="header" style="width: {{ cms_option('customizer_logo_width', 160) }}px" />
                @unless(cms_logo('header'))
                    {{ cms_option('site_title', 'LaraCMS') }}
                @endunless
            </a>
            {!! cms_nav_menu('primary') !!}
        </div>
    </header>

    <div class="cms-container">
        @yield('content')
    </div>

    <footer class="cms-site-footer">
        <div class="cms-container cms-footer-columns">
            {!! cms_widget_area('footer_col_1') !!}
            {!! cms_widget_area('footer_col_2') !!}
            {!! cms_widget_area('footer_col_3') !!}
            {!! cms_widget_area('footer_col_4') !!}
        </div>
        <div class="cms-container cms-footer-bottom">
            <div>
                @if(cms_logo('footer'))
                    <x-cms-logo type="footer" style="max-height: 32px" />
                @else
                    &copy; {{ date('Y') }} {{ cms_option('site_title', 'LaraCMS') }}
                @endif
            </div>
            {!! cms_nav_menu('footer') !!}
        </div>
    </footer>

    {!! cms_customizer_footer_scripts() !!}
</body>
</html>
