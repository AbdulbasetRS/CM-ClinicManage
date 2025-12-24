<?php

namespace App\Http\Requests\Admin\Invoice;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:users,id'],
            'visit_id' => ['nullable', 'exists:visits,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(InvoiceStatus::values())],
            'payment_method' => ['required', Rule::in(PaymentMethod::values())],
            'invoice_date' => ['required', 'date'],
            
            // التحقق من بنود الفاتورة التي يتم إرسالها كمصفوفة
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.amount' => ['required', 'numeric', 'min:0.01'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'يجب أن تحتوي الفاتورة على بند واحد على الأقل.',
            'items.*.description.required' => 'وصف البند مطلوب.',
            'items.*.amount.required' => 'قيمة البند مطلوبة.',
            'items.*.amount.numeric' => 'قيمة البند يجب أن تكون رقماً.',
        ];
    }
}