<?php

namespace App\Http\Requests\Admin\Invoice;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // يجب أن تتأكد هنا من صلاحية المستخدم للتعديل على الفاتورة
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // جميع الحقول هنا يجب أن تكون حاضرة في الـ Request في العادة، لكن نجعلها nullable في حالة عدم إرسالها كلها
        // ونستخدم 'sometimes' للتحقق فقط إذا كان الحقل موجوداً في الطلب
        
        return [
            // حقول الفاتورة الرئيسية
            'patient_id' => ['sometimes', 'required', 'exists:users,id'],
            'visit_id' => ['nullable', 'exists:visits,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['sometimes', 'required', Rule::in(InvoiceStatus::values())],
            'payment_method' => ['sometimes', 'required', Rule::in(PaymentMethod::values())],
            'invoice_date' => ['sometimes', 'required', 'date'],
            
            // بنود الفاتورة - قد تكون مصفوفة فارغة في حالة حذف جميع البنود
            'items' => ['nullable', 'array'],
            
            // البنود الفردية:
            // .id: يجب أن يكون موجوداً لبنود الفاتورة التي يتم تحديثها (موجودة بالفعل في قاعدة البيانات)
            // .description: مطلوب
            // .amount: مطلوب
            'items.*.id' => ['nullable', 'exists:invoice_items,id'], 
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.amount' => ['required', 'numeric', 'min:0.01'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.*.description.required' => 'وصف البند مطلوب.',
            'items.*.amount.required' => 'قيمة البند مطلوبة.',
            'items.*.amount.numeric' => 'قيمة البند يجب أن تكون رقماً.',
            'items.*.id.exists' => 'معرّف بند الفاتورة غير صحيح.',
        ];
    }
}