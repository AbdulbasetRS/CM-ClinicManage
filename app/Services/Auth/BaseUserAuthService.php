<?php

namespace App\Services\Auth;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Exceptions\EmailNotVerifiedException;
use App\Exceptions\InactiveUserException;
use App\Exceptions\NotAllowedToLoginException;
use App\Exceptions\UserTypeNotAllowedException;
use App\Models\User;
use Auth;

class BaseUserAuthService
{
    protected array $allowedTypes = [
        UserType::USER,
        UserType::ADMIN,
        UserType::IT,
        UserType::TESTER,
        UserType::EMPLOYEE,
        UserType::DOCTOR,
        UserType::ASSISTANT,
        UserType::RECEPTIONIST,
        // UserType::PATIENT,
    ];

    public function assertUserAllowed(User $user): void
    {
        if ($user->status !== UserStatus::ACTIVE) {
            throw new InactiveUserException('inactive_user');
        }

        if (! in_array($user->type, $this->allowedTypes, true)) {
            throw new UserTypeNotAllowedException('user_type_not_allowed');
        }

        if (is_null($user->email_verified_at)) {
            session()->put('verification_user_id', $user->id);
            throw new EmailNotVerifiedException($user->id);
        }

        if (! $user->can_login) {
            throw new NotAllowedToLoginException('not_allowed_to_login');
        }
    }

    public function login(User $user, bool $remember = false): User
    {
        $this->assertUserAllowed($user);

        Auth::login($user, $remember);
        session()->regenerate();

        return $user;
    }
}
