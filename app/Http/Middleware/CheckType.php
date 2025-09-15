<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Config;
use App\Models\Configuration;
use Illuminate\Http\Request;

class CheckType
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
        // Get config value
        $config = Configuration::get();
        $APP_T = $config[13]->config_value;

        // Check type
        if ($APP_T == 1) {
            return $next($request);
        }

        return redirect()->route('login');
        
    }
  
}
