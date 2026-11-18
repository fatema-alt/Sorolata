<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Enums\AdminRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('account.index');
        }

        return view('auth.customer-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => 'required|string|max:255',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $loginInput = trim($validated['login']);
        $throttleKey = 'customer_login_' . Str::lower($loginInput) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withInput($request->only('login'))
                ->withErrors([
                    'login' => "অতিরিক্ত ভুল প্রচেষ্টার কারণে সাময়িকভাবে লগইন বন্ধ রয়েছে। অনুগ্রহ করে {$seconds} সেকেন্ড পর আবার চেষ্টা করুন।",
                ]);
        }

        // Determine if login identifier is email or phone
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);
        $credentials = [
            $isEmail ? 'email' : 'phone' => $loginInput,
            'password' => $validated['password'],
        ];

        $remember = (bool) ($validated['remember'] ?? false);

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->intended(route('account.index'))
                ->with('success', 'স্বাগতম! আপনি সফলভাবে লগইন করেছেন।');
        }

        RateLimiter::hit($throttleKey, 300);

        return back()
            ->withInput($request->only('login'))
            ->withErrors([
                'login' => 'প্রদত্ত তথ্য আমাদের রেকর্ডের সাথে মিলছে না। অনুগ্রহ করে পুনরায় যাচাই করুন।',
            ]);
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('account.index');
        }

        return view('auth.customer-register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => AdminRole::CUSTOMER,
        ]);

        \App\Models\CustomerProfile::create([
            'user_id' => $user->id,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('account.index')
            ->with('success', 'অভিনন্দন! আপনার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে।');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'আপনি সফলভাবে লগআউট হয়েছেন।');
    }
}
