@extends('admin.structure')

@section('title', __('admin.create_new_user'))

@section('content')
    <div class="container">
        {{-- Alerts --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="accordion" id="userAccordion">
                {{-- User Info Accordion --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingUser">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseUser" aria-expanded="true" aria-controls="collapseUser">
                            {{ __('admin.user_info') }}
                        </button>
                    </h2>
                    <div id="collapseUser" class="accordion-collapse collapse show" aria-labelledby="headingUser"
                        data-bs-parent="#userAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">{{ __('admin.username') }}</label>
                                        <input type="text" name="username" id="username" value="{{ old('username') }}"
                                            class="form-control @error('username') is-invalid @endif" required>
                                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">{{ __('admin.email') }}</label>
                                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                                class="form-control @error('email') is-invalid @endif" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">{{ __('admin.password') }}</label>
                                                <input type="password" name="password" id="password"
                                                    class="form-control @error('password') is-invalid @endif" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password_confirmation"
                                                        class="form-label">{{ __('admin.confirm_password') }}</label>
                                                    <input type="password" name="password_confirmation"
                                                        id="password_confirmation" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="mobile_number"
                                                        class="form-label">{{ __('admin.mobile_number') }}</label>
                                                    <input type="text" name="mobile_number" id="mobile_number"
                                                        value="{{ old('mobile_number') }}"
                                                        class="form-control @error('mobile_number') is-invalid @endif">
                                    @error('mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- More User Fields --}}
                            <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="status"
                                                            class="form-label">{{ __('admin.status') }}</label>
                                                        <select name="status" id="status"
                                                            class="form-select @error('status') is-invalid @endif" required>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}" @selected(old('status') == $status)>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="type"
                                                                    class="form-label">{{ __('admin.type') }}</label>
                                                                <select name="type" id="type"
                                                                    class="form-select @error('type') is-invalid @endif" required>
                                        @foreach ($types as $type)
                                            <option value="{{ $type }}" @selected(old('type') == $type)>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                                                                    {{-- User Basic Info --}}
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="national_id"
                                                                                class="form-label">{{ __('admin.national_id') }}</label>
                                                                            <input type="text" name="national_id"
                                                                                value="{{ old('national_id') }}"
                                                                                class="form-control @error('national_id') is-invalid @enderror">
                                                                            @error('national_id')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="nationality"
                                                                                class="form-label">{{ __('admin.nationality') }}</label>
                                                                            <input type="text" name="nationality"
                                                                                value="{{ old('nationality') }}"
                                                                                class="form-control @error('nationality') is-invalid @enderror">
                                                                            @error('nationality')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="passport_number"
                                                                                class="form-label">{{ __('admin.passport_number') }}</label>
                                                                            <input type="text" name="passport_number"
                                                                                value="{{ old('passport_number') }}"
                                                                                class="form-control @error('passport_number') is-invalid @enderror">
                                                                            @error('passport_number')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label for="can_login"
                                                                                class="form-label">{{ __('admin.can_login') }}</label>
                                                                            <select name="can_login"
                                                                                class="form-select @error('can_login') is-invalid @enderror">
                                                                                <option value="1"
                                                                                    @selected(old('can_login', true))>
                                                                                    {{ __('admin.yes') }}
                                                                                </option>
                                                                                <option value="0"
                                                                                    @selected(!old('can_login', true))>
                                                                                    {{ __('admin.no') }}</option>
                                                                            </select>
                                                                            @error('can_login')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="status_details"
                                                                                class="form-label">{{ __('admin.status_details') }}</label>
                                                                            <textarea name="status_details" class="form-control @error('status_details') is-invalid @enderror">{{ old('status_details') }}</textarea>
                                                                            @error('status_details')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Profile Info Accordion --}}
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingProfile">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseProfile"
                                                        aria-expanded="false" aria-controls="collapseProfile">
                                                        {{ __('admin.profile_info') }}
                                                    </button>
                                                </h2>
                                                <div id="collapseProfile" class="accordion-collapse collapse"
                                                    aria-labelledby="headingProfile" data-bs-parent="#userAccordion">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="first_name"
                                                                        class="form-label">{{ __('admin.first_name') }}</label>
                                                                    <input type="text" name="first_name"
                                                                        id="first_name" value="{{ old('first_name') }}"
                                                                        class="form-control @error('first_name') is-invalid @endif">
                                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            {{-- More Profile Fields as col-md-6 --}}
                            <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="middle_name"
                                                                            class="form-label">{{ __('admin.middle_name') }}</label>
                                                                        <input type="text" name="middle_name"
                                                                            value="{{ old('middle_name') }}"
                                                                            class="form-control @error('middle_name') is-invalid @enderror">
                                                                        @error('middle_name')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="last_name"
                                                                            class="form-label">{{ __('admin.last_name') }}</label>
                                                                        <input type="text" name="last_name"
                                                                            value="{{ old('last_name') }}"
                                                                            class="form-control @error('last_name') is-invalid @enderror">
                                                                        @error('last_name')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="whatapp_number"
                                                                            class="form-label">{{ __('admin.whatsapp_number') }}</label>
                                                                        <input type="text" name="whatapp_number"
                                                                            value="{{ old('whatapp_number') }}"
                                                                            class="form-control @error('whatapp_number') is-invalid @enderror">
                                                                        @error('whatapp_number')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="telegram_number"
                                                                            class="form-label">{{ __('admin.telegram_number') }}</label>
                                                                        <input type="text" name="telegram_number"
                                                                            value="{{ old('telegram_number') }}"
                                                                            class="form-control @error('telegram_number') is-invalid @enderror">
                                                                        @error('telegram_number')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="date_of_birth"
                                                                            class="form-label">{{ __('admin.date_of_birth') }}</label>
                                                                        <input type="date" name="date_of_birth"
                                                                            value="{{ old('date_of_birth') }}"
                                                                            class="form-control @error('date_of_birth') is-invalid @enderror">
                                                                        @error('date_of_birth')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="gender"
                                                                            class="form-label">{{ __('admin.gender') }}</label>
                                                                        <select name="gender"
                                                                            class="form-select @error('gender') is-invalid @enderror">
                                                                            <option value="">
                                                                                {{ __('admin.select_gender') }}</option>
                                                                            <option value="male"
                                                                                @selected(old('gender') == 'male')>
                                                                                {{ __('admin.male') }}</option>
                                                                            <option value="female"
                                                                                @selected(old('gender') == 'female')>
                                                                                {{ __('admin.female') }}</option>
                                                                        </select>
                                                                        @error('gender')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="title"
                                                                            class="form-label">{{ __('admin.title') }}</label>
                                                                        <input type="text" name="title"
                                                                            value="{{ old('title') }}"
                                                                            class="form-control @error('title') is-invalid @enderror">
                                                                        @error('title')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="mb-3">
                                                                        <label for="note"
                                                                            class="form-label">{{ __('admin.note') }}</label>
                                                                        <textarea name="note" class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                                                                        @error('note')
                                                                            <div class="invalid-feedback">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Avatar Accordion --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingAvatar">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseAvatar"
                                                            aria-expanded="false" aria-controls="collapseAvatar">
                                                            {{ __('admin.avatar') }}
                                                        </button>
                                                    </h2>
                                                    <div id="collapseAvatar" class="accordion-collapse collapse"
                                                        aria-labelledby="headingAvatar" data-bs-parent="#userAccordion">
                                                        <div class="accordion-body">
                                                            <div class="mb-3">
                                                                <input type="file" name="avatar" id="avatar"
                                                                    class="form-control @error('avatar') is-invalid @endif">
                            @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3
                                                                    d-flex justify-content-between">
                                                                <button type="submit"
                                                                    class="btn btn-primary">{{ __('admin.create_user') }}</button>
                                                                <a href="{{ route('admin.users.index') }}"
                                                                    class="btn btn-secondary">
                                                                    <i class="fas fa-arrow-right"></i>
                                                                    {{ __('admin.back_to_list') }}
                                                                </a>
                                                            </div>
        </form>
    </div>
@endsection
