<?php

namespace Cms\Core\Http\Controllers;

use Illuminate\Routing\Controller;

use Cms\Core\Models\Post;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the CMS dashboard.
     */
    public function index()
    {
        // Allow plugins to intercept and modify the dashboard data
        $dashboardData = apply_filters('cms_dashboard_data', [
            'postsCount' => Post::where('post_type', 'post')->count(),
            'pagesCount' => Post::where('post_type', 'page')->count(),
            'cptCount' => Post::whereNotIn('post_type', ['post', 'page', 'attachment'])->count(),
            'usersCount' => User::count(),
            'activeUsers' => User::with('roles')
                ->withCount(['posts' => function ($query) {
                    $query->whereNotIn('post_type', ['attachment', 'page']);
                }])
                ->latest()
                ->take(5)
                ->get(),
            'topPosts' => Post::whereIn('post_type', ['post', 'page'])
                ->where('status', 'published')
                ->orderByDesc('views_count')
                ->take(5)
                ->get(),
            'chartData' => \Cms\Core\Models\PostViewStat::selectRaw('date, SUM(views) as total_views')
                ->where('date', '>=', \Carbon\Carbon::now()->subDays(7)->toDateString())
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy('date'),
        ]);
            
        $coreVersion = 'Unknown';
        if (file_exists(base_path('cms/core/version.php'))) {
            $coreVersion = require base_path('cms/core/version.php');
        }
        
        $latestVersion = \Illuminate\Support\Facades\Cache::remember('cms_latest_version', 3600, function () use ($coreVersion) {
            try {
                // Placeholder for actual version check API.
                // Replace with actual update server URL when available.
                $response = \Illuminate\Support\Facades\Http::timeout(3)->get('https://api.github.com/repos/bipincodes/lara-cms/releases/latest');
                if ($response->successful() && $response->json('tag_name')) {
                    return ltrim($response->json('tag_name'), 'v');
                }
            } catch (\Exception $e) {
                // Fallback on error
            }
            return $coreVersion;
        });

        $hasUpdate = version_compare($coreVersion, $latestVersion, '<');
        
        $dashboardData['coreVersion'] = $coreVersion;
        $dashboardData['latestVersion'] = $latestVersion;
        $dashboardData['hasUpdate'] = $hasUpdate;

        // Allow plugins to override the entirely of the dashboard view
        $view = apply_filters('cms_dashboard_view', 'cms-core::dashboard.index');

        return view($view, $dashboardData);
    }
}
