@extends('frontend.structure')

@section('title', '404')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="display-1 fw-bold text-danger">404</div>
                <h1 class="h3 mt-3 mb-2">{{ app()->getLocale() === 'ar' ? 'الصفحة غير موجودة' : 'Page Not Found' }}</h1>
                <p class="text-muted mb-4">
                    {{ app()->getLocale() === 'ar' ? 'الرابط الذي طلبته غير متاح.' : 'The page you are looking for could not be found.' }}
                </p>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="{{ route('frontend.home') }}" class="btn btn-primary">
                        {{ app()->getLocale() === 'ar' ? 'الصفحة الرئيسية' : 'Home' }}
                    </a>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        {{ app()->getLocale() === 'ar' ? 'عودة للخلف' : 'Go Back' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
