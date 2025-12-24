@extends('admin.structure')

@section('title', __('admin.visit_information') . ' - ' . ($visit->patient?->username ?? __('admin.not_specified')))

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
                        <h3 class="card-title mb-0">{{ __('admin.visit_information') }}</h3>

                        <div>
                            @if ($visit->status === \App\Enums\VisitStatus::Pending->value)
                                <form action="{{ route('admin.visits.update-status', $visit->id) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status"
                                        value="{{ \App\Enums\VisitStatus::Completed->value }}">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> {{ __('admin.complete_visit') }}
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('admin.visits.edit', $visit->id) }}" class="btn btn-primary btn-sm">
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
                                <td>{{ $visit->id }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.patient') }}</th>
                                <td>
                                    @if ($visit->patient)
                                        <a href="{{ route('admin.users.show', $visit->patient['slug']) }}">
                                            {{ $visit->patient['username'] }}
                                        </a>
                                    @else
                                        {{ __('admin.not_specified') }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.doctor') }}</th>
                                <td>
                                    @if ($visit->doctor)
                                        <a href="{{ route('admin.users.show', $visit->doctor['slug']) }}">
                                            {{ $visit->doctor['username'] }}
                                        </a>
                                    @else
                                        {{ __('admin.not_specified') }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.related_appointment') }}</th>
                                <td>
                                    @if ($visit->appointment)
                                        <a href="{{ route('admin.appointments.show', $visit->appointment['id']) }}">
                                            {{ __('admin.view') }} {{ __('admin.appointment_information') }}
                                            #{{ $visit->appointment['id'] }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.related_invoices') }}</th>
                                <td>
                                    @forelse ($visit->invoices as $invoice)
                                        <a href="{{ route('admin.invoices.show', $invoice['id']) }}" class="d-block mb-1">
                                            {{ __('admin.invoice_number') }} #{{ $invoice['id'] }} - <span
                                                class="badge bg-secondary">{{ $invoice['status'] }}</span>
                                        </a>
                                    @empty
                                        <span class="text-muted d-block mb-2">{{ __('admin.no_invoices') }}</span>
                                        @if ($visit->patient)
                                            <a href="{{ route('admin.visits.invoices.create', $visit->id) }}"
                                                class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-plus"></i> {{ __('admin.create_invoice') }}
                                            </a>
                                        @endif
                                    @endforelse
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.visit_date') }}</th>
                                <td>{{ $visit->visit_date }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.status') }}</th>
                                <td>{{ $visit->status }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.symptoms') }}</th>
                                <td>{{ $visit->symptoms ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.diagnosis') }}</th>
                                <td>{{ $visit->diagnosis ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.treatment_plan') }}</th>
                                <td>{{ $visit->treatment_plan ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.notes') }}</th>
                                <td>{{ $visit->notes ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.created_at') }}</th>
                                <td>{{ $visit->created_at }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('admin.updated_at') }}</th>
                                <td>{{ $visit->updated_at }}</td>
                            </tr>

                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('admin.visits.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('admin.back_to_list') }}
                        </a>
                    </div>

                </div>

                {{-- Attachments Section --}}
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">{{ __('admin.attachments') }} ({{ $visit->attachments->count() }})
                        </h4>
                        <a href="{{ route('admin.visits.attachments.upload', $visit->id) }}"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-upload"></i> {{ __('admin.upload_attachment') }}
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($visit->attachments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin.type') }}</th>
                                            <th>{{ __('admin.title') }}</th>
                                            <th>{{ __('admin.description') }}</th>
                                            <th>{{ __('admin.uploaded_by') }}</th>
                                            <th>{{ __('admin.date') }}</th>
                                            <th>{{ __('admin.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($visit->attachments as $attachment)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $attachment->type }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ Storage::url($attachment->name) }}" target="_blank">
                                                        {{ $attachment->title ?? basename($attachment->name) }}
                                                    </a>
                                                </td>
                                                <td>{{ $attachment->description ?? '-' }}</td>
                                                <td>{{ $attachment->uploader->username ?? '-' }}</td>
                                                <td>{{ $attachment->created_at->diffForHumans() }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        {{-- View Modal Button --}}
                                                        <button type="button" class="btn btn-info btn-sm text-white"
                                                            data-bs-toggle="modal" data-bs-target="#viewAttachmentModal"
                                                            data-url="{{ Storage::url($attachment->name) }}"
                                                            data-type="{{ $attachment->type }}"
                                                            data-title="{{ $attachment->title ?? basename($attachment->name) }}"
                                                            title="{{ __('admin.preview') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        {{-- Open Link Button --}}
                                                        <a href="{{ Storage::url($attachment->name) }}" target="_blank"
                                                            class="btn btn-secondary btn-sm"
                                                            title="{{ __('admin.open_new_window') }}">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>

                                                        {{-- Delete Button --}}
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#deleteAttachmentModal"
                                                            data-id="{{ $attachment->id }}"
                                                            data-title="{{ $attachment->title ?? basename($attachment->name) }}"
                                                            title="{{ __('admin.delete') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center py-3">{{ __('admin.no_attachments') }}</p>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>


    {{-- Delete Visit Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">{{ __('admin.confirm_delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>
                        {{ __('admin.confirm_delete_visit_message') }}
                        <strong>{{ $visit->patient?->username ?? __('admin.not_specified') }}</strong>؟
                    </p>
                    <p class="text-danger">{{ __('admin.cannot_undo') }}</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>

                    <form action="{{ route('admin.visits.destroy', $visit->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('admin.delete') }}</button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    {{-- Delete Attachment Modal --}}
    <div class="modal fade" id="deleteAttachmentModal" tabindex="-1" aria-labelledby="deleteAttachmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAttachmentModalLabel">{{ __('admin.confirm_delete_attachment') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('admin.confirm_delete_attachment_message') }} <strong id="modalAttachmentTitle"></strong>؟
                    </p>
                    <p class="text-danger">{{ __('admin.cannot_undo') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <form id="deleteAttachmentForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('admin.delete') }}
                            {{ __('admin.file') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- View Attachment Modal --}}
    <div class="modal fade" id="viewAttachmentModal" tabindex="-1" aria-labelledby="viewAttachmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAttachmentModalLabel">{{ __('admin.preview') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center bg-light">
                    <img id="attachmentPreview" src="" class="img-fluid rounded shadow-sm"
                        alt="Attachment Preview" style="max-height: 80vh; display: none;">
                    <div id="attachmentPlaceholder" class="py-5" style="display: none;">
                        <i class="fas fa-file-alt fa-5x text-secondary mb-3"></i>
                        <p class="text-muted">{{ __('admin.cannot_preview') }}</p>
                        <a id="attachmentDownloadLink" href="" target="_blank" class="btn btn-primary">
                            <i class="fas fa-download me-1"></i> {{ __('admin.download') }} /
                            {{ __('admin.open_new_window') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('main.script')
        <script>
            $(document).ready(function() {
                // Handle Delete Attachment Modal
                $('#deleteAttachmentModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var attachmentId = button.data('id');
                    var attachmentTitle = button.data('title');
                    var modal = $(this);

                    modal.find('#modalAttachmentTitle').text(attachmentTitle);

                    var deleteUrl = "{{ route('admin.attachments.destroy', ':id') }}";
                    deleteUrl = deleteUrl.replace(':id', attachmentId);
                    modal.find('#deleteAttachmentForm').attr('action', deleteUrl);
                });

                // Handle View Attachment Modal
                $('#viewAttachmentModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var attachmentUrl = button.data('url');
                    var attachmentType = button.data('type');
                    var attachmentTitle = button.data('title');
                    var modal = $(this);

                    modal.find('#viewAttachmentModalLabel').text(attachmentTitle);

                    var previewImg = modal.find('#attachmentPreview');
                    var placeholder = modal.find('#attachmentPlaceholder');
                    var downloadLink = modal.find('#attachmentDownloadLink');

                    // Check if image
                    if (['photo', 'xray', 'analysis'].includes(attachmentType) || attachmentUrl.match(
                            /\.(jpeg|jpg|gif|png)$/) != null) {
                        previewImg.attr('src', attachmentUrl).show();
                        placeholder.hide();
                    } else {
                        previewImg.hide();
                        placeholder.show();
                        downloadLink.attr('href', attachmentUrl);
                    }
                });
            });
        </script>
    @endsection
@endsection
