@extends('admin.structure')

@section('title', __('admin.two_factor_verification'))

@section('content')
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="bi bi-shield-lock text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h1 class="h4 mb-2">{{ __('admin.two_factor_verification') }}</h1>
                            <p class="text-muted small">
                                {{ __('admin.enter_code_from_app') }}
                            </p>
                        </div>

                        {{-- Session status / error messages --}}
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- General validation errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.two-factor.verify.login') }}" method="POST" novalidate>
                            @csrf

                            <div class="form-floating mb-4">
                                <input type="text"
                                    class="form-control form-control-lg text-center font-monospace @error('code') is-invalid @enderror"
                                    id="code" name="code" placeholder="000000" maxlength="6" pattern="[0-9]{6}"
                                    autocomplete="off" required autofocus>
                                <label for="code">{{ __('admin.verification_code_label') }}</label>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ __('admin.verify') }}
                            </button>
                        </form>

                        <div class="text-center">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                {{ __('admin.open_google_authenticator') }}
                            </small>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <a href="{{ route('admin.login') }}" class="text-decoration-none small">
                                <i class="bi bi-arrow-right me-1"></i>
                                {{ __('admin.back_to_login') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Help Card --}}
                <div class="card mt-3">
                    <div class="card-body p-3">
                        <h6 class="card-title mb-2">
                            <i class="bi bi-question-circle me-2"></i>
                            {{ __('admin.cant_access_app') }}
                        </h6>
                        <p class="card-text small text-muted mb-0">
                            {{ __('admin.lost_access_support') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-format code input to only allow numbers
            document.getElementById('code').addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
            });

            // Auto-submit when 6 digits are entered (optional)
            document.getElementById('code').addEventListener('input', function(e) {
                if (this.value.length === 6) {
                    // Optional: Auto-submit form
                    // this.form.submit();
                }
            });
        </script>
    @endpush
@endsection
