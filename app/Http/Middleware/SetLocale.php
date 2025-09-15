<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Set locale
        $languages = array_keys(config('app.languages'));
        $route = $request->route();

        // Check if language is set in the URL
        if (request('change_language')) {

            // Set language in session
            session()->put('language', request('change_language'));
            $language = request('change_language');

            // Check if language is in the available languages
            if (array_key_exists('locale', $route->parameters) && $route->parameters['locale'] != $language) {
                $route->parameters['locale'] = $language;

                if (in_array($language, $languages)) {
                    app()->setLocale($language);
                }

                return redirect(route($route->getName(), $route->parameters));
            }
        } elseif (session('language')) {
            // Set language in session
            $language = session('language');

            // Check if language is in the available languages
            if (array_key_exists('locale', $route->parameters) && $route->parameters['locale'] != $language && in_array($route->parameters['locale'], $languages)) {
                $language = $route->parameters['locale'];
                session()->put('language', $language);
            }
        } elseif (config('app.locale')) {
            // Set language in config
            $language = config('app.locale');
        }

        if (isset($language) && in_array($language, $languages)) {
            // Set language in session
            app()->setLocale($language);
        }

        return $next($request);
    }
}
