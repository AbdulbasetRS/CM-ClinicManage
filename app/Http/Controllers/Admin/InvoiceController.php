<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod; // سنقوم بإنشاء هذا الطلب لاحقاً
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Admin\Invoice\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource (قائمة الفواتير).
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Invoice::select('invoices.*')
                ->with(['patient', 'visit'])
                ->leftJoin('users as patient', 'invoices.patient_id', '=', 'patient.id');

            if ($request->status) {
                $query->where('invoices.status', $request->status);
            }

            if ($request->payment_method) {
                $query->where('payment_method', $request->payment_method);
            }

            if ($request->from_date && $request->to_date) {
                $query->whereBetween('invoice_date', [$request->from_date, $request->to_date]);
            }

            if ($request->patient_id) {
                $query->where('invoices.patient_id', $request->patient_id);
            }

            return datatables()->of($query)
                ->addColumn('patient_name', function ($invoice) {
                    return $invoice->patient ? $invoice->patient->username : '-';
                })
                ->editColumn('status', function ($invoice) {
                    return $invoice->status->value;
                })
                ->editColumn('payment_method', function ($invoice) {
                    return $invoice->payment_method->value;
                })
                ->editColumn('total_amount', function ($invoice) {
                    return number_format($invoice->total_amount, 2);
                })
                ->editColumn('final_amount', function ($invoice) {
                    return number_format($invoice->final_amount, 2);
                })
                ->addColumn('action', function ($invoice) {
                    $editBtn = '';
                    if ($invoice->status->value !== 'paid') {
                        $editBtn = '<a href="'.route('admin.invoices.edit', $invoice->id).'" class="btn btn-sm btn-warning">'.__('admin.edit').'</a>';
                    }

                    return '
                        <a href="'.route('admin.invoices.show', $invoice->id).'" class="btn btn-sm btn-primary">'.__('admin.show').'</a>
                        '.$editBtn.'
                        <button type="button" class="btn btn-sm btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteInvoiceModal" 
                            data-id="'.$invoice->id.'" 
                            data-patient="'.($invoice->patient ? $invoice->patient->username : '-').'">
                            '.__('admin.delete').'
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $statuses = InvoiceStatus::values();
        $paymentMethods = PaymentMethod::values();

        return view('admin.invoices.index', compact('statuses', 'paymentMethods'));
    }

    /**
     * Show the form for creating a new resource (شاشة إنشاء فاتورة).
     */
    public function create(?Visit $visit = null, ?User $user = null)
    {
        // نحتاج لجلب الحالات وطرق الدفع للعرض في الـ Blade
        $statuses = InvoiceStatus::values();
        $paymentMethods = PaymentMethod::values();

        // يمكن تمرير معرف زيارة أو مريض
        $visitId = request('visit_id');
        $patientId = request('patient_id');

        // إذا تم تمرير الزيارة عبر الرابط (Route Model Binding) نستخدمها
        // وإلا نتحقق من الـ Request
        $visit = $visit ?? ($visitId ? Visit::find($visitId) : null);

        // إذا تم تمرير المستخدم عبر الرابط (Route Model Binding) نستخدمه
        // وإلا نتحقق من الـ Request أو الزيارة
        $patient = $user ?? ($patientId ? User::find($patientId) : ($visit ? $visit->patient : null));

        return view('admin.invoices.create', compact('statuses', 'paymentMethods', 'visit', 'patient'));
    }

    /**
     * Store a newly created resource in storage (حفظ فاتورة جديدة).
     */
    public function store(StoreInvoiceRequest $request)
    {
        // يتم التعامل مع إنشاء الفاتورة وبنودها داخل معاملة قاعدة البيانات (Transaction)
        DB::beginTransaction();

        try {
            // حساب الإجماليات (قد يتم تعديلها بناءً على المنطق في الـ Request أو الـ Frontend)
            $totalAmount = 0; // سيتم تحديثه لاحقاً
            $discount = $request->input('discount', 0);

            $invoice = Invoice::create([
                'patient_id' => $request->patient_id,
                'visit_id' => $request->visit_id,
                'total_amount' => $totalAmount, // قيمة مؤقتة
                'discount' => $discount,
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'invoice_date' => $request->invoice_date,
                'final_amount' => 0, // قيمة مؤقتة
            ]);

            // إضافة بنود الفاتورة
            $itemsData = $request->items; // يجب أن تكون مصفوفة من البنود

            foreach ($itemsData as $itemData) {
                // قد نحتاج إلى استخدام InvoiceItemController@store هنا إذا كان يتم الإرسال عبر API منفصل
                // ولكن نفترض أن البنود تأتي مع الطلب
                $invoice->items()->create([
                    'description' => $itemData['description'],
                    'amount' => $itemData['amount'],
                    'quantity' => $itemData['quantity'] ?? 1,
                ]);
                $totalAmount += ($itemData['amount'] * ($itemData['quantity'] ?? 1));
            }

            // تحديث قيم الإجماليات بعد إضافة البنود
            $finalAmount = $totalAmount - $discount;
            $invoice->update([
                'total_amount' => $totalAmount,
                'final_amount' => max(0, $finalAmount), // التأكد أن القيمة النهائية ليست سالبة
            ]);

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', __('admin.invoice_created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();

            // يجب تسجيل الخطأ $e->getMessage()
            return redirect()->back()->withInput()->with('error', __('admin.error_creating_invoice'));
        }
    }

    /**
     * Display the specified resource (عرض فاتورة محددة).
     */
    public function show(Invoice $invoice)
    {
        // تحميل العلاقات المطلوبة
        $invoice->load(['patient', 'visit', 'items', 'createdBy', 'updatedBy']);

        // return $invoice;
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource (شاشة تعديل فاتورة).
     */
    public function edit(Invoice $invoice)
    {

        abort_if($invoice->status === InvoiceStatus::PAID, 400, __('admin.cannot_edit_paid_invoice'));
        $statuses = InvoiceStatus::values();
        $paymentMethods = PaymentMethod::values();

        $invoice->load('items');

        return view('admin.invoices.edit', compact('invoice', 'statuses', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage (تحديث فاتورة).
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        if ($invoice->status === InvoiceStatus::PAID) {
            abort(400, __('admin.cannot_edit_paid_invoice'));
        }
        // abort_if($invoice->status->value === InvoiceStatus::PAID, 400, 'لا يمكن تعديل الفاتورة المدفوعة.');
        // Update logic is often complex because it involves recalculating items and totals.
        DB::beginTransaction();

        try {
            $invoice->update($request->validated());

            // 1. Get items from request
            $itemsData = $request->input('items', []);

            // 2. Get IDs of items to keep
            $keepIds = [];
            foreach ($itemsData as $itemData) {
                if (isset($itemData['id'])) {
                    $keepIds[] = $itemData['id'];
                }
            }

            // 3. Delete items not in the request
            $invoice->items()->whereNotIn('id', $keepIds)->delete();

            // 4. Update existing items or Create new ones
            foreach ($itemsData as $itemData) {
                if (isset($itemData['id'])) {
                    // Update existing
                    $invoice->items()->where('id', $itemData['id'])->update([
                        'description' => $itemData['description'],
                        'amount' => $itemData['amount'] * $itemData['quantity'], // Store Total Amount
                        'quantity' => $itemData['quantity'],
                    ]);
                } else {
                    // Create new
                    $invoice->items()->create([
                        'description' => $itemData['description'],
                        'amount' => $itemData['amount'] * $itemData['quantity'], // Store Total Amount
                        'quantity' => $itemData['quantity'],
                    ]);
                }
            }

            // 5. Recalculate Totals
            // Note: We store 'amount' as Total Line Amount in DB (Unit Price * Quantity)
            // But the form sends 'amount' as Unit Price.
            // So we multiplied above.

            $totalAmount = $invoice->items()->sum('amount');
            $finalAmount = $totalAmount - $invoice->discount;

            $invoice->update([
                'total_amount' => $totalAmount,
                'final_amount' => max(0, $finalAmount),
            ]);

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', __('admin.invoice_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', __('admin.error_updating_invoice').' '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage (حذف فاتورة).
     */
    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();

            return redirect()->route('admin.invoices.index')
                ->with('success', __('admin.invoice_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('admin.error_deleting_invoice'));
        }
    }

    public function statistics(Request $request)
    {
        if ($request->ajax()) {
            $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
            $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

            // Group by date and sum final_amount
            $query = Invoice::select(
                DB::raw('DATE(invoice_date) as date'),
                DB::raw('SUM(final_amount) as total_amount'),
                DB::raw('COUNT(*) as count')
            )
                ->whereBetween('invoice_date', [$startDate, $endDate]);

            // Filter by Status
            if ($request->status) {
                $query->where('status', $request->status);
            } else {
                // Default to 'paid' if no status is selected, or remove this to show all by default?
                // Given the context of "Revenue", usually we only care about PAID.
                // But if the user wants to filter, they might want to see Pending too.
                // Let's default to 'paid' if not specified, to match previous behavior.
                // BUT, if the user explicitly selects "All" (empty value), we should show all.
                // The view will send empty string for "All".
                // So if $request->status is present (even if empty string? No, empty string is falsy).
                // Let's assume if it's NOT present in request at all, we default to paid.
                // If it IS present but empty, we show all.
                // Actually, simpler: If the user selects a status, filter by it.
                // If they don't select one (or select "All"), show ALL?
                // The previous code FORCED 'paid'.
                // "The user wants to determine... status".
                // I'll check if 'status' is in the request.
                if ($request->has('status') && $request->status != '') {
                    $query->where('status', $request->status);
                } else {
                    // If not specified, default to 'paid' to preserve original "Revenue" chart meaning?
                    // Or should I default to All?
                    // Let's default to 'paid' for the initial load (where params might be missing),
                    // but if the user explicitly clears the filter, they get all.
                    // Actually, let's just default to 'paid' in the VIEW's initial state.
                    // So the request will send 'paid'.
                }
            }

            // Filter by Payment Method
            if ($request->payment_method) {
                $query->where('payment_method', $request->payment_method);
            }

            $invoices = $query->groupBy('date')
                ->orderBy('date')
                ->get();

            // Prepare data for Chart.js
            $labels = [];
            $data = [];
            $countData = [];

            // Fill in missing dates with 0
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $dateString = $currentDate->format('Y-m-d');
                $record = $invoices->firstWhere('date', $dateString);

                $labels[] = $dateString;
                $data[] = $record ? $record->total_amount : 0;
                $countData[] = $record ? $record->count : 0;

                $currentDate->addDay();
            }

            return response()->json([
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => __('admin.revenue_final_amount'),
                        'data' => $data,
                        'borderColor' => '#4e73df',
                        'backgroundColor' => 'rgba(78, 115, 223, 0.05)',
                        'borderWidth' => 2,
                        'pointRadius' => 3,
                        'pointHoverRadius' => 5,
                        'fill' => true,
                        'tension' => 0.3,
                    ],
                ],
                'summary' => [
                    'total_revenue' => number_format($invoices->sum('total_amount'), 2),
                    'total_invoices' => $invoices->sum('count'),
                ],
            ]);
        }

        $statuses = InvoiceStatus::values();
        $paymentMethods = PaymentMethod::values();

        return view('admin.invoices.statistics', compact('statuses', 'paymentMethods'));
    }
}
