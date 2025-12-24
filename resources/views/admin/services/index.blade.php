@extends('admin.structure')

@section('title', __('admin.services_management'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">{{ __('admin.services_list') }}</h3>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#serviceModal"
                            id="addServiceBtn">
                            <i class="fas fa-plus"></i> {{ __('admin.add_service') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="services-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{ __('admin.service_name') }}</th>
                                    <th>{{ __('admin.description') }}</th>
                                    <th>{{ __('admin.price') }}</th>
                                    <th>{{ __('admin.status') }}</th>
                                    <th>{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Service Modal --}}
    <div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('admin.add_new_service') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="serviceForm">
                    @csrf
                    <input type="hidden" id="service_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('admin.service_name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('admin.description') }}</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">{{ __('admin.price') }} (ر.س)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price"
                                required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="1"
                                checked>
                            <label class="form-check-label" for="status">{{ __('admin.active') }}</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">{{ __('admin.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        {{ __('admin.confirm_delete_service') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>
                        {{ __('admin.are_you_sure_delete_service') }}
                        <strong id="modalServiceName"></strong>؟
                    </p>
                    <p class="text-danger">{{ __('admin.cannot_undo') }}</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('admin.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main.script')
    <script>
        $(document).ready(function() {
            var table = $('#services-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.services.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Add Service
            $('#addServiceBtn').click(function() {
                $('#serviceForm')[0].reset();
                $('#service_id').val('');
                $('#modalTitle').text('{{ __('admin.add_new_service') }}');
                $('#status').prop('checked', true);
            });

            // Edit Service
            $('body').on('click', '.edit-service', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var description = $(this).data('description');
                var price = $(this).data('price');
                var status = $(this).data('status');

                $('#serviceModal').modal('show');
                $('#modalTitle').text('{{ __('admin.edit_service') }}');
                $('#service_id').val(id);
                $('#name').val(name);
                $('#description').val(description);
                $('#price').val(price);
                $('#status').prop('checked', status == 1);
            });

            // Save Service
            $('#serviceForm').submit(function(e) {
                e.preventDefault();
                var id = $('#service_id').val();
                var url = id ? "{{ route('admin.services.update', ':id') }}".replace(':id', id) :
                    "{{ route('admin.services.store') }}";
                var method = id ? 'PUT' : 'POST';
                var formData = $(this).serialize();
                if (id) {
                    formData += '&_method=PUT';
                }

                $.ajax({
                    url: url,
                    type: 'POST', // Always POST, method spoofing handled by _method
                    data: formData,
                    success: function(response) {
                        $('#serviceModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.success);
                    },
                    error: function(xhr) {
                        toastr.error('{{ __('admin.error_check_inputs') }}');
                    }
                });
            });

            // Delete Service - Open Modal
            $('body').on('click', '.delete-service', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var modal = $('#deleteModal');

                // Update modal content
                modal.find('#modalServiceName').text(name);

                // Update form action
                var deleteUrl = "{{ route('admin.services.destroy', ':id') }}".replace(':id', id);
                modal.find('#deleteForm').attr('action', deleteUrl);

                // Show modal
                modal.modal('show');
            });

            // Handle Delete Form Submission
            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.success);
                    },
                    error: function(xhr) {
                        $('#deleteModal').modal('hide');
                        toastr.error('{{ __('admin.error_during_delete') }}');
                    }
                });
            });
        });
    </script>
@endsection
