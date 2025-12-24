@extends('admin.structure')

@section('title', __('admin.register'))

@section('content')
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 text-center mb-4">{{ __('admin.create_new_account') }}</h1>

                        {{-- Session status / error messages --}}
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                        @endif

                        {{-- General validation errors (non field-specific) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.register.submit') }}" method="POST" novalidate>
                            @csrf

                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" placeholder="username"
                                            value="{{ old('username') }}" required>
                                        <label for="username">{{ __('admin.username') }}</label>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" placeholder="name@example.com"
                                            value="{{ old('email') }}" required>
                                        <label for="email">{{ __('admin.email') }}</label>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text"
                                            class="form-control @error('mobile_number') is-invalid @enderror"
                                            id="mobile_number" name="mobile_number" placeholder="05xxxxxxxx"
                                            value="{{ old('mobile_number') }}" required>
                                        <label for="mobile_number">{{ __('admin.mobile_number') }}</label>
                                        @error('mobile_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                            id="first_name" name="first_name" placeholder="الاسم الأول"
                                            value="{{ old('first_name') }}" required>
                                        <label for="first_name">{{ __('admin.first_name') }}</label>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name" name="last_name" placeholder="اسم العائلة"
                                            value="{{ old('last_name') }}" required>
                                        <label for="last_name">{{ __('admin.last_name') }}</label>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="••••••••" required>
                                        <label for="password">{{ __('admin.password') }}</label>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="••••••••" required>
                                        <label for="password_confirmation">{{ __('admin.confirm_password') }}</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit"
                                        class="btn btn-primary w-100">{{ __('admin.create_account') }}</button>
                                </div>

                                <div class="col-12 text-center">
                                    @if (Route::has('admin.login'))
                                        <span class="small">{{ __('admin.have_account') }} <a class="text-decoration-none"
                                                href="{{ route('admin.login') }}">{{ __('admin.login') }}</a></span>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
