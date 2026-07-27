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
        $postsCount = Post::where('post_type', 'post')->count();
        $pagesCount = Post::where('post_type', 'page')->count();
        $cptCount = Post::whereNotIn('post_type', ['post', 'page', 'attachment'])->count();
        $usersCount = User::count();

        return view('cms-core::dashboard.index', compact(
            'postsCount', 'pagesCount', 'cptCount', 'usersCount'
        ));
    }
}
