<?php

namespace App\Services\Auth\Social;

use App\Models\AuthProvider;
use App\Models\User;
use App\Services\Auth\BaseUserAuthService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthService extends BaseUserAuthService
{
    /**
     * Redirect to Google consent screen.
     */
    public function redirect()
    {
        return Socialite::driver('google')
            // ->scopes(['https://www.googleapis.com/auth/drive.file', 'email', 'profile'])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent select_account',
            ])
            ->redirect();
    }

    /**
     * Handle callback from Google.
     * بيرجع ال User لو كل حاجة تمام وإلا بيرمي Exception أو null.
     */
    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        // هل الـ provider موجود قبل كده؟
        $provider = AuthProvider::where('provider_name', 'google')
            ->where('provider_user_id', $googleUser->getId())
            ->first();

        if ($provider) {
            $user = $provider->user;
        } else {
            // لو في user بنفس الايميل نربطه
            $user = User::where('email', $googleUser->getEmail())->first();

            if (! $user) {
                $user = User::create([
                    'username' => Str::slug($googleUser->getName()) . '-' . Str::random(4),
                    'slug' => Str::slug($googleUser->getName()) . '-' . Str::random(6),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(12)),
                    'status' => \App\Enums\UserStatus::Active,
                    'type' => \App\Enums\UserType::User,
                    'can_login' => true,
                ]);
            }

            // updateOrCreate الـ provider record
            AuthProvider::updateOrCreate(
                [
                    'provider_name' => 'google',
                    'provider_user_id' => $googleUser->getId(),
                ],
                [
                    'user_id' => $user->id,
                    'provider_access_token' => $googleUser->token,
                    'refresh_token' => $googleUser->refreshToken ?? null,
                    'token_expires_at' => $googleUser->expiresIn
                        ? Carbon::now()->addSeconds($googleUser->expiresIn)
                        : null,
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName(),
                    'avatar' => $googleUser->getAvatar(),
                ]
            );
        }

        // لو كل حاجة تمام نعمل login
        return $this->login($user);
    }
}
