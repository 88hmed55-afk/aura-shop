<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', 'en');

        if ($request->has('lang') && in_array($request->get('lang'), ['en', 'ar'])) {
            $locale = $request->get('lang');
            session(['locale' => $locale]);

            if (auth()->check()) {
                auth()->user()->update(['locale' => $locale]);
            }
        } elseif (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
        }

        App::setLocale($locale);

        return $next($request);
    }
}
