@extends('admin.structure')

@section('title', __('admin.invoices'))

@section('main.script')
    <script>
        $(document).ready(function() {
            // Handle Delete Modal
            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                // Check if the button is inside the component's table
                // The component might render buttons with data-id and data-patient
                // We need to ensure the component's buttons use these data attributes

                // Wait, the component renders actions. Does it render the delete button with these attributes?
                // I need to check the component's action column renderer.
                // The component uses 'action' column from controller.

                var invoiceId = button.data('id');
                var patientName = button.data('patient');
                var modal = $(this);

                modal.find('#modalInvoiceId').text('#' + invoiceId);
                modal.find('#modalPatientName').text(patientName);

                // Update form action
                var deleteUrl = "{{ route('admin.invoices.destroy', ':id') }}";
                deleteUrl = deleteUrl.replace(':id', invoiceId);
                modal.find('#deleteForm').attr('action', deleteUrl);
            });
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><i class="fas fa-file-invoice-dollar"></i> {{ __('admin.invoices') }}</h4>
                        {{-- <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('admin.create') }} {{ __('admin.invoice') }}
                        </a> --}}
                    </div>

                    <div class="card-body">
                        {{-- Filters --}}


                        {{-- Use the reusable component --}}
                        <x-admin.patient-invoices-table-component />
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
