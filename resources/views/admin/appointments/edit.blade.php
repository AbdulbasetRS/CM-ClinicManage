@extends('admin.structure')

@section('title', __('admin.edit') . ' ' . __('admin.appointment_information'))

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

        <form method="POST" action="{{ route('admin.appointments.update', $appointment->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="patient" class="form-label">{{ __('admin.patient') }}</label>
                    <input type="text" id="patient" value="{{ $appointment->patient['username'] }}"
                        class="form-control" readonly>
                </div>


                <div class="col-md-3 mb-3">
                    <label for="date" class="form-label">{{ __('admin.date') }}</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $appointment->date) }}"
                        class="form-control @error('date') is-invalid @enderror">
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">{{ __('admin.status') }}</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(old('status', $appointment->status) == $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @php
                    $times = [];
                    for ($h = 0; $h < 24; $h++) {
                        for ($m = 0; $m < 60; $m += 15) {
                            $times[] = sprintf('%02d:%02d', $h, $m);
                        }
                    }
                @endphp

                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">{{ __('admin.appointment_time') }}</label>
                    <select name="start_time" id="start_time" class="form-select @error('start_time') is-invalid @enderror">
                        @foreach ($times as $time)
                            <option value="{{ $time }}" @selected(old('start_time', $appointment->start_time) == $time)>{{ $time }}</option>
                        @endforeach
                    </select>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="notes" class="form-label">{{ __('admin.notes') }}</label>
                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">{{ __('admin.update_appointment') }}</button>
                <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                    class="btn btn-secondary">{{ __('admin.back') }}</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#start_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                minuteIncrement: 15, // كل ربع ساعة
            });

            flatpickr("#end_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                minuteIncrement: 15, // كل ربع ساعة
            });
        });
    </script>

@endsection
