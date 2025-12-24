<?php

namespace App\Http\Requests\Appointment;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // يمكنك تعديل هذا الشرط بناءً على صلاحيات المستخدم لديك
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:users,id'],
            'status' => ['required', Rule::in(AppointmentStatus::values())],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Configure the validator instance.
     * * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            
            // تحقق من وجود البيانات المطلوبة أولاً
            if ($validator->errors()->any()) {
                return;
            }

            $date = $this->input('date');
            $startTime = $this->input('start_time');
            $patientId = $this->input('patient_id');

            // 1. ⚠️ التحقق من أن الحجز ليس في الماضي
            $appointmentDateTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $startTime);

            if ($appointmentDateTime->isPast()) {
                $validator->errors()->add('date', 'يجب أن يكون تاريخ ووقت الحجز في المستقبل.');
                $validator->errors()->add('start_time', 'يجب أن يكون تاريخ ووقت الحجز في المستقبل.');
            }

            // 2. 🗓️ التحقق من عدم وجود حجز آخر لنفس المريض في نفس اليوم
            // نستبعد حالتي (Cancelled, Completed) لأن المريض قد يحتاج لزيارة أخرى بعد الانتهاء
            $existingAppointment = Appointment::where('patient_id', $patientId)
                ->where('date', $date)
                ->whereNotIn('status', ['Cancelled', 'Completed'])
                ->first();

            if ($existingAppointment) {
                // رسالة مخصصة مع توجيه
                $message = '
                    عفواً، لا يمكنك إنشاء حجز جديد في نفس اليوم لهذا المريض. يوجد حجز رقم 
                    #' . $existingAppointment->id . ' لا يزال في حالة ('. $existingAppointment->status->value .')
                    للتعديل: <a href="'. route('admin.appointments.edit', $existingAppointment->id) .'" target="_blank">اضغط هنا</a>،
                    أو لإنشاء زيارة جديدة: <a href="'. route('admin.visits.create', ['appointment_id' => $existingAppointment->id]) .'" target="_blank">اضغط هنا</a>.
                ';
                
                $validator->errors()->add('date', $message);
            }
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'patient_id.required' => 'يجب تحديد المريض.',
            'date.required' => 'يجب تحديد تاريخ الحجز.',
            'date.after_or_equal' => 'تاريخ الحجز يجب أن يكون اليوم أو في تاريخ لاحق.',
            'start_time.required' => 'يجب تحديد وقت الحجز.',
            'start_time.date_format' => 'صيغة وقت الحجز غير صحيحة.',
        ];
    }
}