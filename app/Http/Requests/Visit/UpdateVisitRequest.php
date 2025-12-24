<?php

namespace App\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVisitRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'visit_date' => 'required|date',
            'status' => ['required', Rule::in(\App\Enums\VisitStatus::values())],
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'visit_date.required' => 'حقل تاريخ الزيارة مطلوب.',
            'visit_date.date'     => 'التاريخ المدخل غير صالح.',
            'status.required'     => 'حقل الحالة مطلوب.',
            'status.in'           => 'الحالة المختارة غير صالحة.',
            'symptoms.string'     => 'الأعراض يجب أن تكون نص.',
            'diagnosis.string'    => 'التشخيص يجب أن تكون نص.',
            'treatment_plan.string' => 'الخطة العلاجية يجب أن تكون نص.',
            'notes.string'        => 'ملاحظات يجب أن تكون نص.',
        ];
    }

    public function attributes(): array
    {
        return [
            'visit_date'     => 'تاريخ الزيارة',
            'status'         => 'الحالة',
            'symptoms'       => 'الأعراض',
            'diagnosis'      => 'التشخيص',
            'treatment_plan' => 'الخطة العلاجية',
            'notes'          => 'ملاحظات',
        ];
    }
}
