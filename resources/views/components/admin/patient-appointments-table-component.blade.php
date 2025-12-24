@php
    use App\Enums\AppointmentStatus;
@endphp

<div class="card shadow-sm">
    <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            {{ $attributes->has('patient-id') ? __('admin.patient_appointments_record') : __('admin.all_appointments_record') }}
        </h5>
        <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#appointmentFilters"
            aria-expanded="false" aria-controls="appointmentFilters">
            <i class="fas fa-filter"></i> {{ __('admin.filter') }}
        </button>
    </div>
    <div class="card-body">
        {{-- Filters Section --}}
        <div class="collapse mb-4" id="appointmentFilters">
            <div class="card card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('admin.status') }}</label>
                        <select id="filter-status" class="form-select">
                            <option value="">{{ __('admin.all') }}</option>
                            @foreach (AppointmentStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('admin.from_date') }}</label>
                        <input type="date" id="filter-from-date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('admin.to_date') }}</label>
                        <input type="date" id="filter-to-date" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="patient-appointments-table" class="table table-striped table-hover table-bordered"
                style="width:100%" data-patient-id="{{ $attributes->get('patient-id') }}">
                <thead>
                    <tr>
                        <th>#</th>
                        @if (!$attributes->has('patient-id'))
                            <th>{{ __('admin.patient') }}</th>
                        @endif
                        <th>{{ __('admin.date') }}</th>
                        <th>{{ __('admin.from') }}</th>
                        <th>{{ __('admin.to') }}</th>
                        <th>{{ __('admin.status') }}</th>
                        <th>{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- DataTables will populate this --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var patientId = $('#patient-appointments-table').data('patient-id');

        var columns = [{
            data: 'id',
            name: 'id'
        }];

        // Add patient column if no patientId is selected
        if (!patientId) {
            columns.push({
                data: 'patient_name',
                name: 'patient.username' // Assuming join/relation
            });
        }

        columns.push({
            data: 'date',
            name: 'date'
        }, {
            data: 'start_time',
            name: 'start_time'
        }, {
            data: 'end_time',
            name: 'end_time'
        }, {
            data: 'status',
            name: 'status',
            render: function(data, type, row) {
                var color = 'secondary';
                if (data === 'completed') color = 'success';
                else if (data === 'pending') color = 'warning';
                else if (data === 'canceled') color = 'danger';
                else if (data === 'confirmed') color = 'primary';
                return '<span class="badge bg-' + color + '">' + data + '</span>';
            }
        }, {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        });

        $('#patient-appointments-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.appointments.index') }}",
                data: function(d) {
                    d.patient_id = patientId; // Controller needs to support this if not already
                    // Add filters if they exist on the page (or inside component now)
                    d.status = $('#filter-status').val();
                    d.from_date = $('#filter-from-date').val();
                    d.to_date = $('#filter-to-date').val();
                }
            },
            columns: columns,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ar.json"
            },
            "order": [
                [0, 'desc']
            ]
        });

        // Reload table when filters change
        $('#filter-status, #filter-from-date, #filter-to-date').on('change', function() {
            $('#patient-appointments-table').DataTable().ajax.reload();
        });

        // Handle Delete Modal
        $('#deleteAppointmentModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var appointmentId = button.data('id');
            var patientName = button.data('patient');
            var modal = $(this);

            modal.find('#modalAppointmentId').text('#' + appointmentId);
            modal.find('#modalPatientName').text(patientName);

            // Update form action
            var deleteUrl = "{{ route('admin.appointments.destroy', ':id') }}";
            deleteUrl = deleteUrl.replace(':id', appointmentId);
            modal.find('#deleteAppointmentForm').attr('action', deleteUrl);
        });
    });
</script>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteAppointmentModal" tabindex="-1" aria-labelledby="deleteAppointmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAppointmentModalLabel">{{ __('admin.confirm_delete_appointment') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    {{ __('admin.confirm_delete_appointment_message') }}
                    <strong id="modalAppointmentId"></strong>
                    {{ __('admin.related_to_patient') }}
                    <strong id="modalPatientName"></strong>؟
                </p>
                <p class="text-danger">{{ __('admin.cannot_undo') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                <form id="deleteAppointmentForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('admin.delete_appointment') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
