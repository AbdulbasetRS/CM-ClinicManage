@extends('admin.structure')

@section('title', __('admin.change_password'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-lock"></i> {{ __('admin.change_password') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.user-settings.change-password.update') }}">
                            @csrf
                            @method('PUT')

                            {{-- Current Password --}}
                            <div class="mb-4">
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-key text-muted"></i> {{ __('admin.current_password') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        id="current_password" name="current_password" required
                                        autocomplete="current-password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye" id="current_password-icon"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-key text-muted"></i> {{ __('admin.new_password') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="password-icon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> {{ __('admin.password_requirements') }}
                                </small>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-key text-muted"></i> {{ __('admin.confirm_new_password') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('admin.change_password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Security Tips --}}
                <div class="card mt-3 border-info">
                    <div class="card-body">
                        <h6 class="card-title text-info">
                            <i class="fas fa-shield-alt"></i> {{ __('admin.security_tips') }}
                        </h6>
                        <ul class="small mb-0">
                            <li>{{ __('admin.security_tip_1') }}</li>
                            <li>{{ __('admin.security_tip_2') }}</li>
                            <li>{{ __('admin.security_tip_3') }}</li>
                            <li>{{ __('admin.security_tip_4') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main.script')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
