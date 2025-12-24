@extends('admin.structure')

@section('title', __('admin.two_factor_authentication'))

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-lock me-2"></i>
                            {{ __('admin.two_factor_authentication') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        {{-- Session messages --}}
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- 2FA Status --}}
                        <div class="text-center mb-4">
                            @if ($is2FAEnabled)
                                <div class="badge bg-success fs-6 px-4 py-2 mb-3">
                                    <i class="bi bi-shield-check me-2"></i>
                                    {{ __('admin.2fa_enabled') }}
                                </div>
                            @else
                                <div class="badge bg-warning text-dark fs-6 px-4 py-2 mb-3">
                                    <i class="bi bi-shield-exclamation me-2"></i>
                                    {{ __('admin.2fa_disabled') }}
                                </div>
                            @endif
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>{{ __('admin.what_is_2fa') }}</strong>
                            <p class="mb-0 mt-2 small">
                                {{ __('admin.2fa_description') }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        @if ($is2FAEnabled)
                            {{-- Disable 2FA Form --}}
                            <form action="{{ route('admin.user-settings.two-factor.disable') }}" method="POST"
                                class="mt-4">
                                @csrf
                                <div class="alert alert-warning" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>{{ __('admin.warning_disable_2fa') }}</strong>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="••••••••" required>
                                    <label for="password">{{ __('admin.password') }}</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-shield-x me-2"></i>
                                    {{ __('admin.disable_2fa') }}
                                </button>
                            </form>
                        @else
                            {{-- Enable 2FA Button --}}
                            <div class="text-center mt-4">
                                <a href="{{ route('admin.user-settings.two-factor.enable') }}"
                                    class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-shield-plus me-2"></i>
                                    {{ __('admin.enable_2fa') }}
                                </a>
                            </div>

                            <div class="mt-3 text-center">
                                <small class="text-muted">
                                    {{ __('admin.google_authenticator_required') }}
                                </small>
                            </div>
                        @endif

                        {{-- Back to Dashboard --}}
                        <div class="text-center mt-4 pt-3 border-top">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-right me-2"></i>
                                {{ __('admin.back_to_dashboard') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
