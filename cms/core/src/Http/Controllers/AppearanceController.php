<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Services\FaviconService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AppearanceController extends Controller
{
    /**
     * Curated, short list of Google Fonts — kept small on purpose (MVP scope).
     */
    public array $fonts = [
        'Instrument Sans', 'Inter', 'Roboto', 'Poppins', 'Nunito',
        'Open Sans', 'Lato', 'Montserrat', 'Playfair Display', 'Merriweather',
    ];

    public function __construct(protected FaviconService $faviconService) {}

    public function edit(): View
    {
        $settings = [
            'logo_header' => cms_option('customizer_logo_header'),
            'logo_header_dark' => cms_option('customizer_logo_header_dark'),
            'logo_footer' => cms_option('customizer_logo_footer'),
            'logo_header_2x' => cms_option('customizer_logo_header_2x'),
            'logo_width' => cms_option('customizer_logo_width', 160),
            'color_primary' => cms_option('customizer_color_primary', '#7364DB'),
            'color_secondary' => cms_option('customizer_color_secondary', '#111827'),
            'font' => cms_option('customizer_font', 'Instrument Sans'),
            'custom_css' => cms_option('customizer_custom_css', ''),
            'custom_js_header' => cms_option('customizer_custom_js_header', ''),
            'custom_js_footer' => cms_option('customizer_custom_js_footer', ''),
        ];

        $favicons = cms_option('customizer_favicons', []);
        $fonts = $this->fonts;

        return view('cms-core::customizer.edit', compact('settings', 'favicons', 'fonts'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'logo_header' => 'nullable|integer|exists:media,id',
            'logo_header_dark' => 'nullable|integer|exists:media,id',
            'logo_footer' => 'nullable|integer|exists:media,id',
            'logo_header_2x' => 'nullable|integer|exists:media,id',
            'logo_width' => 'nullable|integer|min:20|max:600',
            'color_primary' => 'nullable|string|max:20',
            'color_secondary' => 'nullable|string|max:20',
            'font' => 'nullable|string|max:100',
            'custom_css' => 'nullable|string',
            'custom_js_header' => 'nullable|string',
            'custom_js_footer' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            update_cms_option('customizer_' . $key, $value);
        }

        return redirect()->route('cms.customizer.edit')->with('success', 'Customizer settings saved.');
    }

    public function favicon(Request $request): RedirectResponse
    {
        $request->validate([
            'favicon_source' => 'required|file|mimes:png,jpg,jpeg,svg|max:5120',
        ]);

        try {
            $urls = $this->faviconService->generate($request->file('favicon_source'));
            update_cms_option('customizer_favicons', $urls);

            return redirect()->route('cms.customizer.edit')->with('success', 'Favicon set generated.');
        } catch (\Exception $e) {
            return back()->withErrors(['favicon_source' => $e->getMessage()]);
        }
    }
}
