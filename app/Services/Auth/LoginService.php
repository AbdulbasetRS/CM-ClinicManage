<?php

namespace App\Services\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use App\Services\Google\Google2FAService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService extends BaseUserAuthService
{
    public function attemptCredentialsLogin(string $identifier, string $password, Request $request, bool $remember = false): ?User
    {
        $fieldType = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email'
            : (is_numeric($identifier) ? 'mobile_number' : 'username');

        $user = User::where($fieldType, $identifier)->first();

        if (! $user) {
            throw new InvalidCredentialsException('invalid_credentials');
        }

        // Check status + type
        $this->assertUserAllowed($user);

        // Verify password without logging in
        if (! Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException('invalid_credentials');
        }

        // // Check if 2FA is enabled
        // $google2FAService = new Google2FAService();
        // if ($google2FAService->isEnabled($user)) {
        //     // Store user ID and remember preference in session for 2FA verification
        //     $request->session()->put('2fa:user:id', $user->id);
        //     $request->session()->put('2fa:remember', $remember);
            
        //     // Return null to indicate 2FA verification is needed
        //     // The controller will check this and redirect to 2FA verify page
        //     return null;
        // }

        if ($user->hasTwoFactorEnabled()) {
              // Store user ID and remember preference in session for 2FA verification
            $request->session()->put('2fa:user:id', $user->id);
            $request->session()->put('2fa:remember', $remember);
            
            // Return null to indicate 2FA verification is needed
            // The controller will check this and redirect to 2FA verify page
            return null;
        }


        // No 2FA, proceed with normal login
        if (Auth::attempt([$fieldType => $identifier, 'password' => $password], $remember)) {
            // regenerate session
            $request->session()->regenerate();

            return $user;
        }

        throw new InvalidCredentialsException('invalid_credentials');
    }
}
