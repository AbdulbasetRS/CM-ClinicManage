@extends('admin.structure')

@section('title', __('admin.view') . ' ' . __('admin.invoice') . ' #' . $invoice->id)

@section('main.style')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printable-content,
            #printable-content * {
                visibility: visible;
            }

            #printable-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }

            /* تحسينات للطباعة */
            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .card-header {
                background-color: transparent !important;
                border-bottom: 1px solid #ddd !important;
            }
        }
    </style>
@endsection

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

                <div class="card mb-3 no-print">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">{{ __('admin.invoice') }} {{ __('admin.number') }} #{{ $invoice->id }}
                        </h3>

                        {{-- الأزرار الرئيسية --}}
                        <div class="btn-group">
                            @if ($invoice->status->value !== 'paid')
                                <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> {{ __('admin.edit') }}
                                </a>
                            @endif
                            <button type="button" class="btn btn-secondary btn-sm" onclick="window.print();">
                                <i class="fas fa-print"></i> {{ __('admin.print') }}
                            </button>

                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> {{ __('admin.delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div id="printable-content">


                    {{-- تفاصيل الفاتورة والمريض والزيارة --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">

                                {{-- معلومات الفاتورة العامة --}}
                                <div class="col-md-4">
                                    <h5><i class="fas fa-file-invoice text-info"></i> {{ __('admin.invoice_information') }}
                                    </h5>
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item"><b>ID:</b> <span
                                                class="float-left">{{ $invoice->id }}</span></li>
                                        <li class="list-group-item"><b>{{ __('admin.invoice_date') }}:</b> <span
                                                class="float-left">{{ $invoice->invoice_date }}</span></li>
                                        <li class="list-group-item"><b>{{ __('admin.payment_status') }}:</b>
                                            <span class="float-left">
                                                @php
                                                    $statusClass =
                                                        [
                                                            'pending' => 'warning',
                                                            'paid' => 'success',
                                                            'canceled' => 'danger',
                                                        ][$invoice->status->value] ?? 'secondary';
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $statusClass }}">{{ $invoice->status->value }}</span>
                                            </span>
                                        </li>
                                        <li class="list-group-item"><b>{{ __('admin.payment_method') }}:</b> <span
                                                class="float-left">{{ $invoice->payment_method->value }}</span></li>
                                    </ul>
                                </div>

                                {{-- معلومات المريض --}}
                                <div class="col-md-4">
                                    <h5><i class="fas fa-user-injured text-primary"></i> {{ __('admin.patient') }}</h5>
                                    @if ($invoice->patient)
                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item"><b>{{ __('admin.name') }}:</b> <a
                                                    href="{{ route('admin.users.show', $invoice->patient->slug) }}"
                                                    class="float-left">{{ $invoice->patient->username }}</a></li>
                                            <li class="list-group-item"><b>{{ __('admin.nationality') }}:</b> <span
                                                    class="float-left">{{ $invoice->patient->nationality ?? '-' }}</span>
                                            </li>
                                            <li class="list-group-item"><b>{{ __('admin.mobile') }}:</b> <span
                                                    class="float-left">{{ $invoice->patient->mobile_number ?? '-' }}</span>
                                            </li>
                                            <li class="list-group-item"><b>{{ __('admin.email') }}:</b> <span
                                                    class="float-left">{{ $invoice->patient->email ?? '-' }}</span></li>
                                        </ul>
                                    @else
                                        <p class="text-danger">{{ __('admin.no_patient_data') }}</p>
                                    @endif
                                </div>

                                {{-- معلومات الزيارة المرتبطة --}}
                                <div class="col-md-4">
                                    <h5><i class="fas fa-stethoscope text-secondary"></i>
                                        {{ __('admin.related_visit_info') }}</h5>
                                    @if ($invoice->visit)
                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item"><b>{{ __('admin.visit_number_short') }}:</b> <a
                                                    href="{{ route('admin.visits.show', $invoice->visit->id) }}"
                                                    class="float-left">#{{ $invoice->visit->id }}</a></li>
                                            <li class="list-group-item"><b>{{ __('admin.visit_date') }}:</b> <span
                                                    class="float-left">{{ $invoice->visit->visit_date }}</span></li>
                                            {{-- بما أن الزيارة مرتبطة، من المفترض أن يكون الطبيب متاحاً عبر علاقة Visit->doctor
                                        --}}
                                            <li class="list-group-item"><b>{{ __('admin.doctor') }}:</b>
                                                @if ($invoice->visit->doctor)
                                                    <a href="{{ route('admin.users.show', $invoice->visit->doctor->slug) }}"
                                                        class="float-left">{{ $invoice->visit->doctor->username }}</a>
                                                @else
                                                    <span class="float-left">-</span>
                                                @endif
                                            </li>
                                        </ul>
                                    @else
                                        <p class="text-warning">{{ __('admin.no_related_visit_invoice') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- جدول عناصر الفاتورة --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title mb-0"><i class="fas fa-list-alt"></i> {{ __('admin.invoice_items') }}
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 50%">{{ __('admin.service_description') }}</th>
                                            <th class="text-center">{{ __('admin.quantity') }}</th>
                                            <th class="text-right">{{ __('admin.unit_price') }}</th>
                                            <th class="text-right">{{ __('admin.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($invoice->items as $item)
                                            <tr>
                                                <td>{{ $item->description }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                {{-- سعر الوحدة = المبلغ الإجمالي للعنصر / الكمية --}}
                                                @php
                                                    $unitPrice =
                                                        $item->quantity > 0
                                                            ? $item->amount / $item->quantity
                                                            : $item->amount;
                                                @endphp
                                                <td class="text-right">{{ number_format($unitPrice, 2) }}</td>
                                                {{-- المبلغ الإجمالي للعنصر هو amount في جدول invoice_items --}}
                                                <td class="text-right">
                                                    <strong>{{ number_format($item->amount, 2) }}</strong>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    {{ __('admin.no_items') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- ملخص المبالغ --}}
                    <div class="row justify-content-end ">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="card-title mb-0"><i class="fas fa-calculator"></i>
                                        {{ __('admin.payment_summary') }}</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            {{-- المجموع الفرعي (يساوي total_amount) --}}
                                            <th>{{ __('admin.subtotal') }}:</th>
                                            <td class="text-right">{{ number_format($invoice->total_amount ?? 0, 2) }} ر.س
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('admin.discount') }}:</th>
                                            <td class="text-right text-danger">
                                                ({{ number_format($invoice->discount ?? 0, 2) }}
                                                ر.س)</td>
                                        </tr>
                                        {{-- ملاحظة: لا يوجد حقل صريح للضريبة (tax_amount) في جدول invoices لذا افترضنا أن
                                    final_amount هو الإجمالي بعد الخصم --}}
                                        <tr class="">
                                            <th class="h4">{{ __('admin.final_amount_due') }}:</th>
                                            <td class="text-right h4">
                                                <strong>{{ number_format($invoice->final_amount ?? 0, 2) }} ر.س</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 no-print">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="card-title mb-0"><i class="fas fa-history"></i>
                                        {{ __('admin.creation_update_info') }}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>{{ __('admin.created_at') }}:</strong>
                                        {{ $invoice->created_at }}</p>
                                    <p class="mb-1"><strong>{{ __('admin.created_by') }}:</strong>
                                        @if ($invoice->createdBy)
                                            <a
                                                href="{{ route('admin.users.show', $invoice->createdBy->slug) }}">{{ $invoice->createdBy->username }}</a>
                                        @else
                                            <span class="text-muted">{{ __('admin.unknown') }}</span>
                                        @endif
                                    </p>
                                    @if ($invoice->updatedBy)
                                        <p class="mb-0"><strong>{{ __('admin.last_update') }}:</strong>
                                            {{ $invoice->updated_at }}</p>
                                        <p class="mb-0"><strong>{{ __('admin.updated_by') }}:</strong> <a
                                                href="{{ route('admin.users.show', $invoice->updatedBy->slug) }}">{{ $invoice->updatedBy->username }}</a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Delete Modal --}}
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="deleteModalLabel">
                                            {{ __('admin.confirm_delete_invoice') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <p>
                                            {{ __('admin.confirm_delete_invoice_message') }}
                                            <strong>#{{ $invoice->id }}</strong>
                                            {{ __('admin.for_patient') }}
                                            <strong>{{ $invoice->patient->username ?? __('admin.not_specified') }}</strong>؟
                                        </p>
                                        <p class="text-danger">{{ __('admin.cannot_undo') }}</p>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>

                                        <form action="{{ route('admin.invoices.destroy', $invoice->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger">{{ __('admin.delete_invoice') }}</button>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        </div>
                    @endsection
