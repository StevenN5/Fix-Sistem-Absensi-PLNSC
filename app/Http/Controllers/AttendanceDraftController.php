<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDraftDocument;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceDraftController extends Controller
{
    public function adminIndex(Request $request)
    {
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = now()->format('Y-m');
        }

        $selectedEmployeeId = $request->query('emp_id');
        $employees = Employee::orderBy('name')->get();

        $query = AttendanceDraftDocument::with(['employee', 'uploadedBy'])
            ->where('report_month', $selectedMonth)
            ->orderBy('created_at', 'desc');

        if ($selectedEmployeeId) {
            $query->where('emp_id', $selectedEmployeeId);
        }

        $documents = $query->get();

        return view('admin.attendance-drafts')->with([
            'documents' => $documents,
            'selectedMonth' => $selectedMonth,
            'employees' => $employees,
            'selectedEmployeeId' => $selectedEmployeeId,
        ]);
    }

    public function userIndex()
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        $documents = collect();
        if ($employee) {
            $documents = AttendanceDraftDocument::where('emp_id', $employee->id)
                ->orderBy('report_month', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('user.attendance-draft')->with([
            'employee' => $employee,
            'documents' => $documents,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'report_month' => 'required|date_format:Y-m',
            'draft_document' => 'required|file|max:5120|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ]);

        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();
        if (!$employee) {
            $employee = new Employee();
            $employee->name = $user->name;
            $employee->email = $user->email;
            $employee->position = 'Pengguna';
            $employee->save();
        }

        $file = $request->file('draft_document');
        $timestamp = now()->format('YmdHis');
        $extension = $file->getClientOriginalExtension();
        $safeName = 'attendance-draft-' . $employee->id . '-' . $timestamp . '.' . $extension;
        $month = $request->report_month;
        $path = $file->storeAs('attendance-drafts/' . $month, $safeName, 'public');

        AttendanceDraftDocument::create([
            'emp_id' => $employee->id,
            'uploaded_by' => $user->id,
            'report_month' => $month,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ]);

        flash()->success('Berhasil', 'Draft dokumen presensi berhasil diunggah.');
        return redirect()->route('user.attendance.draft');
    }

    public function view(AttendanceDraftDocument $document)
    {
        $this->authorizeAccess($document);
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($document->file_path);
        $mimeType = $document->mime_type ?: 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"',
        ]);
    }

    public function download(AttendanceDraftDocument $document)
    {
        $this->authorizeAccess($document);
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    private function authorizeAccess(AttendanceDraftDocument $document): void
    {
        $user = auth()->user();
        if ($user && $user->hasRole('admin')) {
            return;
        }

        $employee = Employee::where('email', optional($user)->email)->first();
        if (!$employee || (int) $employee->id !== (int) $document->emp_id) {
            abort(403);
        }
    }
}
