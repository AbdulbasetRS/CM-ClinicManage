<?php

namespace App\Services\Google;

use PragmaRX\Google2FA\Google2FA;


class Google2FAService
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a new secret key for Google 2FA
     *
     * @return string
     */
    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate QR Code URL for Google Authenticator
     *
     * @param string $companyName
     * @param string $email
     * @param string $secret
     * @return string
     */
    public function getQRCode(string $companyName, string $email, string $secret): string
    {
        return $this->google2fa->getQRCodeUrl(
            $companyName,
            $email,
            $secret
        );
    }

    /**
     * Verify the code provided by the user
     *
     * @param string $secret
     * @param string $code
     * @return bool
     */
    public function verifyCode(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey($secret, $code);
    }

    /**
     * Check if the user has 2FA enabled
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function isEnabled($user): bool
    {
        return $user->userSettings && $user->userSettings->enable_two_factor && !empty($user->userSettings->google2fa_secret);
    }
}
