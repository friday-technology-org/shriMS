<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XssSanitizer
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        
        array_walk_recursive($input, function(&$value, $key) {
            // Fields that should be completely ignored
            $ignoreFields = ['_token', '_method', 'password', 'password_confirmation'];
            
            // Fields that are expected to contain rich text HTML (WYSIWYG)
            $htmlFields = ['content', 'body', 'description', 'excerpt', 'banner_text', 'text'];

            if (is_string($value) && !in_array($key, $ignoreFields)) {
                if (in_array($key, $htmlFields)) {
                    $value = clean($value); // Use HTMLPurifier for rich text
                } else {
                    $value = strip_tags($value); // Use fast tag stripping for standard inputs like Name, Email, IDs
                }
            }
        });
        
        $request->merge($input);
        
        return $next($request);
    }
}
