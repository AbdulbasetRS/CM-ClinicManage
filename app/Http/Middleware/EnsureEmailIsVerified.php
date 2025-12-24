<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();
        if (is_null($user->email_verified_at)) {
            // Save for resend button
            $request->session()->put('verification_user_id', $user->id);
            return redirect()->route('admin.verification-notice')
                ->with('error', 'حسابك غير مفعل. رجاءً تحقق من بريدك الإلكتروني لتفعيل الحساب.');
        }

        return $next($request);
    }
}
