<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Theme;
use Cms\Core\Services\ThemeInstallerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function __construct(protected ThemeInstallerService $installer) {}

    public function index(): View
    {
        $themes = Theme::orderByDesc('is_active')->orderBy('name')->get();

        return view('cms-core::themes.index', compact('themes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'theme_zip' => 'required|file|mimes:zip|max:20480',
        ]);

        try {
            $theme = $this->installer->install($request->file('theme_zip'));
            return redirect()->route('cms.themes.index')->with('success', "Theme \"{$theme->name}\" installed successfully.");
        } catch (\Exception $e) {
            return back()->withErrors(['theme_zip' => $e->getMessage()]);
        }
    }

    public function activate(Theme $theme): RedirectResponse
    {
        DB::transaction(function () use ($theme) {
            Theme::where('is_active', true)->update(['is_active' => false]);
            $theme->update(['is_active' => true]);
        });

        return redirect()->route('cms.themes.index')->with('success', "\"{$theme->name}\" is now the active theme.");
    }

    public function destroy(Theme $theme): RedirectResponse
    {
        try {
            $this->installer->delete($theme);
            return redirect()->route('cms.themes.index')->with('success', 'Theme deleted.');
        } catch (\Exception $e) {
            return back()->withErrors(['theme' => $e->getMessage()]);
        }
    }

    public function preview(Theme $theme): RedirectResponse
    {
        return redirect(url('/?cms_preview_theme=' . $theme->slug));
    }
}
