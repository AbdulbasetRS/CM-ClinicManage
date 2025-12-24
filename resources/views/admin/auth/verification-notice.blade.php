@extends('admin.structure')

@section('title', __('admin.email_verification_required'))

@section('content')
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4 text-center">
                        <h1 class="h4 mb-3">{{ __('admin.email_verification_required') }}</h1>
                        <p class="text-muted">{{ __('admin.account_not_verified_check_email') }}</p>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('admin.verification-notification.submit') }}" method="POST"
                            class="d-inline-block">
                            @csrf
                            <button type="submit"
                                class="btn btn-primary">{{ __('admin.resend_verification_email') }}</button>
                        </form>

                        <div class="mt-3">
                            <a class="small text-decoration-none"
                                href="{{ route('admin.login') }}">{{ __('admin.back_to_login') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
