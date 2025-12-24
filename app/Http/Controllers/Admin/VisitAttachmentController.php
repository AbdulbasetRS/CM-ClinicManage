<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AttachmentType;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;

class VisitAttachmentController extends Controller
{
    public function upload(Visit $visit)
    {
        $types = AttachmentType::cases();

        return view('admin.visits.attachments.upload', compact('visit', 'types'));
    }

    public function store(Request $request, Visit $visit)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'type' => ['required', new Enum(AttachmentType::class)],
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments/visits/' . $visit->id, 'public');

        $attachment = new Attachment();
        $attachment->visit_id = $visit->id;
        $attachment->patient_id = $visit->patient_id;
        $attachment->type = $request->type;
        $attachment->name = $path; // Storing path as name, or original name? Migration says 'name'. Usually path.
        $attachment->title = $request->title ?? $file->getClientOriginalName();
        $attachment->description = $request->description;
        $attachment->uploaded_by = Auth::id();
        $attachment->created_by = Auth::id();
        $attachment->updated_by = Auth::id();
        $attachment->save();

        return redirect()->route('admin.visits.show', $visit->id)
            ->with('success', __('admin.attachment_uploaded_successfully'));
    }

    public function destroy(Attachment $attachment)
    {
        // Optional: Check permission

        if (Storage::disk('public')->exists($attachment->name)) {
            Storage::disk('public')->delete($attachment->name);
        }

        $visitId = $attachment->visit_id;
        $attachment->delete();

        return redirect()->route('admin.visits.show', $visitId)
            ->with('success', __('admin.attachment_deleted_successfully'));
    }
}
