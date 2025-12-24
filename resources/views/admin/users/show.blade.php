@extends('admin.structure')

@section('title', __('admin.view_user') . ' - ' . $user->username)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">{{ __('admin.user_information') }}</h3>
                        <div class="">
                            {{-- ✅ الشرط الجديد: الأزرار تظهر فقط إذا كان type هو patient --}}
                            @if ($user->type->value === 'patient' && $user->status->value === 'active')
                                {{-- زر إنشاء حجز (Appointment) --}}
                                <a href="{{ route('admin.appointments.create', ['patient_id' => $user->id]) }}"
                                    class="btn btn-info btn-sm text-white"
                                    title="{{ __('admin.create_new_appointment_for_user') }}">
                                    <i class="fas fa-calendar-plus"></i> {{ __('admin.new_appointment') }}
                                </a>

                                {{-- زر إنشاء زيارة (Visit) --}}
                                <a href="{{ route('admin.visits.create', ['patient_id' => $user->id]) }}"
                                    class="btn btn-success btn-sm" title="{{ __('admin.create_new_visit_for_user') }}">
                                    <i class="fas fa-stethoscope"></i> {{ __('admin.new_visit') }}
                                </a>

                                {{-- زر إنشاء فاتورة (Invoice) --}}
                                <a href="{{ route('admin.users.invoices.create', $user->slug) }}"
                                    class="btn btn-warning btn-sm" title="{{ __('admin.create_new_invoice_for_user') }}">
                                    <i class="fas fa-file-invoice-dollar"></i> {{ __('admin.new_invoice') }}
                                </a>
                            @endif
                            <a href="{{ route('admin.users.edit', $user->slug) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> {{ __('admin.edit') }}
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> {{ __('admin.delete') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- User Image -->
                            <div class="col-md-3 text-center mb-4">
                                <div class="mb-3">
                                    @if ($user->profile->avatar)
                                        <img src="{{ \App\Helpers\PathHelper::userAvatarUrl($user->id, $user->profile->avatar) }}"
                                            alt="{{ __('admin.user_image') }}"
                                            class="rounded-circle border border-3 border-primary shadow"
                                            style="width: 200px; height: 200px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center border border-3 border-primary shadow"
                                            style="width: 200px; height: 200px; margin: 0 auto;">
                                            <i class="fas fa-user fa-5x text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <h4>{{ $user->profile->full_name ?? $user->username }}</h4>
                                @if ($user->status->value === 'active')
                                    <span class="badge bg-success">{{ $user->status->value }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $user->status->value }}</span>
                                @endif
                            </div>

                            <!-- User Details -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>{{ __('admin.basic_information') }}</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">{{ __('admin.username') }}:</th>
                                                <td>{{ $user->username }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.email') }}:</th>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.mobile_number') }}:</th>
                                                <td>{{ $user->mobile_number ?? __('admin.not_specified') }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.national_id') }}:</th>
                                                <td>{{ $user->national_id ?? __('admin.not_specified') }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.account_status') }}:</th>
                                                <td>
                                                    <span class="badge bg-{{ $user->status }}">
                                                        {{ $user->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.user_type') }}:</th>
                                                <td>{{ $user->type }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.created_at') }}:</th>
                                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <h5>{{ __('admin.profile_information') }}</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">{{ __('admin.first_name') }}:</th>
                                                <td>{{ $user->profile->first_name ?? __('admin.not_specified') }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.middle_name') }}:</th>
                                                <td>{{ $user->profile->middle_name ?? __('admin.not_specified') }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.last_name') }}:</th>
                                                <td>{{ $user->profile->last_name ?? __('admin.not_specified') }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.gender') }}:</th>
                                                <td>{{ $user->profile->gender ?? __('admin.not_specified') }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.date_of_birth') }}:</th>
                                                <td>{{ $user->profile->date_of_birth ? $user->profile->date_of_birth->format('Y-m-d') : __('admin.not_specified') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.whatsapp_number') }}:</th>
                                                <td>{{ $user->profile->whatapp_number ?? __('admin.not_specified') }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('admin.address') }}:</th>
                                                <td>{{ $user->profile->address ?? __('admin.not_specified') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @if ($user->profile->note)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h5>{{ __('admin.additional_notes') }}</h5>
                                            <div class="card ">
                                                <div class="card-body">
                                                    {{ $user->profile->note }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="me-3">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ __('admin.created') }}: {{ $user->created_at->diffForHumans() }}
                                </span>
                                <span>
                                    <i class="fas fa-sync-alt me-1"></i>
                                    {{ __('admin.last_updated') }}:
                                    {{ $user->updated_at?->diffForHumans() ?? __('admin.none') }}
                                </span>
                            </div>
                            <div>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-right"></i> {{ __('admin.back_to_list') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ************************************************************ --}}
        {{-- قسم السجلات الخاصة بالـ Patient (يظهر فقط إذا كان patient) --}}
        {{-- ************************************************************ --}}
        @if ($user->type->value === 'patient')
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-dark text-white">
                            <h4 class="card-title mb-0">{{ __('admin.patient_historical_records') }}</h4>
                        </div>
                        <div class="card-body">
                            {{-- قائمة التبويبات (Tabs Navigation) --}}
                            <ul class="nav nav-tabs mb-4" id="patientTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="invoices-tab" data-bs-toggle="tab"
                                        data-bs-target="#invoices-content" type="button" role="tab"
                                        aria-controls="invoices-content" aria-selected="true">
                                        <i class="fas fa-file-invoice me-1"></i> {{ __('admin.invoices') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="appointments-tab" data-bs-toggle="tab"
                                        data-bs-target="#appointments-content" type="button" role="tab"
                                        aria-controls="appointments-content" aria-selected="false">
                                        <i class="fas fa-calendar-alt me-1"></i> {{ __('admin.appointments_bookings') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="visits-tab" data-bs-toggle="tab"
                                        data-bs-target="#visits-content" type="button" role="tab"
                                        aria-controls="visits-content" aria-selected="false">
                                        <i class="fas fa-stethoscope me-1"></i> {{ __('admin.medical_visits') }}
                                    </button>
                                </li>
                            </ul>

                            {{-- محتوى التبويبات (Tabs Content) --}}
                            <div class="tab-content" id="patientTabsContent">
                                {{-- تبويبة الفواتير --}}
                                <div class="tab-pane fade show active" id="invoices-content" role="tabpanel"
                                    aria-labelledby="invoices-tab">
                                    {{-- استخدام المكون --}}
                                    <x-admin.patient-invoices-table-component :patient-id="$user->id" />
                                </div>


                                <div class="tab-pane fade" id="appointments-content" role="tabpanel"
                                    aria-labelledby="appointments-tab">
                                    {{-- استخدام المكون --}}
                                    <x-admin.patient-appointments-table-component :patient-id="$user->id" />
                                </div>

                                {{-- تبويبة الزيارات --}}
                                <div class="tab-pane fade" id="visits-content" role="tabpanel"
                                    aria-labelledby="visits-tab">
                                    {{-- استخدام المكون --}}
                                    <x-admin.patient-visits-table-component :patient-id="$user->id" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- نهاية قسم السجلات --}}
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">{{ __('admin.confirm_delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('admin.confirm_delete_user_message') }} <strong>{{ $user->username }}</strong>؟</p>
                    <p class="text-danger">{{ __('admin.cannot_undo_all_data_deleted') }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <form action="{{ route('admin.users.destroy', $user->slug) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('admin.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Add any additional JavaScript if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any tooltips or other JS functionality
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
