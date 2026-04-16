<?php

namespace App\Http\Controllers;

use App\Models\AbsenceRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AbsenceRequestController extends Controller
{
    public function userIndex()
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();
        $selectedType = $this->resolveAbsenceTypeFilter(request('type'));

        $requests = collect();
        if ($employee) {
            $query = AbsenceRequest::where('emp_id', $employee->id);
            if ($selectedType === 'izin_sakit') {
                $query->whereIn('absence_type', ['izin', 'sakit']);
            } elseif ($selectedType === 'lupa_absensi') {
                $query->where('absence_type', 'lupa_absensi');
            }

            $requests = $query
                ->orderBy('absence_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('user.absence-request')->with([
            'employee' => $employee,
            'requests' => $requests,
            'selectedType' => $selectedType,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'absence_date' => 'required|date',
            'absence_type' => 'required|in:izin,sakit,lupa_absensi',
            'reason' => 'required|string|max:2000',
            'document' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
            'correction_time_in' => 'nullable|date_format:H:i',
            'correction_time_out' => 'nullable|date_format:H:i',
        ]);

        if ($request->absence_type === 'lupa_absensi' && !$request->filled('correction_time_in') && !$request->filled('correction_time_out')) {
            return back()
                ->withErrors(['correction_time_in' => 'Isi minimal jam masuk atau jam pulang untuk pengajuan lupa absensi.'])
                ->withInput();
        }

        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            $employee = new Employee();
            $employee->name = $user->name;
            $employee->email = $user->email;
            $employee->position = 'Pengguna';
            $employee->save();
        }

        $absenceRequest = new AbsenceRequest();
        $absenceRequest->emp_id = $employee->id;
        $absenceRequest->absence_date = $request->absence_date;
        $absenceRequest->absence_type = $request->absence_type;
        $absenceRequest->correction_time_in = $this->normalizeTimeInput($request->input('correction_time_in'));
        $absenceRequest->correction_time_out = $this->normalizeTimeInput($request->input('correction_time_out'));
        $absenceRequest->reason = trim((string) $request->reason);
        $absenceRequest->status = 'pending';

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $safeName = 'absence-' . $employee->id . '-' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('absence-requests/' . date('Y-m'), $safeName, 'public');
            $absenceRequest->document_name = $file->getClientOriginalName();
            $absenceRequest->document_path = $path;
            $absenceRequest->document_size = $file->getSize();
            $absenceRequest->document_mime = $file->getClientMimeType();
        }

        $absenceRequest->save();

        flash()->success('Berhasil', 'Pengajuan ketidakhadiran berhasil dikirim.');
        $menuType = $this->resolveAbsenceTypeFilter($request->input('menu_type'));
        return redirect()->route('user.absence.index', ['type' => $menuType]);
    }

    public function index(Request $request)
    {
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = now()->format('Y-m');
        }
        $selectedType = $this->resolveAbsenceTypeFilter($request->query('type'));

        $start = $selectedMonth . '-01';
        $end = date('Y-m-t', strtotime($start));

        $query = AbsenceRequest::with(['employee', 'reviewer'])
            ->whereBetween('absence_date', [$start, $end]);

        if ($selectedType === 'izin_sakit') {
            $query->whereIn('absence_type', ['izin', 'sakit']);
        } elseif ($selectedType === 'lupa_absensi') {
            $query->where('absence_type', 'lupa_absensi');
        }

        $requests = $query
            ->orderBy('absence_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.absence-requests')->with([
            'requests' => $requests,
            'selectedMonth' => $selectedMonth,
            'selectedType' => $selectedType,
        ]);
    }

    public function update(Request $request, AbsenceRequest $absenceRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_note' => 'nullable|string|max:2000',
            'correction_time_in' => 'nullable|date_format:H:i',
            'correction_time_out' => 'nullable|date_format:H:i',
        ]);

        $isLupaAbsensi = strtolower((string) $absenceRequest->absence_type) === 'lupa_absensi';
        if ($isLupaAbsensi && $request->status === 'approved') {
            $timeInInput = $request->input('correction_time_in');
            $timeOutInput = $request->input('correction_time_out');
            if (empty($timeInInput) && empty($timeOutInput) && empty($absenceRequest->correction_time_in) && empty($absenceRequest->correction_time_out)) {
                return back()->withErrors([
                    'correction_time_in_' . $absenceRequest->id => 'Jam koreksi wajib diisi untuk menyetujui lupa absensi.',
                ]);
            }
        }

        $newTimeIn = $this->normalizeTimeInput($request->input('correction_time_in'));
        $newTimeOut = $this->normalizeTimeInput($request->input('correction_time_out'));
        if ($newTimeIn !== null) {
            $absenceRequest->correction_time_in = $newTimeIn;
        }
        if ($newTimeOut !== null) {
            $absenceRequest->correction_time_out = $newTimeOut;
        }

        $absenceRequest->status = $request->status;
        $absenceRequest->admin_note = $request->admin_note;
        $absenceRequest->reviewed_by = optional(auth()->user())->id;
        $absenceRequest->reviewed_at = now();
        $absenceRequest->save();

        if ($isLupaAbsensi && $request->status === 'approved') {
            $this->applyManualAttendanceCorrection($absenceRequest);
        }

        flash()->success('Berhasil', 'Status pengajuan ketidakhadiran berhasil diperbarui.');
        return back();
    }

    public function download(AbsenceRequest $absenceRequest)
    {
        $this->authorizeFileAccess($absenceRequest);

        if (!$absenceRequest->document_path || !Storage::disk('public')->exists($absenceRequest->document_path)) {
            abort(404);
        }

        $downloadName = $absenceRequest->document_name ?: basename($absenceRequest->document_path);
        return Storage::disk('public')->download($absenceRequest->document_path, $downloadName);
    }

    public function view(AbsenceRequest $absenceRequest)
    {
        $this->authorizeFileAccess($absenceRequest);

        if (!$absenceRequest->document_path || !Storage::disk('public')->exists($absenceRequest->document_path)) {
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($absenceRequest->document_path);
        $mimeType = $absenceRequest->document_mime ?: 'application/octet-stream';
        $filename = $absenceRequest->document_name ?: basename($absenceRequest->document_path);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    private function authorizeFileAccess(AbsenceRequest $absenceRequest): void
    {
        $user = auth()->user();
        $isAdmin = $user && $user->hasRole('admin');

        if ($isAdmin) {
            return;
        }

        $employee = Employee::where('email', optional($user)->email)->first();
        if (!$employee || (int) $employee->id !== (int) $absenceRequest->emp_id) {
            abort(403);
        }
    }

    private function normalizeTimeInput(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::createFromFormat('H:i', $value)->format('H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function applyManualAttendanceCorrection(AbsenceRequest $absenceRequest): void
    {
        $date = Carbon::parse($absenceRequest->absence_date)->toDateString();
        $timeIn = $absenceRequest->correction_time_in ?: null;
        $timeOut = $absenceRequest->correction_time_out ?: null;

        DB::transaction(function () use ($absenceRequest, $date, $timeIn, $timeOut): void {
            if ($timeIn) {
                $attendance = Attendance::firstOrNew([
                    'emp_id' => $absenceRequest->emp_id,
                    'attendance_date' => $date,
                ]);
                $attendance->uid = $attendance->uid ?? 0;
                $attendance->state = $attendance->state ?? 0;
                $attendance->type = 0;
                $attendance->attendance_time = $timeIn;
                $attendance->status = strtotime($timeIn) <= strtotime('08:00:00') ? 1 : 0;
                $attendance->status_type = null;
                $attendance->note = 'Koreksi admin dari pengajuan lupa absensi';
                $attendance->save();
            }

            if ($timeOut) {
                $leave = Leave::firstOrNew([
                    'emp_id' => $absenceRequest->emp_id,
                    'leave_date' => $date,
                ]);
                $leave->uid = $leave->uid ?? 0;
                $leave->state = $leave->state ?? 0;
                $leave->type = 1;
                $leave->leave_time = $timeOut;
                $leave->status = strtotime($timeOut) >= strtotime('16:30:00') ? 1 : 0;
                $leave->note = 'Koreksi admin dari pengajuan lupa absensi';
                $leave->save();
            }
        });
    }

    private function resolveAbsenceTypeFilter(?string $type): string
    {
        $type = strtolower((string) $type);
        if (!in_array($type, ['izin_sakit', 'lupa_absensi', 'all'], true)) {
            return 'izin_sakit';
        }
        return $type;
    }
}
