<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localization
{
    public function handle(Request $request, Closure $next)
    {
        if(session()->has('locale')) {
            app()->setLocale(session()->get('locale'));
        } else {
            session()->put('locale', 'ar');
            app()->setLocale('ar');
        }
        return $next($request);
    }
}
