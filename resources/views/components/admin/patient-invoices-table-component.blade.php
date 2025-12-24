@php
    use App\Enums\InvoiceStatus;
    use App\Enums\PaymentMethod;
@endphp

<div class="card shadow-sm">
    <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            {{ $attributes->has('patient-id') ? __('admin.patient_invoices_record') : __('admin.all_invoices_record') }}
        </h5>
        <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#invoiceFilters"
            aria-expanded="false" aria-controls="invoiceFilters">
            <i class="fas fa-filter"></i> {{ __('admin.filter') }}
        </button>
    </div>
    <div class="card-body">
        {{-- Filters Section --}}
        <div class="collapse mb-4" id="invoiceFilters">
            <div class="card card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('admin.status') }}</label>
                        <select id="filter-status" class="form-select">
                            <option value="">{{ __('admin.all') }}</option>
                            @foreach (InvoiceStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('admin.payment_method') }}</label>
                        <select id="filter-payment-method" class="form-select">
                            <option value="">{{ __('admin.all') }}</option>
                            @foreach (PaymentMethod::cases() as $method)
                                <option value="{{ $method->value }}">{{ $method->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('admin.from_date') }}</label>
                        <input type="date" id="filter-from-date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('admin.to_date') }}</label>
                        <input type="date" id="filter-to-date" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="patient-invoices-table" class="table table-striped table-hover table-bordered" style="width:100%"
                data-patient-id="{{ $attributes->get('patient-id') }}">
                <thead>
                    <tr>
                        <th>#</th>
                        @if (!$attributes->has('patient-id'))
                            <th>{{ __('admin.patient') }}</th>
                        @endif
                        <th>{{ __('admin.date') }}</th>
                        <th>{{ __('admin.total') }}</th>
                        <th>{{ __('admin.discount') }}</th>
                        <th>{{ __('admin.final_amount') }}</th>
                        <th>{{ __('admin.status') }}</th>
                        <th>{{ __('admin.payment_method') }}</th>
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
        var patientId = $('#patient-invoices-table').data('patient-id');

        var columns = [{
            data: 'id',
            name: 'id'
        }];

        // Add patient column if no patientId is selected
        if (!patientId) {
            columns.push({
                data: 'patient_name',
                name: 'patient.username' // Assuming join with users table
            });
        }

        columns.push({
            data: 'invoice_date',
            name: 'invoice_date'
        }, {
            data: 'total_amount',
            name: 'total_amount'
        }, {
            data: 'discount',
            name: 'discount'
        }, {
            data: 'final_amount',
            name: 'final_amount',
            render: function(data, type, row) {
                return '<strong>' + data + '</strong>';
            }
        }, {
            data: 'status',
            name: 'status',
            render: function(data, type, row) {
                var color = 'secondary';
                if (data === 'paid') color = 'success';
                else if (data === 'pending') color = 'warning';
                else if (data === 'canceled') color = 'danger';
                return '<span class="badge bg-' + color + '">' + data + '</span>';
            }
        }, {
            data: 'payment_method',
            name: 'payment_method'
        }, {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        });

        $('#patient-invoices-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.invoices.index') }}",
                data: function(d) {
                    d.patient_id = patientId;
                    // Add filters if they exist on the page
                    d.status = $('#filter-status').val();
                    d.payment_method = $('#filter-payment-method').val();
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
        $('#filter-status, #filter-payment-method, #filter-from-date, #filter-to-date').on('change',
            function() {
                $('#patient-invoices-table').DataTable().ajax.reload();
            });

        // Handle Delete Modal
        $('#deleteInvoiceModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var invoiceId = button.data('id');
            var patientName = button.data('patient');
            var modal = $(this);

            modal.find('#modalInvoiceId').text('#' + invoiceId);
            modal.find('#modalPatientName').text(patientName);

            // Update form action
            var deleteUrl = "{{ route('admin.invoices.destroy', ':id') }}";
            deleteUrl = deleteUrl.replace(':id', invoiceId);
            modal.find('#deleteInvoiceForm').attr('action', deleteUrl);
        });
    });
</script>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteInvoiceModal" tabindex="-1" aria-labelledby="deleteInvoiceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteInvoiceModalLabel">{{ __('admin.confirm_delete_invoice') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    {{ __('admin.confirm_delete_invoice_message') }}
                    <strong id="modalInvoiceId"></strong>
                    {{ __('admin.for_patient') }}
                    <strong id="modalPatientName"></strong>؟
                </p>
                <p class="text-danger">{{ __('admin.cannot_undo') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                <form id="deleteInvoiceForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('admin.delete_invoice') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
