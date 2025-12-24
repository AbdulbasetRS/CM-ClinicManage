@php
    use App\Enums\VisitStatus;
@endphp

<div class="card shadow-sm">
    <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            {{ $attributes->has('patient-id') ? __('admin.patient_visits_record') : __('admin.all_visits_record') }}
        </h5>
        <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#visitFilters"
            aria-expanded="false" aria-controls="visitFilters">
            <i class="fas fa-filter"></i> {{ __('admin.filter') }}
        </button>
    </div>
    <div class="card-body">
        {{-- Filters Section --}}
        <div class="collapse mb-4" id="visitFilters">
            <div class="card card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('admin.status') }}</label>
                        <select id="filter-status" class="form-select">
                            <option value="">{{ __('admin.all') }}</option>
                            @if (enum_exists(VisitStatus::class))
                                @foreach (VisitStatus::cases() as $status)
                                    <option value="{{ $status->value }}">{{ $status->value }}</option>
                                @endforeach
                            @endif
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
            <table id="patient-visits-table" class="table table-striped table-hover table-bordered" style="width:100%"
                data-patient-id="{{ $attributes->get('patient-id') }}">
                <thead>
                    <tr>
                        <th>#</th>
                        @if (!$attributes->has('patient-id'))
                            <th>{{ __('admin.patient') }}</th>
                        @endif
                        <th>{{ __('admin.doctor') }}</th>
                        <th>{{ __('admin.date') }}</th>
                        <th>{{ __('admin.diagnosis') }}</th>
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
        var patientId = $('#patient-visits-table').data('patient-id');

        var columns = [{
            data: 'id',
            name: 'id'
        }];

        // Add patient column if no patientId is selected
        if (!patientId) {
            columns.push({
                data: 'patient_name',
                name: 'patient.username'
            });
        }

        columns.push({
            data: 'doctor_name',
            name: 'doctor.username'
        }, {
            data: 'visit_date',
            name: 'visit_date'
        }, {
            data: 'diagnosis',
            name: 'diagnosis',
            render: function(data) {
                return data ? data.substring(0, 50) + (data.length > 50 ? '...' : '') :
                    '{{ __('admin.not_specified') }}';
            },
            visible: false
        }, {
            data: 'status',
            name: 'status',
            render: function(data) {
                return data ? '<span class="badge bg-secondary">' + data + '</span>' : '';
            }
        }, {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        });

        $('#patient-visits-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.visits.index') }}",
                data: function(d) {
                    d.patient_id = patientId;
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
            $('#patient-visits-table').DataTable().ajax.reload();
        });

        // Handle Delete Modal
        $('#deleteVisitModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var visitId = button.data('id');
            var patientName = button.data('patient');
            var modal = $(this);

            modal.find('#modalVisitId').text('#' + visitId);
            modal.find('#modalPatientName').text(patientName);

            // Update form action
            var deleteUrl = "{{ route('admin.visits.destroy', ':id') }}";
            deleteUrl = deleteUrl.replace(':id', visitId);
            modal.find('#deleteVisitForm').attr('action', deleteUrl);
        });
    });
</script>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteVisitModal" tabindex="-1" aria-labelledby="deleteVisitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteVisitModalLabel">{{ __('admin.confirm_delete_visit') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    {{ __('admin.confirm_delete_visit_message') }}
                    <strong id="modalVisitId"></strong>
                    {{ __('admin.related_to_patient') }}
                    <strong id="modalPatientName"></strong>؟
                </p>
                <p class="text-danger">{{ __('admin.cannot_undo') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                <form id="deleteVisitForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('admin.delete_visit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
