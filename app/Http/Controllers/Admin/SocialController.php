<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Auth\Social\GithubAuthService;
use App\Services\Auth\Social\GoogleAuthService;

class SocialController extends Controller
{
    protected GoogleAuthService $googleService;

    protected GithubAuthService $githubService;

    public function __construct(GoogleAuthService $googleService, GithubAuthService $githubService)
    {
        $this->googleService = $googleService;
        $this->githubService = $githubService;

    }

    // redirect to google
    public function googleRedirect()
    {
        return $this->googleService->redirect();
    }

    // callback
    public function googleCallback()
    {
        try {
            $user = $this->googleService->callback();

            return redirect()->intended(route('admin.dashboard'));
        } catch (\Exception $e) {
            // report($e);
            return redirect()->route('admin.login')->with('error', __('admin.google_login_failed').' '.$e->getMessage());
        }
    }

    // ==== GitHub ====
    public function githubRedirect()
    {
        return $this->githubService->redirect();
    }

    public function githubCallback()
    {
        try {
            $user = $this->githubService->callback();

            return redirect()->intended(route('admin.dashboard'));
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', __('admin.github_login_failed').' '.$e->getMessage());
        }
    }
}
