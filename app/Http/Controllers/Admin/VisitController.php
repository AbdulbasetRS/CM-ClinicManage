<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VisitStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Visit\CreateVisitRequest;
use App\Http\Requests\Visit\UpdateVisitRequest;
use App\Http\Resources\Admin\VisitResource;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = Visit::with(['patient', 'doctor', 'appointment']);

            // فلترة حسب التاريخ
            if ($request->from_date && $request->to_date) {
                $query->whereBetween('visit_date', [$request->from_date, $request->to_date]);
            }

            // فلترة حسب المريض
            if ($request->patient_id) {
                $query->where('patient_id', $request->patient_id);
            }

            // فلترة حسب الحالة
            if ($request->status) {
                $query->where('status', $request->status);
            }

            // استخدام Resource
            $visits = VisitResource::collection($query->get())->toArray($request);

            return datatables()->of($visits)
                ->addColumn(
                    'patient_name',
                    fn ($visit) => $visit['patient']['username'] ?? '-'
                )
                ->addColumn(
                    'doctor_name',
                    fn ($visit) => $visit['doctor']['username'] ?? '-'
                )
                ->addColumn(
                    'appointment_id',
                    fn ($visit) => $visit['appointment']['id'] ?? '-'
                )
                ->addColumn('action', function ($visit) {
                    return '
                    <a href="'.route('admin.visits.show', $visit['id']).'" class="btn btn-sm btn-primary">View</a>
                    <a href="'.route('admin.visits.edit', $visit['id']).'" class="btn btn-sm btn-warning">Edit</a>
                    <button type="button" class="btn btn-sm btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteVisitModal" 
                        data-id="'.$visit['id'].'" 
                        data-patient="'.($visit['patient']['username'] ?? '-').'">
                        Delete
                    </button>
                ';
                })
                ->make(true);
        }

        $statuses = VisitStatus::values();

        return view('admin.visits.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $doctors = User::where('type', 'doctor')->get();
        $statuses = VisitStatus::values();
        $appointmentId = $request->get('appointment_id');
        $patientId = $request->get('patient_id');

        $appointment = null;
        $patient = null;
        $appointmentId = $request->query('appointment_id');
        $patientId = $request->query('patient_id');

        if ($appointmentId) {
            $appointment = Appointment::with('patient')->find($appointmentId);
            // لا نحتاج لتعيين $patient هنا لأننا سنستخدم $appointment->patient في Blade.
        } elseif ($patientId) {
            $patient = User::find($patientId);
        }

        return view('admin.visits.create', compact('doctors', 'statuses', 'appointment', 'patient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVisitRequest $request)
    {
        $visit = Visit::create($request->validated());

        return redirect()->route('admin.visits.show', $visit->id)
            ->with('success', __('admin.visit_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $visit = Visit::with(['patient', 'doctor', 'appointment', 'invoices', 'attachments.uploader'])->findOrFail($id);

        $statuses = VisitStatus::values();

        return view('admin.visits.show', [
            'visit' => $visit,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $visit = Visit::with(['patient', 'doctor', 'appointment'])->findOrFail($id);

        // باستخدام VisitResource
        $visitResource = new VisitResource($visit);
        $visitData = (object) $visitResource->toArray(request());

        $statuses = VisitStatus::values(); // لو عندك Enum

        return view('admin.visits.edit', [
            'visit' => $visitData,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVisitRequest $request, $id)
    {
        $visit = Visit::findOrFail($id);

        $visit->update($request->validated());

        return redirect()->route('admin.visits.show', $visit->id)
            ->with('success', __('admin.visit_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit)
    {
        try {
            $visit->delete();

            return redirect()
                ->route('admin.visits.index')
                ->with('success', __('admin.visit_deleted_successfully'));
        } catch (\Exception $e) {
            // لو في خطأ
            return redirect()->route('admin.visits.show', $visit->id)
                ->with('error', __('admin.error_deleting_visit').': '.$e->getMessage());
        }
    }

    /**
     * Update the status of the specified visit.
     */
    public function updateStatus(Request $request, Visit $visit)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', VisitStatus::values())],
        ]);

        $visit->update(['status' => $request->status]);

        return redirect()->back()->with('success', __('admin.visit_status_updated_successfully'));
    }

    /**
     * Display visit statistics with charts.
     */
    public function statistics(Request $request)
    {
        if ($request->ajax()) {
            $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
            $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

            // Group by date and count visits
            $query = Visit::select(
                DB::raw('DATE(visit_date) as date'),
                DB::raw('COUNT(*) as count')
            )
                ->whereBetween('visit_date', [$startDate, $endDate]);

            // Filter by Status
            if ($request->status) {
                $query->where('status', $request->status);
            }

            $visits = $query->groupBy('date')
                ->orderBy('date')
                ->get();

            // Prepare data for Chart.js
            $labels = [];
            $data = [];

            // Fill in missing dates with 0
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $dateString = $currentDate->format('Y-m-d');
                $record = $visits->firstWhere('date', $dateString);

                $labels[] = $dateString;
                $data[] = $record ? $record->count : 0;

                $currentDate->addDay();
            }

            return response()->json([
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => __('admin.visits_count'),
                        'data' => $data,
                        'borderColor' => '#1cc88a',
                        'backgroundColor' => 'rgba(28, 200, 138, 0.05)',
                        'borderWidth' => 2,
                        'pointRadius' => 3,
                        'pointHoverRadius' => 5,
                        'fill' => true,
                        'tension' => 0.3,
                    ],
                ],
                'summary' => [
                    'total_visits' => $visits->sum('count'),
                ],
            ]);
        }

        $statuses = VisitStatus::values();

        return view('admin.visits.statistics', compact('statuses'));
    }
}
