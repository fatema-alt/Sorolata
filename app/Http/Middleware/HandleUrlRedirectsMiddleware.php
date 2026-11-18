<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\UrlRedirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleUrlRedirectsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only inspect GET / HEAD requests for SEO redirects
        if (!$request->isMethodSafe()) {
            return $next($request);
        }

        $path = '/' . ltrim($request->path(), '/');
        $fullPath = $request->getRequestUri();

        // Check if redirect rule exists for current path or uri
        $redirect = UrlRedirect::where('is_active', true)
            ->where(function ($query) use ($path, $fullPath): void {
                $query->where('source_url', $path)
                      ->orWhere('source_url', $fullPath)
                      ->orWhere('source_url', rtrim($path, '/'));
            })
            ->first();

        if ($redirect) {
            $redirect->increment('hits_count');

            $statusCode = in_array($redirect->status_code, [301, 302, 307, 308], true)
                ? $redirect->status_code
                : 301;

            return redirect($redirect->destination_url, $statusCode);
        }

        return $next($request);
    }
}
