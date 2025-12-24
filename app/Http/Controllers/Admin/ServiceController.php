<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $services = Service::query();
            
            // Filter by status if provided
            if ($request->has('status')) {
                $services->where('status', $request->status);
            }
            
            return datatables()->of($services)
                ->addColumn('action', function ($service) {
                    return '
                        <button type="button" class="btn btn-sm btn-warning edit-service" 
                            data-id="'.$service->id.'"
                            data-name="'.$service->name.'"
                            data-description="'.$service->description.'"
                            data-price="'.$service->price.'"
                            data-status="'.$service->status.'">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-service" data-id="'.$service->id.'">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->editColumn('status', function ($service) {
                    return $service->status
                        ? '<span class="badge bg-success">'.__('admin.active').'</span>'
                        : '<span class="badge bg-danger">'.__('admin.inactive').'</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.services.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $data = $request->only(['name', 'description', 'price']);
        $data['status'] = $request->has('status') ? 1 : 0;

        Service::create($data);

        return response()->json(['success' => __('admin.service_added_successfully')]);
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $data = $request->only(['name', 'description', 'price']);
        $data['status'] = $request->has('status') ? 1 : 0;

        $service->update($data);

        return response()->json(['success' => __('admin.service_updated_successfully')]);
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json(['success' => __('admin.service_deleted_successfully')]);
    }
}
