@extends('admin.structure')

@section('title', __('admin.dashboard'))

@section('content')
    <div class="container py-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0">
                            @php
                                $defaultAvatar =
                                    auth()->user()->profile->gender === 'female'
                                        ? asset('assets/images/female-default.png')
                                        : asset('assets/images/male-default.png');
                                $avatarUrl = auth()->user()->profile->avatar_url ?? $defaultAvatar;
                            @endphp
                            <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->username }}" class="rounded-circle"
                                width="80" height="80" style="object-fit: cover;">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="mb-1">{{ __('admin.welcome_back') }},
                                {{ auth()->user()->profile->full_name ?? auth()->user()->username }}!</h2>
                            <p class="text-muted mb-0">
                                <span class="badge bg-primary">{{ auth()->user()->type->value }}</span>
                                <span class="ms-2"><i class="far fa-envelope me-1"></i> {{ auth()->user()->email }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <!-- Users Stats -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted mb-0">{{ __('admin.total_users') }}</h6>
                            <div class="icon-box bg-light-primary text-primary rounded-circle p-2">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ $users_count }}</h3>
                        <small class="text-muted">
                            <span class="text-success"><i class="fas fa-user-md"></i> {{ $doctors_count }}
                                {{ __('admin.doctors') }}</span> |
                            <span class="text-info"><i class="fas fa-user-injured"></i> {{ $patients_count }}
                                {{ __('admin.patients') }}</span>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted mb-0">{{ __('admin.services') }}</h6>
                            <div class="icon-box bg-light-info text-info rounded-circle p-2">
                                <i class="fas fa-stethoscope fa-lg"></i>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ $services_count }}</h3>
                        <small class="text-muted">{{ __('admin.available_services') }}</small>
                    </div>
                </div>
            </div>

            <!-- Visits -->
            <!-- Visits -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted mb-0">{{ __('admin.visits_this_month') }}</h6>
                            <div class="icon-box bg-light-secondary text-secondary rounded-circle p-2">
                                <i class="fas fa-walking fa-lg"></i>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ $visits_count }}</h3>
                        <small class="text-success">
                            <i class="fas fa-check-circle"></i> {{ $completed_visits_count }} {{ __('admin.completed') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted mb-0">{{ __('admin.revenue_this_month') }}</h6>
                            <div class="icon-box bg-light-success text-success rounded-circle p-2">
                                <i class="fas fa-dollar-sign fa-lg"></i>
                            </div>
                        </div>
                        <h3 class="mb-0">{{ number_format($total_revenue, 2) }}</h3>
                        <small class="text-muted">{{ $invoices_count }} {{ __('admin.invoices_generated') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row g-3">
            <!-- Recent Visits -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('admin.todays_visits') }}</h5>
                        <a href="{{ route('admin.visits.index') }}"
                            class="btn btn-sm btn-primary">{{ __('admin.view_all') }}</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="">
                                <tr>
                                    <th>{{ __('admin.visit_number') }}</th>
                                    <th>{{ __('admin.patient') }}</th>
                                    <th>{{ __('admin.doctor') }}</th>
                                    <th>{{ __('admin.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_visits as $visit)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.visits.show', $visit->id) }}"
                                                class="text-decoration-none fw-bold">
                                                #{{ $visit->id }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $visit->patient->slug) }}"
                                                class="text-decoration-none fw-bold">
                                                {{ $visit->patient->profile->full_name ?? $visit->patient->username }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $visit->doctor->slug) }}"
                                                class="text-decoration-none fw-bold">
                                                {{ $visit->doctor->profile->full_name ?? $visit->doctor->username }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                {{ $visit->status ?? 'N/A' }}
                                            </span>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            {{ __('admin.no_visits_today') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('admin.recent_invoices') }}</h5>
                        <a href="{{ route('admin.invoices.index') }}"
                            class="btn btn-sm btn-primary">{{ __('admin.view_all') }}</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-bordered">
                            <thead class="">
                                <tr>
                                    <th>{{ __('admin.invoice_number') }}</th>
                                    <th>{{ __('admin.patient') }}</th>
                                    <th>{{ __('admin.amount') }}</th>
                                    <th>{{ __('admin.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_invoices as $invoice)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.invoices.show', $invoice->id) }}"
                                                class="text-decoration-none fw-bold">
                                                #{{ $invoice->id }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $invoice->patient->slug) }}"
                                                class="text-decoration-none fw-bold">
                                                {{ $invoice->patient->profile->full_name ?? $invoice->patient->username }}
                                            </a>
                                        </td>
                                        <td>{{ number_format($invoice->final_amount, 2) }}</td>
                                        <td>

                                            <span
                                                class="badge bg-{{ $invoice->status->value === 'paid' ? 'success' : ($invoice->status->value === 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ $invoice->status->value }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            {{ __('admin.no_recent_invoices') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
