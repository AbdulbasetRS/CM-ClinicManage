@extends('admin.structure')

@section('title', __('admin.appointment_information') . ' - ' . ($appointment->patient['username'] ??
    __('admin.not_specified')))

@section('content')
    <div class="container">
        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">{{ __('admin.appointment_information') }}</h3>
                        <div>
                            @if (!$appointment->visit)
                                <a href="{{ route('admin.visits.create', ['appointment_id' => $appointment->id]) }}"
                                    class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> {{ __('admin.create_visit_for_appointment') }}
                                </a>
                            @endif

                            <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                                class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> {{ __('admin.edit') }}
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> {{ __('admin.delete') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $appointment->id }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.patient') }}</th>
                                <td>
                                    @if ($appointment->patient)
                                        <a href="{{ route('admin.users.show', $appointment->patient['slug']) }}">
                                            {{ $appointment->patient['username'] }}
                                        </a>
                                    @else
                                        {{ __('admin.not_specified') }}
                                    @endif
                                </td>
                            </tr>
                            {{-- Visit Section --}}
                            <tr>
                                <th>{{ __('admin.related_visit') }}</th>
                                <td>
                                    @if ($appointment->visit)
                                        <a href="{{ route('admin.visits.show', $appointment->visit['id']) }}">
                                            {{ __('admin.view') }} {{ __('admin.visit_information') }}
                                            #{{ $appointment->visit['id'] }}
                                        </a>
                                    @else
                                        {{ __('admin.no_related_visit') }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.date') }}</th>
                                <td>{{ $appointment->date }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.start_time') }}</th>
                                <td>{{ $appointment->start_time }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.end_time') }}</th>
                                <td>{{ $appointment->end_time }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.status') }}</th>
                                <td>{{ $appointment->status }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.notes') }}</th>
                                <td>{{ $appointment->notes ?? '-' }}</td>
                            </tr>


                            <tr>
                                <th>{{ __('admin.created_at') }}</th>
                                <td>{{ $appointment->created_at }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.updated_at') }}</th>
                                <td>{{ $appointment->updated_at }}</td>
                            </tr>
                        </table>

                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('admin.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">{{ __('admin.confirm_delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('admin.confirm_delete_appointment_message') }}
                        <strong>{{ $appointment->patient['username'] ?? __('admin.not_specified') }}</strong>؟
                    </p>
                    <p class="text-danger">{{ __('admin.cannot_undo') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('admin.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
