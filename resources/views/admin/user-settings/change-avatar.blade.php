@extends('admin.structure')

@section('title', __('admin.change_avatar'))

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle"></i> {{ __('admin.change_avatar') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Current Avatar Display --}}
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @if (auth()->user()->profile->avatar)
                                    <img src="{{ \App\Helpers\PathHelper::userAvatarUrl(auth()->user()->id, auth()->user()->profile->avatar) }}"
                                        alt="Avatar" class="rounded-circle border border-3 border-primary shadow"
                                        style="width: 150px; height: 150px; object-fit: cover;" id="avatarPreview">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-3 border-primary shadow"
                                        style="width: 150px; height: 150px;" id="avatarPreview">
                                        <i class="fas fa-user fa-4x text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-3">
                                <h6 class="mb-0">{{ auth()->user()->username }}</h6>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </div>
                        </div>

                        {{-- Upload New Avatar Form --}}
                        <form method="POST" action="{{ route('admin.user-settings.change-avatar.update') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="avatar" class="form-label">
                                    <i class="fas fa-image text-muted"></i> {{ __('admin.choose_new_image') }}
                                </label>
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                    id="avatar" name="avatar" accept="image/*" onchange="previewImage(event)" required>
                                @error('avatar')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> {{ __('admin.avatar_requirements') }}
                                </small>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> {{ __('admin.update_avatar') }}
                                </button>
                            </div>
                        </form>

                        {{-- Delete Avatar Button --}}
                        @if (auth()->user()->avatar)
                            <form method="POST" action="{{ route('admin.user-settings.change-avatar.delete') }}"
                                class="mt-3">
                                @csrf
                                @method('DELETE')
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-outline-danger"
                                        onclick="return confirm('{{ __('admin.confirm_delete_avatar') }}')">
                                        <i class="fas fa-trash"></i> {{ __('admin.delete_avatar') }}
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Tips Card --}}
                <div class="card mt-3 border-info">
                    <div class="card-body">
                        <h6 class="card-title text-info">
                            <i class="fas fa-lightbulb"></i> {{ __('admin.avatar_tips') }}
                        </h6>
                        <ul class="small mb-0">
                            <li>{{ __('admin.avatar_tip_1') }}</li>
                            <li>{{ __('admin.avatar_tip_2') }}</li>
                            <li>{{ __('admin.avatar_tip_3') }}</li>
                            <li>{{ __('admin.avatar_tip_4') }}</li>
                            <li>{{ __('admin.avatar_tip_5') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main.script')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('avatarPreview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Replace the preview with the new image
                    preview.innerHTML = `<img src="${e.target.result}" 
                                             alt="Preview" 
                                             class="rounded-circle border border-3 border-primary shadow"
                                             style="width: 150px; height: 150px; object-fit: cover;">`;
                }

                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
