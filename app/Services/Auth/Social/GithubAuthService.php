<?php

namespace App\Services\Auth\Social;

use App\Models\AuthProvider;
use App\Models\User;
use App\Services\Auth\BaseUserAuthService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GithubAuthService extends BaseUserAuthService
{
    /**
     * Redirect المستخدم لصفحة GitHub OAuth
     */
    public function redirect()
    {
        return Socialite::driver('github')
            ->with(['scope' => 'user:email', 'allow_signup' => 'true', 'login' => ''])
            ->redirect();
    }

    /**
     * التعامل مع callback بعد ما المستخدم يرجع من GitHub
     */
    public function callback()
    {
        $githubUser = Socialite::driver('github')->user();

        // هل المستخدم مربوط بالفعل بمزود GitHub؟
        $provider = AuthProvider::where('provider_name', 'github')
            ->where('provider_user_id', $githubUser->getId())
            ->first();

        if ($provider) {
            $user = $provider->user;
        } else {
            // لو في user بنفس الايميل نربطه
            $user = User::where('email', $githubUser->getEmail())->first();

            if (! $user) {
                $user = User::create([
                    'username' => Str::slug($githubUser->getName() ?: $githubUser->getNickname()).'-'.Str::random(4),
                    'slug' => Str::slug($githubUser->getName() ?: $githubUser->getNickname()).'-'.Str::random(6),
                    'email' => $githubUser->getEmail(),
                    'password' => bcrypt(Str::random(12)),
                    'status' => \App\Enums\UserStatus::Active,
                    'type' => \App\Enums\UserType::User,
                    'can_login' => true,
                ]);
            }

            // updateOrCreate record الـ provider
            AuthProvider::updateOrCreate(
                [
                    'provider_name' => 'github',
                    'provider_user_id' => $githubUser->getId(),
                ],
                [
                    'user_id' => $user->id,
                    'provider_access_token' => $githubUser->token,
                    'refresh_token' => $githubUser->refreshToken ?? null,
                    'token_expires_at' => $githubUser->expiresIn
                        ? Carbon::now()->addSeconds($githubUser->expiresIn)
                        : null,
                    'email' => $githubUser->getEmail(),
                    'name' => $githubUser->getName() ?: $githubUser->getNickname(),
                    'avatar' => $githubUser->getAvatar(),
                ]
            );
        }

        // التحقق من صلاحية المستخدم قبل تسجيل الدخول
        $this->assertUserAllowed($user);

        // تسجيل الدخول
        return $this->login($user);
    }
}
