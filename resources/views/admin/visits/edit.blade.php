@extends('admin.structure')

@section('title', __('admin.edit') . ' ' . __('admin.visit_information'))

@section('content')
    <div class="container">

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.visits.update', $visit->id) }}">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Patient --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.patient') }}</label>
                    @if ($visit->patient)
                        <a href="{{ route('admin.users.show', $visit->patient['slug']) }}" class="form-control text-primary"
                            target="_blank">
                            {{ $visit->patient['username'] }}
                        </a>
                    @else
                        <input type="text" class="form-control" value="{{ __('admin.not_specified') }}" readonly>
                    @endif
                </div>

                {{-- Doctor --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.doctor') }}</label>
                    @if ($visit->doctor)
                        <a href="{{ route('admin.users.show', $visit->doctor['slug']) }}" class="form-control text-primary"
                            target="_blank">
                            {{ $visit->doctor['username'] }}
                        </a>
                    @else
                        <input type="text" class="form-control" value="{{ __('admin.not_specified') }}" readonly>
                    @endif
                </div>


                {{-- Appointment Link --}}
                <div class="col-md-12 mb-3">
                    <label class="form-label">{{ __('admin.related_appointment') }}</label>
                    @if ($visit->appointment)
                        <a href="{{ route('admin.appointments.show', $visit->appointment['id']) }}"
                            class="form-control text-primary" target="_blank">
                            {{ __('admin.view') }} {{ __('admin.appointment_information') }}
                            #{{ $visit->appointment['id'] }}
                        </a>
                    @else
                        <input type="text" class="form-control" value="{{ __('admin.none') }}" readonly>
                    @endif
                </div>

                {{-- Visit Date --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.visit_date') }}</label>
                    <input type="date" name="visit_date" value="{{ old('visit_date', $visit->visit_date) }}"
                        class="form-control @error('visit_date') is-invalid @enderror">
                    @error('visit_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.status') }}</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(old('status', $visit->status) == $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Symptoms --}}
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('admin.symptoms') }}</label>
                    <textarea name="symptoms" class="form-control @error('symptoms') is-invalid @enderror">{{ old('symptoms', $visit->symptoms) }}</textarea>
                    @error('symptoms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Diagnosis --}}
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('admin.diagnosis') }}</label>
                    <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror">{{ old('diagnosis', $visit->diagnosis) }}</textarea>
                    @error('diagnosis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Treatment Plan --}}
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('admin.treatment_plan') }}</label>
                    <textarea name="treatment_plan" class="form-control @error('treatment_plan') is-invalid @enderror">{{ old('treatment_plan', $visit->treatment_plan) }}</textarea>
                    @error('treatment_plan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Notes --}}
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('admin.notes') }}</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $visit->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">{{ __('admin.update') }}
                    {{ __('admin.visit_information') }}</button>
                <a href="{{ route('admin.visits.show', $visit->id) }}"
                    class="btn btn-secondary">{{ __('admin.back') }}</a>
            </div>

        </form>
    </div>
@endsection
