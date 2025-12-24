<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Google\Google2FAService;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TwoFactorController extends Controller
{
    protected Google2FAService $google2FAService;

    public function __construct(Google2FAService $google2FAService)
    {
        $this->google2FAService = $google2FAService;
    }

    /**
     * Show 2FA settings page
     */
    public function index()
    {
        $user = Auth::user();

        // Ensure user has settings record
        if (! $user->userSettings) {
            $user->userSettings()->create([
                'enable_two_factor' => false,
                'google2fa_secret' => null,
            ]);
        }

        $is2FAEnabled = $this->google2FAService->isEnabled($user);

        return view('admin.two-factor.index', compact('is2FAEnabled'));
    }

    /**
     * Show QR code for enabling 2FA
     */
    public function enable()
    {
        $user = Auth::user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('admin.user-settings.two-factor.index');
        }

        $secret = session('2fa_secret') ?? $this->google2FAService->generateSecretKey();
        session(['2fa_secret' => $secret]);

        // Generate QR code
        $companyName = config('app.name', 'Laravel App');
        $qrCode = $this->google2FAService->getQRCode($companyName, $user->email, $secret);

        // convert qrcode to image for display in blade view
        $renderer = new ImageRenderer(
            new RendererStyle(300), // حجم الـ QR
            new SvgImageBackEnd
        );

        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCode);
        $qrCode = $qrCodeSvg;

        return view('admin.two-factor.enable', compact('qrCode', 'secret'));
    }

    /**
     * Verify and activate 2FA
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => __('admin.verification_code_required'),
            'code.size' => __('admin.verification_code_must_be_6_digits'),
        ]);

        $user = Auth::user();
        $secret = session('2fa_secret');

        if (! $secret) {
            return back()->with('error', __('admin.session_expired_try_again'));
        }

        // Verify the code
        if (! $this->google2FAService->verifyCode($secret, $request->code)) {
            return back()->with('error', __('admin.invalid_code_try_again'));
        }

        try {
            DB::beginTransaction();

            // Ensure user has settings
            $settings = $user->userSettings;
            if (! $settings) {
                $settings = $user->userSettings()->create([]);
            }

            // Save the secret and enable 2FA
            $settings->update([
                'google2fa_secret' => $secret,
                'enable_two_factor' => true,
            ]);

            DB::commit();

            // Clear session
            session()->forget('2fa_secret');

            return redirect()->route('admin.user-settings.two-factor.index')
                ->with('status', __('admin.two_factor_enabled_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to enable 2FA', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->with('error', __('admin.error_enabling_two_factor'));
        }
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => __('admin.password_required_to_disable_2fa'),
        ]);

        $user = Auth::user();

        // Verify password
        if (! Auth::guard('web')->attempt(['email' => $user->email, 'password' => $request->password])) {
            return back()->with('error', __('admin.incorrect_password'));
        }

        try {
            DB::beginTransaction();

            if ($user->userSettings) {
                $user->userSettings->update([
                    'google2fa_secret' => null,
                    'enable_two_factor' => false,
                ]);
            }

            DB::commit();

            // Re-authenticate user after password check
            Auth::login($user);

            return redirect()->route('admin.user-settings.two-factor.index')
                ->with('status', __('admin.two_factor_disabled_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to disable 2FA', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->with('error', __('admin.error_disabling_two_factor'));
        }
    }

    /**
     * Show 2FA verification during login
     */
    public function showVerify()
    {
        // Check if there's a pending 2FA session
        if (! session()->has('2fa:user:id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.two-factor.verify');
    }

    /**
     * Verify 2FA code during login
     */
    public function verifyLogin(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => __('admin.verification_code_required'),
            'code.size' => __('admin.verification_code_must_be_6_digits'),
        ]);

        $userId = session('2fa:user:id');
        if (! $userId) {
            return redirect()->route('admin.login')->with('error', __('admin.session_expired'));
        }

        $user = \App\Models\User::find($userId);
        if (! $user || ! $user->userSettings || ! $user->userSettings->google2fa_secret) {
            session()->forget('2fa:user:id');

            return redirect()->route('admin.login')->with('error', __('admin.verification_error'));
        }

        // Verify the code
        if (! $this->google2FAService->verifyCode($user->userSettings->google2fa_secret, $request->code)) {
            return back()->with('error', __('admin.invalid_code_try_again'));
        }

        // Log the user in
        Auth::login($user, session('2fa:remember', false));
        session()->forget(['2fa:user:id', '2fa:remember']);
        session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function regenerate()
    {
        // امسح السيكرت القديم
        session()->forget('2fa_secret');

        // generate جديد وإحفظه
        $secret = $this->google2FAService->generateSecretKey();
        session(['2fa_secret' => $secret]);

        
        return redirect()->route('admin.user-settings.two-factor.enable')
            ->with('message', __('admin.new_code_generated_successfully'));
        // return back()->with('message', 'تم توليد رمز جديد بنجاح ✅');
    }
}
