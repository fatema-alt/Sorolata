<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    /**
     * Show the administrative luxury login screen
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && (Auth::user()->isStaff() || Auth::user()->isAdmin())) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login authentication with brute-force rate-limiting
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:4',
            'remember' => 'nullable|boolean',
        ]);

        $throttleKey = 'admin_login_' . Str::lower($validated['email']) . '|' . $request->ip();

        // 1. Rate Limiting: Lockout after 5 failed attempts
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "অতিরিক্ত ভুল প্রচেষ্টার কারণে লগইন সাময়িকভাবে ব্লক করা হয়েছে। অনুগ্রহ করে {$seconds} সেকেন্ড পর আবার চেষ্টা করুন।",
                ]);
        }

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        $remember = $request->boolean('remember');

        // 2. Attempt Authentication
        if (!Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'প্রদত্ত ইমেইল অথবা পাসওয়ার্ডটি সঠিক নয়।',
                ]);
        }

        $user = Auth::user();

        // 3. Authorization Check: Must be staff or super admin
        if (!$user->isStaff() && !$user->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'অননুমোদিত একাউন্ট! এই প্যানেলে শুধুমাত্র অ্যাডমিন ও স্টাফদের প্রবেশের অনুমতি রয়েছে।',
                ]);
        }

        // 4. Success: Clear rate limiter and regenerate session ID
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        // 5. Audit Logging
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'admin.login.success',
            'description' => "Staff member [{$user->name}] logged into Admin Studio from IP [{$request->ip()}].",
            'created_at' => now(),
        ]);

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', "স্বাগতম, {$user->name}! সরলতা স্টুডিও অ্যাডমিন প্যানেলে সফলভাবে লগইন হয়েছে।");
    }

    /**
     * Terminate the admin session securely
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'admin.logout',
                'description' => "Staff member [{$user->name}] signed out of Admin Studio.",
                'created_at' => now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'আপনি সফলভাবে অ্যাডমিন প্যানেল থেকে লগআউট হয়েছেন।');
    }

    /**
     * Show admin profile & security management form
     */
    public function profile(): View
    {
        return view('admin.auth.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update admin profile details and password
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $validated['name'];
        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }

        // If password change requested
        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'বর্তমান পাসওয়ার্ডটি সঠিক নয়।']);
            }

            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'admin.profile.update',
            'description' => "Staff member [{$user->name}] updated account credentials/profile.",
            'created_at' => now(),
        ]);

        return back()->with('success', 'প্রোফাইল এবং সিকিউরিটি ক্রেডেনশিয়াল সফলভাবে আপডেট করা হয়েছে।');
    }
}
