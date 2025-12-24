<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Exceptions\EmailNotVerifiedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Profile;
use App\Models\User;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $loginService = new LoginService;
        $identifier = $request->identifier;
        $password = $request->password;
        $remember = (bool) $request->remember_me;

        try {

            $user = $loginService->attemptCredentialsLogin($identifier, $password, $request, $remember);

            // Check if 2FA verification is required
            if (! $user && $request->session()->has('2fa:user:id')) {
                return redirect()->route('admin.two-factor.verify');
            }

            if (! $user) {
                return back()->withErrors(['login' => __('admin.invalid_credentials')]);
            }

            return redirect()->intended(route('admin.dashboard'));

        } catch (EmailNotVerifiedException $e) {
            return redirect()
                ->route('admin.verification-notice')
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {

            return back()->withErrors(['login' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('admin.login');
    }

    // Register
    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        // Validate incoming registration data
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'mobile_number' => ['required', 'string', 'max:50', 'unique:users,mobile_number'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
        ], [
            'username.required' => __('admin.username_required'),
            'username.unique' => __('admin.username_already_taken'),
            'email.required' => __('admin.email_required'),
            'email.email' => __('admin.email_invalid'),
            'email.unique' => __('admin.email_already_taken'),
            'mobile_number.required' => __('admin.mobile_required'),
            'mobile_number.unique' => __('admin.mobile_already_taken'),
            'password.required' => __('admin.password_required'),
            'password.confirmed' => __('admin.password_confirmation_mismatch'),
            'password.min' => __('admin.password_min_8_chars'),
            'first_name.required' => __('admin.first_name_required'),
            'last_name.required' => __('admin.last_name_required'),
        ]);

        try {
            DB::beginTransaction();

            // Generate a unique slug based on username
            $baseSlug = Str::slug($validated['username']);
            $slug = $baseSlug;
            $counter = 1;
            while (User::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter++;
            }

            // Create user
            $user = User::create([
                'username' => $validated['username'],
                'slug' => $slug,
                'email' => $validated['email'],
                'mobile_number' => $validated['mobile_number'],
                'password' => Hash::make($validated['password']),
                'status' => UserStatus::PENDING,
                'type' => UserType::USER,
                'can_login' => true,
            ]);

            // Create profile
            $user->profile()->create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
            ]);

            DB::commit();

            // Store for verification flow and redirect to notice (auto-sends email with throttle)
            $request->session()->put('verification_user_id', $user->id);

            return redirect()->route('admin.verification-notice')
                ->with('status', __('admin.account_created_verify_email'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Registration failed', ['error' => $e->getMessage()]);

            return back()->with('error', __('admin.registration_failed'))
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    // Password Reset
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        // 1) Validate email input
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => __('admin.email_required'),
            'email.email' => __('admin.email_invalid'),
            'email.exists' => __('admin.user_not_found_email'),
        ]);

        $email = $data['email'];

        // 2) Generate a secure random token and store hashed token
        $plainToken = Str::random(64);
        $hashedToken = Hash::make($plainToken);

        // Ensure table password_reset_tokens exists. Laravel 10 default: email, token, created_at
        // Remove previous tokens for this email to avoid clutter
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $hashedToken,
            'created_at' => now(),
        ]);

        // 3) Send email with reset link (include plain token and email)
        $resetUrl = route('admin.reset-password', ['token' => $plainToken]).'?email='.urlencode($email);

        Mail::send('emails.admin.reset-password', ['resetUrl' => $resetUrl, 'email' => $email], function ($message) use ($email) {
            $message->to($email)
                ->subject(__('admin.reset_password_notification'));
        });

        return back()->with('status', __('admin.reset_link_sent_email'));
    }

    // Reset Password
    public function showResetPasswordForm(string $token)
    {
        // The email may arrive via query string
        $email = request()->query('email');

        return view('admin.auth.reset-password', compact('token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        // 1) Validate input
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'email.required' => __('admin.email_required'),
            'email.email' => __('admin.email_invalid'),
            'email.exists' => __('admin.user_not_found_email'),
            'password.required' => __('admin.password_required'),
            'password.confirmed' => __('admin.password_confirmation_mismatch'),
            'password.min' => __('admin.password_min_8_chars'),
        ]);

        $email = $validated['email'];
        $plainToken = $validated['token'];

        // 2) Fetch token record and verify
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (! $record) {
            return back()->withErrors(['email' => __('admin.invalid_or_expired_token')])->withInput($request->except('password', 'password_confirmation'));
        }

        // Token expiry (e.g., 60 minutes)
        $expiresAt = \Carbon\Carbon::parse($record->created_at)->addMinutes(60);
        if (now()->greaterThan($expiresAt)) {
            // Cleanup expired token
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return back()->withErrors(['email' => __('admin.reset_link_expired')])->withInput($request->except('password', 'password_confirmation'));
        }

        if (! Hash::check($plainToken, $record->token)) {
            return back()->withErrors(['email' => __('admin.invalid_token')])->withInput($request->except('password', 'password_confirmation'));
        }

        // 3) Update user password
        $user = User::where('email', $email)->first();
        if (! $user) {
            return back()->withErrors(['email' => __('admin.user_not_found')])->withInput($request->except('password', 'password_confirmation'));
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        // 4) Delete token
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // 5) Redirect to login with status
        return redirect()->route('admin.login')->with('status', __('admin.password_reset_success'));
    }

    // Email Verification
    public function verificationNotice(Request $request)
    {
        // Try to get the user needing verification from session or current auth
        $user = null;
        if ($request->session()->has('verification_user_id')) {
            $user = User::find($request->session()->get('verification_user_id'));
        }
        if (! $user && Auth::check()) {
            $user = Auth::user();
        }

        // Auto-send verification email with cooldown when landing on this page
        if ($user && is_null($user->email_verified_at)) {
            $key = 'verify_resend_user_'.$user->id;
            if (! Cache::has($key)) {
                Cache::put($key, true, now()->addSeconds(60));

                $url = URL::temporarySignedRoute(
                    'admin.verification-verify',
                    now()->addMinutes(60),
                    ['id' => $user->id, 'hash' => sha1($user->email)]
                );

                try {
                    Mail::send('emails.admin.verify-email', ['verifyUrl' => $url, 'user' => $user], function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject(__('admin.email_verification'));
                    });
                    session()->flash('status', __('admin.verification_email_sent'));
                } catch (\Throwable $e) {
                    Log::error('Failed to send verification email', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                    session()->flash('error', __('admin.verification_email_failed'));
                }
            } else {
                session()->flash('status', __('admin.verification_email_throttled'));
            }
        }

        return view('admin.auth.verification-notice', compact('user'));
    }

    public function verificationVerify(Request $request, $id, $hash)
    {
        // Validate signature and expiry
        if (! URL::hasValidSignature($request)) {
            return redirect()->route('admin.verification-notice')->with('error', __('admin.verification_link_invalid'));
        }

        $user = User::find($id);
        if (! $user) {
            return redirect()->route('admin.verification-notice')->with('error', __('admin.user_not_found'));
        }

        // Confirm hash matches email
        if (! hash_equals((string) $hash, sha1($user->email))) {
            return redirect()->route('admin.verification-notice')->with('error', __('admin.verification_link_invalid'));
        }

        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
        }

        // Optionally clear the session flag
        $request->session()->forget('verification_user_id');

        return view('admin.auth.verification-verify', ['user' => $user]);
    }

    public function sendVerificationNotification(Request $request)
    {
        // Determine target user
        $user = null;
        if ($request->session()->has('verification_user_id')) {
            $user = User::find($request->session()->get('verification_user_id'));
        }
        if (! $user && Auth::check()) {
            $user = Auth::user();
        }
        if (! $user) {
            return back()->with('error', __('admin.no_user_to_verify'));
        }

        if (! is_null($user->email_verified_at)) {
            return redirect()->route('admin.dashboard');
        }

        // Throttle: allow once per 60 seconds per user
        $key = 'verify_resend_user_'.$user->id;
        if (Cache::has($key)) {
            return back()->with('status', __('admin.verification_email_throttled'));
        }

        Cache::put($key, true, now()->addSeconds(60));

        // Build signed URL valid for 60 minutes
        $url = URL::temporarySignedRoute(
            'admin.verification-verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Send email
        try {
            Mail::send('emails.admin.verify-email', ['verifyUrl' => $url, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject(__('admin.email_verification'));
            });

            return back()->with('status', __('admin.verification_email_sent'));
        } catch (\Throwable $e) {
            Log::error('Failed to send verification email (manual resend)', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return back()->with('error', __('admin.verification_email_failed'));
        }
    }

    public function dashboard()
    {
        // Statistics
        $users_count = User::count();
        $patients_count = User::where('type', UserType::PATIENT)->count();
        $doctors_count = User::where('type', UserType::DOCTOR)->count();
        $services_count = \App\Models\Service::count();

        $startOfMonth = now()->startOfMonth();

        $appointments_count = \App\Models\Appointment::whereDate('date', '>=', $startOfMonth)->count();
        $completed_appointments_count = \App\Models\Appointment::where('status', \App\Enums\AppointmentStatus::COMPLETED)
            ->whereDate('date', '>=', $startOfMonth)
            ->count();

        $visits_count = \App\Models\Visit::whereDate('visit_date', '>=', $startOfMonth)->count();
        $completed_visits_count = \App\Models\Visit::where('status', \App\Enums\VisitStatus::Completed)
            ->whereDate('visit_date', '>=', $startOfMonth)
            ->count();

        $invoices_count = \App\Models\Invoice::whereDate('invoice_date', '>=', $startOfMonth)->count();
        $total_revenue = \App\Models\Invoice::where('status', \App\Enums\InvoiceStatus::PAID)
            ->whereDate('invoice_date', '>=', $startOfMonth)
            ->sum('final_amount');

        // Recent Activity
        $recent_visits = \App\Models\Visit::with(['patient.profile', 'doctor.profile'])
            ->whereDate('visit_date', now())
            ->where('status', \App\Enums\VisitStatus::Pending)
            ->latest()
            ->take(10)
            ->get();

        $recent_invoices = \App\Models\Invoice::with('patient')
            ->whereDate('invoice_date', now())
            ->where('status', \App\Enums\InvoiceStatus::PAID)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'users_count',
            'patients_count',
            'doctors_count',
            'services_count',
            'visits_count',
            'completed_visits_count',
            'invoices_count',
            'total_revenue',
            'recent_visits',
            'recent_invoices'
        ));
    }
}
