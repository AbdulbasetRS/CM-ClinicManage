<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Google\GoogleService;

class GoogleController extends Controller
{
    public function redirectToGoogle(GoogleService $google)
    {
        return $google->auth()->redirect();
    }

    public function handleGoogleCallback(GoogleService $google)
    {
        $user = $google->auth()->handleCallback();

        if (! $user) {
            return redirect()->route('admin.login')->with('error', 'فشل تسجيل الدخول باستخدام Google');
        }

        return redirect()->route('admin.dashboard');
    }
}
