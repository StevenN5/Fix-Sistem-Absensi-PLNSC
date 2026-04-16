<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AbsenceRequest;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\NationalHoliday;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserAttendanceController extends Controller
{
    public function index()
    {
        $data = $this->buildAttendanceContext();

        return view('user.attendance', $data);
    }

    public function history(Request $request)
    {
        $selectedMonth = $this->normalizeMonth($request->query('month'));
        $nationalHolidays = $this->resolveNationalHolidaysForMonth($selectedMonth);

        $data = $this->buildAttendanceContext();
        $rows = collect();
        $pdfRows = collect();
        if ($data['employee']) {
            $rows = $this->buildMonthlyAttendanceRows((int) $data['employee']->id, $selectedMonth, $nationalHolidays);
            $pdfRows = $rows->map(function ($row, $index) {
                $row['no'] = $index + 1;
                $row['day_name'] = Carbon::parse($row['date'])->translatedFormat('l');
                return $row;
            });
        }

        return view('user.attendance-history', [
            'employee' => $data['employee'],
            'rows' => $rows,
            'pdfRows' => $pdfRows,
            'selectedMonth' => $selectedMonth,
            'nationalHolidays' => $nationalHolidays,
        ]);
    }

    private function buildAttendanceContext(): array
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        $today = now()->toDateString();
        $hasAttendance = false;
        $lastAttendance = null;
        $hasLeave = false;
        $lastLeave = null;
        $logs = collect();

        if ($employee) {
            $hasAttendance = Attendance::where('emp_id', $employee->id)
                ->whereDate('attendance_date', $today)
                ->exists();
            $lastAttendance = Attendance::where('emp_id', $employee->id)
                ->orderBy('attendance_date', 'desc')
                ->orderBy('attendance_time', 'desc')
                ->first();

            $hasLeave = \App\Models\Leave::where('emp_id', $employee->id)
                ->whereDate('leave_date', $today)
                ->exists();
            $lastLeave = \App\Models\Leave::where('emp_id', $employee->id)
                ->orderBy('leave_date', 'desc')
                ->orderBy('leave_time', 'desc')
                ->first();

            $attendanceLogs = Attendance::where('emp_id', $employee->id)
                ->orderBy('attendance_date', 'desc')
                ->orderBy('attendance_time', 'desc')
                ->get()
                ->map(function ($attendance) {
                    $datetime = $attendance->attendance_date . ' ' . $attendance->attendance_time;
                    return [
                        'datetime' => $datetime,
                        'timestamp' => strtotime($datetime),
                        'type' => 'Masuk',
                        'note' => $attendance->note,
                    ];
                });

            $leaveLogs = \App\Models\Leave::where('emp_id', $employee->id)
                ->orderBy('leave_date', 'desc')
                ->orderBy('leave_time', 'desc')
                ->get()
                ->map(function ($leave) {
                    $datetime = $leave->leave_date . ' ' . $leave->leave_time;
                    return [
                        'datetime' => $datetime,
                        'timestamp' => strtotime($datetime),
                        'type' => 'Pulang',
                        'note' => $leave->note,
                    ];
                });

            $logs = $attendanceLogs
                ->merge($leaveLogs)
                ->sortByDesc('timestamp')
                ->values()
                ->take(50);
        }

        $groupedLogs = $logs->groupBy(function ($log) {
            $date = $log['datetime'] ?? null;
            if (!$date) {
                return 'unknown';
            }
            $carbon = Carbon::parse($date);
            return $carbon->isoWeekYear . '-' . str_pad($carbon->isoWeek, 2, '0', STR_PAD_LEFT);
        });

        return [
            'employee' => $employee,
            'hasAttendance' => $hasAttendance,
            'lastAttendance' => $lastAttendance,
            'hasLeave' => $hasLeave,
            'lastLeave' => $lastLeave,
            'logs' => $logs,
            'groupedLogs' => $groupedLogs,
        ];
    }

    private function buildMonthlyAttendanceRows(int $employeeId, string $month, array $nationalHolidays = []): \Illuminate\Support\Collection
    {
        $start = Carbon::createFromFormat('Y-m-d', $month . '-01')->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $attendanceByDate = Attendance::where('emp_id', $employeeId)
            ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy('attendance_date');

        $leaveByDate = Leave::where('emp_id', $employeeId)
            ->whereBetween('leave_date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy('leave_date');

        $absenceRequests = AbsenceRequest::where('emp_id', $employeeId)
            ->whereBetween('absence_date', [$start->toDateString(), $end->toDateString()])
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('reviewed_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $absenceByDate = collect();
        foreach ($absenceRequests as $absenceRequest) {
            $key = Carbon::parse($absenceRequest->absence_date)->toDateString();
            if (!$absenceByDate->has($key)) {
                $absenceByDate->put($key, $absenceRequest);
            }
        }

        $rows = collect();
        for ($cursor = $start->copy(); $cursor->lte($end); $cursor->addDay()) {
            $date = $cursor->toDateString();
            $attendance = $attendanceByDate->get($date);
            $leave = $leaveByDate->get($date);
            $isWeekend = $cursor->isWeekend();
            $holidayCode = $nationalHolidays[$date] ?? null;
            $isNationalHoliday = !empty($holidayCode);
            $isHoliday = $isWeekend || $isNationalHoliday;

            $timeIn = $attendance && $attendance->attendance_time
                ? Carbon::parse($attendance->attendance_time)->format('H:i')
                : '-';
            $timeOut = $leave && $leave->leave_time
                ? Carbon::parse($leave->leave_time)->format('H:i')
                : '-';
            $rawTimeIn = $attendance && $attendance->attendance_time
                ? Carbon::parse($attendance->attendance_time)->format('H:i:s')
                : null;
            $rawTimeOut = $leave && $leave->leave_time
                ? Carbon::parse($leave->leave_time)->format('H:i:s')
                : null;
            $isLateIn = $rawTimeIn ? strtotime($rawTimeIn) > strtotime('08:00:00') : false;
            $isEarlyOut = $rawTimeOut ? strtotime($rawTimeOut) < strtotime('16:30:00') : false;

            $status = 'Belum Presensi';
            $statusCode = '';
            $statusType = $attendance->status_type ?? null;
            $absenceRequest = $absenceByDate->get($date);
            $approvedAbsenceType = null;
            if ($absenceRequest && $absenceRequest->status === 'approved') {
                $approvedAbsenceType = strtolower((string) ($absenceRequest->absence_type ?: 'izin'));
            }

            if ($absenceRequest && $absenceRequest->status === 'approved' && $approvedAbsenceType !== 'lupa_absensi') {
                if ($approvedAbsenceType === 'sakit') {
                    $status = 'Sakit';
                    $statusCode = 'S';
                } else {
                    $status = 'Izin';
                    $statusCode = 'I';
                }
            } elseif ($absenceRequest && $absenceRequest->status === 'rejected') {
                $status = 'Tanpa Keterangan';
                $statusCode = 'TK';
            } elseif ($statusType === 'sakit') {
                $status = 'Sakit';
                $statusCode = 'S';
            } elseif ($statusType === 'izin') {
                $status = 'Izin';
                $statusCode = 'I';
            } elseif ($statusType === 'tanpa_keterangan') {
                $status = 'Tanpa Keterangan';
                $statusCode = 'TK';
            } elseif ($statusType === 'cuti') {
                $status = 'Cuti';
                $statusCode = 'C';
            } elseif ($statusType === 'perjalanan_dinas') {
                $status = 'Perjalanan Dinas';
                $statusCode = 'PD';
            } elseif ($attendance || $leave) {
                if ($isLateIn || $isEarlyOut) {
                    $status = 'Terlambat';
                    $statusCode = 'T';
                } else {
                    $status = 'Hadir';
                    $statusCode = 'H';
                }
            } elseif ($isHoliday) {
                $status = 'Libur';
                $statusCode = $isNationalHoliday ? $holidayCode : 'LS';
            }

            $notes = [];
            if ($attendance && $attendance->note) {
                $notes[] = 'Catatan masuk: ' . $attendance->note;
            }
            if ($leave && $leave->note) {
                $notes[] = 'Catatan pulang: ' . $leave->note;
            }

            $description = 'Masuk: ' . $timeIn . ' | Pulang: ' . $timeOut;
            if (!empty($notes)) {
                $description .= ' | ' . implode(' | ', $notes);
            }

            $rows->push([
                'date' => $date,
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'status' => $status,
                'status_code' => $statusCode,
                'description' => $description,
                'is_holiday' => $isHoliday,
                'is_national_holiday' => $isNationalHoliday,
            ]);
        }

        return $rows;
    }

    private function normalizeMonth(?string $month): string
    {
        $month = $month ?: now()->format('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }

        return $month;
    }

    private function resolveNationalHolidaysForMonth(string $month): array
    {
        $start = $month . '-01';
        $end = date('Y-m-t', strtotime($start));

        $mapped = NationalHoliday::query()
            ->whereBetween('holiday_date', [$start, $end])
            ->get()
            ->mapWithKeys(function ($row) {
                return [Carbon::parse($row->holiday_date)->toDateString() => strtoupper((string) $row->type)];
            })
            ->toArray();

        $raw = (string) env('NATIONAL_HOLIDAYS', '');
        if ($raw !== '') {
            $list = array_filter(array_map('trim', explode(',', $raw)));
            foreach ($list as $date) {
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && strpos($date, $month . '-') === 0) {
                    if (!isset($mapped[$date])) {
                        $mapped[$date] = 'LH';
                    }
                }
            }
        }

        return $mapped;
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            $employee = new Employee();
            $employee->name = $user->name;
            $employee->email = $user->email;
            $employee->position = 'Pengguna';
            $employee->save();
        }

        $today = now()->toDateString();
        $alreadyRecorded = Attendance::where('emp_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->exists();

        if ($alreadyRecorded) {
            flash()->error('Gagal', 'Kehadiran hari ini sudah tercatat.');
            return redirect()->route('user.attendance.index');
        }

        $attendance = new Attendance();
        $attendance->uid = 0;
        $attendance->emp_id = $employee->id;
        $attendance->state = 0;
        $attendance->attendance_time = now()->toTimeString();
        $attendance->attendance_date = $today;
        $timeIn = '08:00:00';
        $attendanceTime = date('H:i:s', strtotime($attendance->attendance_time));
        $attendance->status = $attendanceTime <= $timeIn ? 1 : 0;
        if ($attendance->status === 0 && $request->filled('note')) {
            $attendance->note = $request->note;
        }
        $attendance->type = 0;
        $attendance->save();

        flash()->success('Berhasil', 'Kehadiran berhasil dicatat.');
        return redirect()->route('user.attendance.index');
    }

    public function storeLeave(Request $request)
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            flash()->error('Gagal', 'Data karyawan tidak ditemukan.');
            return redirect()->route('user.attendance.index');
        }

        $today = now()->toDateString();
        $alreadyLeave = \App\Models\Leave::where('emp_id', $employee->id)
            ->whereDate('leave_date', $today)
            ->exists();

        if ($alreadyLeave) {
            flash()->error('Gagal', 'Jam pulang hari ini sudah tercatat.');
            return redirect()->route('user.attendance.index');
        }

        $leave = new \App\Models\Leave();
        $leave->uid = 0;
        $leave->emp_id = $employee->id;
        $leave->state = 0;
        $leave->leave_time = now()->toTimeString();
        $leave->leave_date = $today;
        $timeOut = '16:30:00';
        $leaveTime = date('H:i:s', strtotime($leave->leave_time));
        $leave->status = $leaveTime >= $timeOut ? 1 : 0;
        if ($leave->status === 0 && $request->filled('note')) {
            $leave->note = $request->note;
        }
        $leave->type = 1;
        $leave->save();

        flash()->success('Berhasil', 'Jam pulang berhasil dicatat.');
        return redirect()->route('user.attendance.index');
    }
}
