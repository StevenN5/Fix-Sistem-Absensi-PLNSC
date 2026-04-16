<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\AbsenceRequest;
use App\Models\NationalHoliday;
use Carbon\Carbon;

class CheckController extends Controller
{
    public function index()
    {
        return view('admin.check')->with(['employees' => Employee::all()]);
    }

    public function CheckStore(Request $request)
    {
        if (isset($request->status_type)) {
            foreach ($request->status_type as $keys => $values) {
                foreach ($values as $key => $value) {
                    if (!$value || $value === 'hadir') {
                        continue;
                    }
                    $attendance = Attendance::whereAttendance_date($keys)
                        ->whereEmp_id($key)
                        ->whereType(0)
                        ->first();

                    if (!$attendance) {
                        $attendance = new Attendance();
                        $attendance->emp_id = $key;
                        $attendance->attendance_date = $keys;
                        $attendance->attendance_time = '00:00:00';
                        $attendance->type = 0;
                    }
                    $attendance->status_type = $value;
                    $attendance->save();
                }
            }
        }
        if (isset($request->attd)) {
            foreach ($request->attd as $keys => $values) {
                foreach ($values as $key => $value) {
                    if ($employee = Employee::whereId(request('emp_id'))->first()) {
                        if (
                            !Attendance::whereAttendance_date($keys)
                                ->whereEmp_id($key)
                                ->whereType(0)
                                ->first()
                        ) {
                            $data = new Attendance();
                            
                            $data->emp_id = $key;
                            $emp_req = Employee::whereId($data->emp_id)->first();
                            $data->attendance_time = date('H:i:s', strtotime($emp_req->schedules->first()->time_in));
                            $data->attendance_date = $keys;
                            $data->status_type = null;
                            
                            // $emps = date('H:i:s', strtotime($employee->schedules->first()->time_in));
                            // if (!($emps >= $data->attendance_time)) {
                            //     $data->status = 0;
                           
                            // }
                            $data->save();
                        }
                    }
                }
            }
        }
        if (isset($request->leave)) {
            foreach ($request->leave as $keys => $values) {
                foreach ($values as $key => $value) {
                    if ($employee = Employee::whereId(request('emp_id'))->first()) {
                        if (
                            !Leave::whereLeave_date($keys)
                                ->whereEmp_id($key)
                                ->whereType(1)
                                ->first()
                        ) {
                            $data = new Leave();
                            $data->emp_id = $key;
                            $emp_req = Employee::whereId($data->emp_id)->first();
                            $data->leave_time = $emp_req->schedules->first()->time_out;
                            $data->leave_date = $keys;
                            // if ($employee->schedules->first()->time_out <= $data->leave_time) {
                            //     $data->status = 1;
                                
                            // }
                            // 
                            $data->save();
                        }
                    }
                }
            }
        }
        flash()->success('Berhasil', 'Kehadiran berhasil disimpan.');
        return back();
    }
    public function sheetReport()
    {
        $employees = Employee::with(['division', 'mentor'])->orderBy('name')->get();
        $selectedMonth = $this->normalizeMonth(request('month'));
        $nationalHolidays = $this->resolveNationalHolidaysForMonth($selectedMonth);

        $selectedEmployeeId = (string) request('user_id', '');
        $selectedEmployee = null;
        if ($selectedEmployeeId !== '') {
            $selectedEmployee = $employees->firstWhere('id', (int) $selectedEmployeeId);
        }

        $attendanceByDate = collect();
        $leaveByDate = collect();
        $pdfRows = collect();
        $monthlyRows = collect();
        $workingDays = 0;
        $attendanceDays = 0;
        if ($selectedEmployee) {
            [$year, $month] = explode('-', $selectedMonth);
            $startDate = sprintf('%04d-%02d-01', (int) $year, (int) $month);
            $endDate = date('Y-m-t', strtotime($startDate));

            $attendanceByDate = Attendance::where('emp_id', $selectedEmployee->id)
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->get()
                ->keyBy('attendance_date');

            $leaveByDate = Leave::where('emp_id', $selectedEmployee->id)
                ->whereBetween('leave_date', [$startDate, $endDate])
                ->get()
                ->keyBy('leave_date');

            $monthlyRows = $this->buildMonthlyAttendanceRows((int) $selectedEmployee->id, $selectedMonth, $nationalHolidays);
            $pdfRows = $monthlyRows
                ->map(function ($row, $index) {
                    $row['no'] = $index + 1;
                    $row['day_name'] = Carbon::parse($row['date'])->translatedFormat('l');
                    return $row;
                });

            $workingDays = $monthlyRows->filter(function ($row) {
                return !$row['is_holiday'];
            })->count();

            $attendanceDays = $monthlyRows->filter(function ($row) {
                return $row['status'] === 'Hadir';
            })->count();
        }

        return view('admin.sheet-report')->with([
            'employees' => $employees,
            'selectedMonth' => $selectedMonth,
            'selectedEmployeeId' => $selectedEmployeeId,
            'selectedEmployee' => $selectedEmployee,
            'attendanceByDate' => $attendanceByDate,
            'leaveByDate' => $leaveByDate,
            'monthlyRows' => $monthlyRows,
            'workingDays' => $workingDays,
            'attendanceDays' => $attendanceDays,
            'pdfRows' => $pdfRows,
            'nationalHolidays' => $nationalHolidays,
        ]);
    }

    private function normalizeMonth(?string $month): string
    {
        $month = $month ?: now()->format('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }

        return $month;
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

}
