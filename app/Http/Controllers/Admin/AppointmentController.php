<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Http\Requests\Appointment\UpdateAppointmentRequest;
use App\Http\Resources\Admin\AppointmentResource;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // لو فيه طلب AJAX
        if ($request->ajax()) {
            $query = Appointment::with('patient');

            // فلترة حسب status
            if ($request->status) {
                $query->where('status', $request->status);
            }

            // فلترة حسب التاريخ
            if ($request->from_date && $request->to_date) {
                $query->whereBetween('date', [$request->from_date, $request->to_date]);
            }

            // فلترة حسب المريض
            if ($request->patient_id) {
                $query->where('patient_id', $request->patient_id);
            }

            // استخدام الـ Resource
            $appointments = AppointmentResource::collection($query->get())->toArray($request);

            return datatables()->of($appointments)
                ->addColumn('patient_name', function ($appointment) {
                    return $appointment['patient']['username'] ?? '';
                })
                ->addColumn('action', function ($appointment) {
                    return '
                    <a href="'.route('admin.appointments.show', $appointment['id']).'" class="btn btn-sm btn-primary">View</a>
                    <a href="'.route('admin.appointments.edit', $appointment['id']).'" class="btn btn-sm btn-warning">Edit</a>
                    <button type="button" class="btn btn-sm btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteAppointmentModal" 
                        data-id="'.$appointment['id'].'" 
                        data-patient="'.($appointment['patient']['username'] ?? '-').'">
                        Delete
                    </button>
                ';
                })
                ->make(true);
        }

        // للإرسال للـ Blade
        $statuses = AppointmentStatus::values();

        return view('admin.appointments.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $patient = null;
        $patientId = $request->query('patient_id'); // جلب ID المريض من الـ URL

        // إذا تم تمرير patient_id في الـ Query String
        if ($patientId) {
            $patient = User::where('type', 'patient')->find($patientId);
            // يجب التأكد من أن المستخدم موجود ونوعه 'patient'
        }

        // إعداد الحالات الافتراضية للحجز
        $statuses = AppointmentStatus::values();

        return view('admin.appointments.create', compact('patient', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request) // 👈 نستخدم StoreAppointmentRequest هنا
    {
        // 1. حساب وقت النهاية (افتراض 15 دقيقة)
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $duration = config('app_settings.appointments.duration_minutes', 15);
        $endTime = $startTime->copy()->addMinutes($duration)->format('H:i');

        // 2. التحقق من التعارض (نفس منطق التحديث ولكن بدون تجاهل ID معين)
        $conflict = Appointment::where('date', $request->date)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime->format('H:i'), $endTime])
                    ->orWhereBetween('end_time', [$startTime->format('H:i'), $endTime])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<=', $startTime->format('H:i'))
                            ->where('end_time', '>=', $endTime);
                    });
            })->exists();

        if ($conflict) {
            return back()
                ->withErrors(['start_time' => __('admin.appointment_conflict_same_day')])
                ->withInput();
        }

        // 3. إنشاء الحجز
        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'date' => $request->date,
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.appointments.show', $appointment->id)
            ->with('success', __('admin.appointment_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appointment = Appointment::with('patient', 'visit')->findOrFail($id);

        // استخدام Resource
        $appointmentResource = new AppointmentResource($appointment);

        // تحويل الـ Resource لـ array
        $appointmentData = $appointmentResource->toArray(request());

        $statuses = AppointmentStatus::values();

        return view('admin.appointments.show', [
            'appointment' => (object) $appointmentData, // هنجعلها object عشان Blade يقدر يتعامل زي الـ Model
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appointment = Appointment::with('patient')->findOrFail($id);
        $appointmentResource = new AppointmentResource($appointment);
        $appointmentData = (object) $appointmentResource->toArray(request());
        $statuses = AppointmentStatus::values();

        return view('admin.appointments.edit', [
            'appointment' => $appointmentData,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        // تحويل start_time لCarbon
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);

        $duration = config('app_settings.appointments.duration_minutes', 15);
        $endTime = $startTime->copy()->addMinutes($duration)->format('H:i');

        // التحقق من التعارض مع أي حجز آخر لنفس اليوم
        $conflict = Appointment::where('date', $request->date)
            ->where('id', '!=', $appointment->id)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime->format('H:i'), $endTime])
                    ->orWhereBetween('end_time', [$startTime->format('H:i'), $endTime])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<=', $startTime->format('H:i'))
                            ->where('end_time', '>=', $endTime);
                    });
            })->exists();

        if ($conflict) {
            return back()
                ->withErrors(['start_time' => __('admin.appointment_conflict')])
                ->withInput();
        }

        // تحديث الحجز
        $appointment->update([
            // patient_id مش بيتغير
            'date' => $request->date,
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.appointments.show', $appointment->id)
            ->with('success', __('admin.appointment_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        try {
            $appointment->delete();

            return redirect()->route('admin.appointments.index')
                ->with('success', __('admin.appointment_deleted_successfully'));
        } catch (\Exception $e) {
            // لو في خطأ
            return redirect()->route('admin.appointments.show', $appointment->id)
                ->with('error', __('admin.error_deleting_appointment') . ': ' . $e->getMessage());
        }
    }
}
