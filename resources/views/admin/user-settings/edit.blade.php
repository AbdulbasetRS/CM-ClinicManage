@extends('admin.structure')

@section('title', __('admin.general_settings'))

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ __('admin.general_settings') }}</h1>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.edit') }}
                            {{ __('admin.general_settings') }}</h6>
                    </div>
                    <div class="card-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.user-settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <ul class="nav nav-tabs mb-4" id="settingsTab" role="tablist">
                                {{-- <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="appearance-tab" data-bs-toggle="tab"
                                        data-bs-target="#appearance" type="button" role="tab"
                                        aria-controls="appearance" aria-selected="true">
                                        <i class="fa-solid fa-palette me-2"></i>{{ __('admin.appearance') }}
                                    </button>
                                </li> --}}
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="localization-tab" data-bs-toggle="tab"
                                        data-bs-target="#localization" type="button" role="tab"
                                        aria-controls="localization" aria-selected="false">
                                        <i class="fa-solid fa-globe me-2"></i>{{ __('admin.localization') }}
                                    </button>
                                </li>
                                {{-- <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="notifications-tab" data-bs-toggle="tab"
                                        data-bs-target="#notifications" type="button" role="tab"
                                        aria-controls="notifications" aria-selected="false">
                                        <i class="fa-solid fa-bell me-2"></i>{{ __('admin.notifications') }}
                                    </button>
                                </li> --}}
                            </ul>

                            <div class="tab-content" id="settingsTabContent">
                                {{-- Appearance Tab --}}
                                <div class="tab-pane fade" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="language" class="form-label">{{ __('admin.language') }}</label>
                                            <select class="form-select" id="language" name="language">
                                                <option value="ar"
                                                    {{ old('language', $settings->language) == 'ar' ? 'selected' : '' }}>
                                                    العربية</option>
                                                <option value="en"
                                                    {{ old('language', $settings->language) == 'en' ? 'selected' : '' }}>
                                                    English</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="theme" class="form-label">{{ __('admin.theme') }}</label>
                                            <select class="form-select" id="theme" name="theme">
                                                <option value="light"
                                                    {{ old('theme', $settings->theme) == 'light' ? 'selected' : '' }}>
                                                    {{ __('admin.light') }}</option>
                                                <option value="dark"
                                                    {{ old('theme', $settings->theme) == 'dark' ? 'selected' : '' }}>
                                                    {{ __('admin.dark') }}</option>
                                                <option value="system"
                                                    {{ old('theme', $settings->theme) == 'system' ? 'selected' : '' }}>
                                                    {{ __('admin.system') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="font_size" class="form-label">{{ __('admin.font_size') }}</label>
                                            <select class="form-select" id="font_size" name="font_size">
                                                <option value="small"
                                                    {{ old('font_size', $settings->font_size) == 'small' ? 'selected' : '' }}>
                                                    {{ __('admin.small') }}</option>
                                                <option value="medium"
                                                    {{ old('font_size', $settings->font_size) == 'medium' ? 'selected' : '' }}>
                                                    {{ __('admin.medium') }}</option>
                                                <option value="large"
                                                    {{ old('font_size', $settings->font_size) == 'large' ? 'selected' : '' }}>
                                                    {{ __('admin.large') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Localization Tab --}}
                                <div class="tab-pane fade show active" id="localization" role="tabpanel"
                                    aria-labelledby="localization-tab">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="timezone" class="form-label">{{ __('admin.timezone') }}</label>
                                            <select class="form-select select2" id="timezone" name="timezone">
                                                @foreach (\DateTimeZone::listIdentifiers() as $timezone)
                                                    <option value="{{ $timezone }}"
                                                        {{ old('timezone', $settings->timezone) == $timezone ? 'selected' : '' }}>
                                                        {{ $timezone }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="date_format"
                                                class="form-label">{{ __('admin.date_format') }}</label>
                                            <select class="form-select" id="date_format" name="date_format">
                                                <option value="Y-m-d"
                                                    {{ old('date_format', $settings->date_format) == 'Y-m-d' ? 'selected' : '' }}>
                                                    YYYY-MM-DD ({{ date('Y-m-d') }})</option>
                                                <option value="d-m-Y"
                                                    {{ old('date_format', $settings->date_format) == 'd-m-Y' ? 'selected' : '' }}>
                                                    DD-MM-YYYY ({{ date('d-m-Y') }})</option>
                                                <option value="m/d/Y"
                                                    {{ old('date_format', $settings->date_format) == 'm/d/Y' ? 'selected' : '' }}>
                                                    MM/DD/YYYY ({{ date('m/d/Y') }})</option>
                                                <option value="d/m/Y"
                                                    {{ old('date_format', $settings->date_format) == 'd/m/Y' ? 'selected' : '' }}>
                                                    DD/MM/YYYY ({{ date('d/m/Y') }})</option>
                                                <option value="M d, Y"
                                                    {{ old('date_format', $settings->date_format) == 'M d, Y' ? 'selected' : '' }}>
                                                    Mon DD, YYYY ({{ date('M d, Y') }})</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="time_format"
                                                class="form-label">{{ __('admin.time_format') }}</label>
                                            <select class="form-select" id="time_format" name="time_format">
                                                <option value="24h"
                                                    {{ old('time_format', $settings->time_format) == '24h' ? 'selected' : '' }}>
                                                    {{ __('admin.24h') }} ({{ date('H:i') }})</option>
                                                <option value="12h"
                                                    {{ old('time_format', $settings->time_format) == '12h' ? 'selected' : '' }}>
                                                    {{ __('admin.12h') }} ({{ date('h:i A') }})</option>
                                            </select>
                                        </div>
                                        {{-- <div class="col-md-6 mb-3">
                                            <label for="currency" class="form-label">{{ __('admin.currency') }}</label>
                                            <select class="form-select" id="currency" name="currency">
                                                <option value="EGP"
                                                    {{ old('currency', $settings->currency) == 'EGP' ? 'selected' : '' }}>
                                                    EGP</option>
                                                <option value="USD"
                                                    {{ old('currency', $settings->currency) == 'USD' ? 'selected' : '' }}>
                                                    USD</option>
                                                <option value="SAR"
                                                    {{ old('currency', $settings->currency) == 'SAR' ? 'selected' : '' }}>
                                                    SAR</option>
                                                <option value="EUR"
                                                    {{ old('currency', $settings->currency) == 'EUR' ? 'selected' : '' }}>
                                                    EUR</option>
                                            </select>
                                        </div> --}}
                                    </div>
                                </div>

                                {{-- Notifications Tab --}}
                                <div class="tab-pane fade" id="notifications" role="tabpanel"
                                    aria-labelledby="notifications-tab">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="notifications_email"
                                                    name="notifications_email" value="1"
                                                    {{ old('notifications_email', $settings->notifications_email) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="notifications_email">{{ __('admin.notifications_email') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="notifications_sound"
                                                    name="notifications_sound" value="1"
                                                    {{ old('notifications_sound', $settings->notifications_sound) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="notifications_sound">{{ __('admin.notifications_sound') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="login_alerts"
                                                    name="login_alerts" value="1"
                                                    {{ old('login_alerts', $settings->login_alerts) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="login_alerts">{{ __('admin.login_alerts') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save me-1"></i> {{ __('admin.save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
@endsection
