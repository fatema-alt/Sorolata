<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminOrStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated. Administrator session required.',
                ], 401);
            }

            return redirect()->guest(route('admin.login'))
                ->with('error', 'অ্যাডমিন ড্যাশবোর্ডে প্রবেশ করতে অনুগ্রহ করে আগে লগইন করুন।');
        }

        $user = Auth::user();

        // 2. Check if user has administrative or staff privileges
        if (!$user->isStaff() && !$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden. You do not have staff permissions to access this area.',
                ], 403);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('error', 'অনুমতি নেই! শুধুমাত্র অনুমোদিত অ্যাডমিন ও স্টাফবৃন্দ প্রবেশ করতে পারবেন।');
        }

        return $next($request);
    }
}
