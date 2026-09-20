<?php

namespace Cms\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If maintenance mode is enabled and the CMS is fully installed
        if (function_exists('is_cms_installed') && is_cms_installed() && function_exists('cms_option') && cms_option('maintenance_mode') == 1) {
            
            // Bypass for admin, API, and install routes
            if (!$request->is('admin*') && !$request->is('api*') && !$request->is('install*')) {
                // Return 503 maintenance view
                return response()->view('cms-core::errors.maintenance', [], 503);
            }
        }

        return $next($request);
    }
}
