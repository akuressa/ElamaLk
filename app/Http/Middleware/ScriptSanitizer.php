<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;
use Symfony\Component\HttpFoundation\Response;

class ScriptSanitizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitize input
        $input = $request->all();

        array_walk_recursive($input, function (&$input) {
            if (isset($input)) {
                $input = Purifier::clean(html_entity_decode($input));
            } else {
                $input = null;
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
