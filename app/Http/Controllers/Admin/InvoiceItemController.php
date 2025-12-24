<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InvoiceItem\StoreInvoiceItemRequest; // سنقوم بإنشاء هذا الطلب
use App\Http\Requests\Admin\InvoiceItem\UpdateInvoiceItemRequest;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class InvoiceItemController extends Controller
{
    /**
     * Display a listing of the resource. (عادة لا يستخدم)
     */
    public function index()
    {
        // عادةً ما يتم جلب البنود عبر API مرتبط بمعرف الفاتورة: /api/invoices/{invoice_id}/items
        return response()->json(['message' => __('admin.not_implemented_for_standalone_view')], 404);
    }

    /**
     * Show the form for creating a new resource. (عادة لا يستخدم)
     */
    public function create()
    {
        // البند يُضاف داخل شاشة الفاتورة، لا حاجة لشاشة إنشاء منفصلة
        return response()->json(['message' => __('admin.not_implemented_for_standalone_view')], 404);
    }

    /**
     * Store a newly created resource in storage (إضافة بند جديد).
     */
    public function store(StoreInvoiceItemRequest $request, Invoice $invoice)
    {
        // هذا الإجراء يفترض أن المسار هو /admin/invoices/{invoice}/items
        DB::beginTransaction();
        try {
            $item = $invoice->items()->create($request->validated());

            // تحديث إجماليات الفاتورة
            $invoice->updateTotals(); // سنفترض وجود هذه الدالة في موديل Invoice

            DB::commit();

            return response()->json(['message' => __('admin.invoice_item_added_successfully'), 'item' => $item], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => __('admin.error_adding_invoice_item')], 500);
        }
    }

    /**
     * Display the specified resource. (عرض بند محدد - نادر الاستخدام)
     */
    public function show(InvoiceItem $invoiceItem)
    {
        return response()->json($invoiceItem);
    }

    /**
     * Show the form for editing the specified resource. (عادة لا يستخدم)
     */
    public function edit(InvoiceItem $invoiceItem)
    {
        // البند يُعدل داخل شاشة الفاتورة
        return response()->json(['message' => __('admin.not_implemented_for_standalone_view')], 404);
    }

    /**
     * Update the specified resource in storage (تحديث بند موجود).
     */
    public function update(UpdateInvoiceItemRequest $request, InvoiceItem $invoiceItem)
    {
        // يجب التحقق من أن المستخدم لديه صلاحية لتعديل الفاتورة الأصلية
        DB::beginTransaction();
        try {
            $invoiceItem->update($request->validated());

            // تحديث إجماليات الفاتورة
            $invoiceItem->invoice->updateTotals(); // سنفترض وجود هذه الدالة في موديل Invoice

            DB::commit();

            return response()->json(['message' => __('admin.invoice_item_updated_successfully'), 'item' => $invoiceItem]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => __('admin.error_updating_invoice_item')], 500);
        }
    }

    /**
     * Remove the specified resource from storage (حذف بند).
     */
    public function destroy(InvoiceItem $invoiceItem)
    {
        DB::beginTransaction();
        try {
            $invoice = $invoiceItem->invoice;
            $invoiceItem->delete();

            // تحديث إجماليات الفاتورة
            $invoice->updateTotals(); // سنفترض وجود هذه الدالة في موديل Invoice

            DB::commit();

            return response()->json(['message' => __('admin.invoice_item_deleted_successfully')]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => __('admin.error_deleting_invoice_item')], 500);
        }
    }
}
