<?php

namespace App\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
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
            'patient_id.required' => 'اختر المريض.',
            'patient_id.exists' => 'المريض المختار غير موجود.',
            'doctor_id.required' => 'اختر الطبيب.',
            'doctor_id.exists' => 'الطبيب المختار غير موجود.',
            'visit_date.required' => 'حقل تاريخ الزيارة مطلوب.',
            'visit_date.date' => 'التاريخ المدخل غير صالح.',
            'status.required' => 'اختر الحالة.',
            'status.in' => 'الحالة المختارة غير صالحة.',
            'symptoms.string' => 'الأعراض يجب أن تكون نص.',
            'diagnosis.string' => 'التشخيص يجب أن يكون نص.',
            'treatment_plan.string' => 'الخطة العلاجية يجب أن تكون نص.',
            'notes.string' => 'الملاحظات يجب أن تكون نص.',
        ];
    }

    public function attributes(): array
    {
        return [
            'patient_id' => 'المريض',
            'doctor_id' => 'الطبيب',
            'appointment_id' => 'الموعد',
            'visit_date' => 'تاريخ الزيارة',
            'status' => 'الحالة',
            'symptoms' => 'الأعراض',
            'diagnosis' => 'التشخيص',
            'treatment_plan' => 'الخطة العلاجية',
            'notes' => 'الملاحظات',
        ];
    }
}
