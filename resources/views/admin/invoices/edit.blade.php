@extends('admin.structure')

@section('title', __('admin.edit') . ' ' . __('admin.invoice') . ' #' . $invoice->id)

@section('content')
    <div class="container">

        {{-- Alerts --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.invoices.update', $invoice->id) }}" method="POST" id="invoiceForm">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-12">

                    {{-- Card العنوان --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title mb-0"><i class="fas fa-edit"></i> {{ __('admin.edit') }}
                                {{ __('admin.invoice') }} {{ __('admin.number') }} #{{ $invoice->id }}
                            </h3>
                        </div>
                    </div>

                    <div class="row">
                        {{-- تفاصيل الفاتورة --}}
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-file-invoice"></i>
                                        {{ __('admin.invoice_data') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="invoice_date"
                                                class="form-label">{{ __('admin.invoice_date') }}</label>
                                            <input type="date" class="form-control" id="invoice_date" name="invoice_date"
                                                value="{{ old('invoice_date', $invoice->invoice_date) }}" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="status"
                                                class="form-label">{{ __('admin.invoice_status') }}</label>
                                            <select class="form-select" id="status" name="status" required>
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status }}"
                                                        {{ old('status', $invoice->status->value) == $status ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="payment_method"
                                                class="form-label">{{ __('admin.payment_method') }}</label>
                                            <select class="form-select" id="payment_method" name="payment_method" required>
                                                @foreach ($paymentMethods as $method)
                                                    <option value="{{ $method }}"
                                                        {{ old('payment_method', $invoice->payment_method) == $method ? 'selected' : '' }}>
                                                        {{ $method }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="discount" class="form-label">{{ __('admin.discount') }}
                                                (ر.س)</label>
                                            <input type="number" step="0.01" min="0" class="form-control"
                                                id="discount" name="discount"
                                                value="{{ old('discount', $invoice->discount) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- معلومات المريض والزيارة (للقراءة فقط) --}}
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-info-circle"></i>
                                        {{ __('admin.related_info') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('admin.patient') }}</label>
                                        <div class="form-control-plaintext">
                                            @if ($invoice->patient)
                                                <a href="{{ route('admin.users.show', $invoice->patient->slug) }}"
                                                    target="_blank">
                                                    {{ $invoice->patient->username }} <i
                                                        class="fas fa-external-link-alt small"></i>
                                                </a>
                                            @else
                                                <span class="text-danger">{{ __('admin.patient_not_available') }}</span>
                                            @endif
                                            <input type="hidden" name="patient_id" value="{{ $invoice->patient_id }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">{{ __('admin.visit') }}</label>
                                        <div class="form-control-plaintext">
                                            @if ($invoice->visit)
                                                <a href="{{ route('admin.visits.show', $invoice->visit->id) }}"
                                                    target="_blank">
                                                    {{ __('admin.visit') }} #{{ $invoice->visit->id }} <i
                                                        class="fas fa-external-link-alt small"></i>
                                                </a>
                                                <input type="hidden" name="visit_id" value="{{ $invoice->visit_id }}">
                                            @else
                                                <span class="text-muted">{{ __('admin.no_related_visit') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- جدول العناصر --}}
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="fas fa-list"></i> {{ __('admin.invoice_items') }}</h5>
                            <div>
                                <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal"
                                    data-bs-target="#selectServiceModal">
                                    <i class="fas fa-hand-holding-medical"></i> {{ __('admin.add_from_services') }}
                                </button>
                                <button type="button" class="btn btn-success btn-sm" id="addItemBtn">
                                    <i class="fas fa-plus"></i> {{ __('admin.add_item') }}
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="itemsTable">
                                    <thead class="table-active">
                                        <tr>
                                            <th style="width: 50%">{{ __('admin.item_description') }}</th>
                                            <th style="width: 15%">{{ __('admin.quantity') }}</th>
                                            <th style="width: 20%">{{ __('admin.price_sar') }}</th>
                                            <th style="width: 15%">{{ __('admin.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (old('items', $invoice->items) as $index => $item)
                                            <tr class="item-row">
                                                <td>
                                                    {{-- إذا كان البند موجوداً مسبقاً (له ID) نضعه في حقل مخفي --}}
                                                    @if (isset($item['id']) || is_object($item))
                                                        <input type="hidden" name="items[{{ $index }}][id]"
                                                            value="{{ is_array($item) ? $item['id'] : $item->id }}">
                                                    @endif

                                                    <input type="text" class="form-control"
                                                        name="items[{{ $index }}][description]"
                                                        value="{{ is_array($item) ? $item['description'] : $item->description }}"
                                                        required placeholder="وصف الخدمة أو العلاج">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control item-qty"
                                                        name="items[{{ $index }}][quantity]"
                                                        value="{{ is_array($item) ? $item['quantity'] : $item->quantity }}"
                                                        min="1" required>
                                                </td>
                                                <td>
                                                    {{-- 
                                                        ملاحظة: في العرض (show) يتم حساب سعر الوحدة بقسمة الإجمالي على الكمية.
                                                        هنا نفترض أن المستخدم يدخل سعر الوحدة (amount)
                                                        إذا كانت البيانات قادمة من الـ Model مباشرة، فإن $item->amount هو الإجمالي،
                                                        لذا يجب قسمته على الكمية لعرض سعر الوحدة الصحيح في حقل الإدخال.
                                                    --}}
                                                    @php
                                                        $amountValue = is_array($item)
                                                            ? $item['amount']
                                                            : $item->amount;
                                                        $qtyValue = is_array($item)
                                                            ? $item['quantity']
                                                            : $item->quantity;
                                                        // إذا كان كائن (من قاعدة البيانات) نقسم للحصول على سعر الوحدة
                                                        // إذا كان مصفوفة (من old input) نستخدم القيمة كما هي لأن المستخدم أدخل سعر الوحدة
                                                        if (is_object($item) && $qtyValue > 0) {
                                                            $unitPrice = $amountValue / $qtyValue;
                                                        } else {
                                                            $unitPrice = $amountValue;
                                                        }
                                                    @endphp
                                                    <input type="number" step="0.01" class="form-control item-price"
                                                        name="items[{{ $index }}][amount]"
                                                        value="{{ $unitPrice }}" min="0" required>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row justify-content-end text-end">
                                <div class="col-md-4">
                                    <h5>{{ __('admin.subtotal_expected') }}: <span id="totalPreview">0.00</span> ر.س</h5>
                                    <small class="text-muted">{{ __('admin.calculated_on_save') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- أزرار التحكم --}}
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ __('admin.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('admin.save') }} {{ __('admin.update') }}
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    {{-- Select Service Modal --}}
    <div class="modal fade" id="selectServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.select_service') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table id="services-selection-table" class="table table-bordered table-hover w-100">
                        <thead>
                            <tr>
                                <th>{{ __('admin.service_name') }}</th>
                                <th>{{ __('admin.price') }}</th>
                                <th>{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@section('main.script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('#itemsTable tbody');
            const addItemBtn = document.getElementById('addItemBtn');
            const totalPreview = document.getElementById('totalPreview');
            const discountInput = document.getElementById('discount');

            // دالة لحساب الإجمالي التقريبي
            function calculateTotal() {
                let total = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
                    const price = parseFloat(row.querySelector('.item-price').value) || 0;
                    total += qty * price;
                });

                const discount = parseFloat(discountInput.value) || 0;
                total = Math.max(0, total - discount);

                totalPreview.textContent = total.toFixed(2);
            }

            // إضافة مستمعي الأحداث لحقول الإدخال الموجودة
            function attachEvents() {
                document.querySelectorAll('.item-qty, .item-price').forEach(input => {
                    input.addEventListener('input', calculateTotal);
                });

                document.querySelectorAll('.remove-item').forEach(btn => {
                    btn.onclick = function() {
                        this.closest('tr').remove();
                        calculateTotal();
                    };
                });
            }

            // دالة لإضافة صف جديد
            function addNewRow(description = '', price = 0, isFromService = false) {
                const index = new Date().getTime() + Math.floor(Math.random() * 1000);
                const readonlyAttr = isFromService ? 'readonly' : '';
                const readonlyClass = isFromService ? 'locked' : '';
                const lockIcon = isFromService ?
                    '<span class="input-group-text"><i class="fas fa-lock"></i></span>' : '';
                const inputGroupStart = isFromService ? '<div class="input-group">' : '';
                const inputGroupEnd = isFromService ? '</div>' : '';

                const newRow = `
                    <tr class="item-row">
                        <td>
                            ${inputGroupStart}
                            ${lockIcon}
                            <input type="text" class="form-control ${readonlyClass}" name="items[${index}][description]" value="${description}" required placeholder="وصف الخدمة" ${readonlyAttr}>
                            ${inputGroupEnd}
                        </td>
                        <td>
                            <input type="number" class="form-control item-qty" name="items[${index}][quantity]" value="1" min="1" required>
                        </td>
                        <td>
                            ${inputGroupStart}
                            ${lockIcon}
                            <input type="number" step="0.01" class="form-control item-price ${readonlyClass}" name="items[${index}][amount]" value="${price}" min="0" required ${readonlyAttr}>
                            ${inputGroupEnd}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', newRow);

                const lastRow = tableBody.lastElementChild;
                lastRow.querySelector('.item-qty').addEventListener('input', calculateTotal);
                lastRow.querySelector('.item-price').addEventListener('input', calculateTotal);
                lastRow.querySelector('.remove-item').addEventListener('click', function() {
                    this.closest('tr').remove();
                    calculateTotal();
                });
                calculateTotal();
            }

            // إضافة بند جديد يدوي
            addItemBtn.addEventListener('click', function() {
                addNewRow('', 0, false);
            });

            discountInput.addEventListener('input', calculateTotal);

            // التشغيل الأولي
            attachEvents();
            calculateTotal();

            // Initialize DataTables for Service Selection
            $('#services-selection-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.services.index') }}",
                    data: function(d) {
                        d.status = 1; // فقط الخدمات النشطة
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button type="button" class="btn btn-sm btn-primary select-service" 
                                        data-name="${row.name}" 
                                        data-price="${row.price}">
                                        <i class="fas fa-check"></i> اختيار
                                    </button>`;
                        }
                    }
                ]
            });

            // Handle Service Selection
            $('#services-selection-table tbody').on('click', '.select-service', function() {
                var name = $(this).data('name');
                var price = $(this).data('price');

                addNewRow(name, price, true); // true = from service (readonly)
                $('#selectServiceModal').modal('hide');
                toastr.success('تم إضافة الخدمة للفاتورة');
            });
        });
    </script>
@endsection
@endsection
