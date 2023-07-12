<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocaleCheck
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->get('locale');
        if($locale == 'ar') {
            session()->put('locale', 'ar');
        }

        if(session()->has('locale')) {
            app()->setLocale(session()->get('locale'));
        } else {
            session()->put('locale', 'en');
            app()->setLocale('en');
        }
        return $next($request);
    }
}
