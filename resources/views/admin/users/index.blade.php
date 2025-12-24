@extends('admin.structure')

@section('title', __('admin.users'))

@section('main.script')
    <script>
        $(document).ready(function() {
            let table = $('#user-table').DataTable({
                ajax: {
                    url: "{{ route('admin.users.index') }}",
                    type: 'GET',
                    data: function(d) {
                        d.status = $('#filter-status').val();
                        d.type = $('#filter-type').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        visible: false
                    },
                    {
                        data: 'mobile_number',
                        name: 'mobile_number'
                    },
                    {
                        data: 'national_id',
                        name: 'national_id',
                        visible: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            return data ? data.toString().toUpperCase() : '';
                        }
                    },
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data) {
                            return data ? data.toString().toUpperCase() : '';
                        }
                    },
                    {
                        data: 'can_login',
                        name: 'can_login',
                        render: function(data) {
                            return data ?
                                '<span class="badge bg-success">{{ __('admin.yes') }}</span>' :
                                '<span class="badge bg-danger">{{ __('admin.no') }}</span>';
                        },
                        visible: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        visible: true
                    }
                ],
            });

            $('#filter-status, #filter-type').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            @if (session('error'))
                <div class="col-12">
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('admin.users') }}</h4>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="filter-status"></label>
                                <select id="filter-status" class="form-control">
                                    <option value="">{{ __('admin.all') }}</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="filter-type"></label>
                                <select id="filter-type" class="form-control">
                                    <option value="">{{ __('admin.all') }}</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="user-table" class="table table-striped table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>{{ __('admin.username') }}</th>
                                        <th>{{ __('admin.email') }}</th>
                                        <th>{{ __('admin.mobile_number') }}</th>
                                        <th>{{ __('admin.national_id') }}</th>
                                        <th>{{ __('admin.status') }}</th>
                                        <th>{{ __('admin.type') }}</th>
                                        <th>{{ __('admin.can_login') }}</th>
                                        <th>{{ __('admin.created_at') }}</th>
                                        <th>{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
