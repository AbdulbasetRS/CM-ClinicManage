@extends('admin.structure')

@section('title', __('admin.reset_password_title'))

@section('content')
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 text-center mb-4">{{ __('admin.set_new_password') }}</h1>

                        {{-- Session status / error messages --}}
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.reset-password.submit') }}" method="POST" novalidate>
                            @csrf
                            <input type="hidden" name="token" value="{{ $token ?? request('token') }}">

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="name@example.com"
                                    value="{{ old('email', $email ?? request('email')) }}" required>
                                <label for="email">{{ __('admin.email') }}</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="••••••••" required>
                                <label for="password">{{ __('admin.new_password') }}</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="••••••••" required>
                                <label for="password_confirmation">{{ __('admin.confirm_password') }}</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">{{ __('admin.update_password') }}</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('admin.login') }}"
                                class="small text-decoration-none">{{ __('admin.back_to_login') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
