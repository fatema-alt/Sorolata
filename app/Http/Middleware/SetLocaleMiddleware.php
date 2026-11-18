<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request and set application locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'bn'];

        // 1. Check if user passed ?lang= in query string
        if ($request->has('lang') && in_array($request->query('lang'), $supportedLocales, true)) {
            $locale = (string) $request->query('lang');
            session(['locale' => $locale]);
        }

        // 2. Resolve from session or fallback to default
        $locale = session('locale', config('app.locale', 'en'));

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
