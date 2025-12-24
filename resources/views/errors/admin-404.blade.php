@extends('admin.structure')

@section('title', '404')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="display-1 fw-bold text-danger">404</div>
                <h1 class="h3 mt-3 mb-2">الصفحة غير موجودة</h1>
                <p class="text-muted mb-4">لم نتمكن من إيجاد الصفحة المطلوبة داخل لوحة التحكم.</p>

                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                        <i class="fa-solid fa-gauge me-2"></i>
                        لوحة التحكم
                    </a>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-rotate-left me-2"></i>
                        عودة للخلف
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
