@extends('admin.structure')

@section('title', __('admin.upload_attachment'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">{{ __('admin.upload_attachment') }} {{ __('admin.visit_information') }}
                            #{{ $visit->id }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.visits.attachments.store', $visit->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="type" class="form-label">نوع المرفق <span
                                        class="text-danger">*</span></label>
                                <select name="type" id="type"
                                    class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">{{ __('admin.select') }} {{ __('admin.type') }}</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->value }}"
                                            {{ old('type') == $type->value ? 'selected' : '' }}>
                                            {{ ucfirst($type->value) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">{{ __('admin.title') }}</label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                    placeholder="{{ __('admin.file_title_optional') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="file" class="form-label">{{ __('admin.file') }} <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="file" id="file"
                                    class="form-control @error('file') is-invalid @enderror" required>
                                <div class="form-text">{{ __('admin.max_file_size_10mb') }}</div>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('admin.description') }}</label>
                                <textarea name="description" id="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="{{ __('admin.additional_description_optional') }}">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.visits.show', $visit->id) }}"
                                    class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-1"></i> {{ __('admin.upload') }} {{ __('admin.file') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
