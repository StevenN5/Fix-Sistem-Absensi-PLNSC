<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class UserAttendanceLogSheet implements FromArray, WithEvents, WithTitle
{
    private Carbon $month;
    private Employee $employee;

    public function __construct(?string $month, Employee $employee)
    {
        if ($month) {
            try {
                $this->month = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            } catch (\Exception $e) {
                $this->month = Carbon::today()->startOfMonth();
            }
        } else {
            $this->month = Carbon::today()->startOfMonth();
        }

        $this->employee = $employee;
    }

    public function title(): string
    {
        return 'Log Kehadiran';
    }

    public function array(): array
    {
        $start = $this->month->copy()->startOfMonth()->toDateString();
        $end = $this->month->copy()->endOfMonth()->toDateString();

        $attendanceLogs = Attendance::where('emp_id', $this->employee->id)
            ->whereBetween('attendance_date', [$start, $end])
            ->orderBy('attendance_date', 'asc')
            ->orderBy('attendance_time', 'asc')
            ->get()
            ->map(function ($attendance) {
                $date = $attendance->attendance_date;
                $time = $attendance->attendance_time;
                $schedule = '08:00:00';
                $actual = strtotime($date . ' ' . $time);
                $target = strtotime($date . ' ' . $schedule);

                return [
                    'datetime' => $date . ' ' . $time,
                    'type' => 'Masuk',
                    'status' => $actual > $target ? 'Terlambat' : 'Tepat Waktu',
                    'note' => $attendance->note ?: '-',
                ];
            });

        $leaveLogs = Leave::where('emp_id', $this->employee->id)
            ->whereBetween('leave_date', [$start, $end])
            ->orderBy('leave_date', 'asc')
            ->orderBy('leave_time', 'asc')
            ->get()
            ->map(function ($leave) {
                $date = $leave->leave_date;
                $time = $leave->leave_time;
                $schedule = '16:30:00';
                $actual = strtotime($date . ' ' . $time);
                $target = strtotime($date . ' ' . $schedule);

                return [
                    'datetime' => $date . ' ' . $time,
                    'type' => 'Pulang',
                    'status' => $actual < $target ? 'Pulang Cepat' : 'Tepat Waktu',
                    'note' => $leave->note ?: '-',
                ];
            });

        $logs = $attendanceLogs->merge($leaveLogs)
            ->sortBy('datetime')
            ->values();

        $rows = [];
        $rows[] = ['Nama', $this->employee->name, '', ''];
        $rows[] = ['Bulan', $this->month->format('Y-m'), '', ''];
        $rows[] = ['Tanggal & Jam', 'Tipe', 'Status', 'Keterangan'];

        foreach ($logs as $log) {
            $rows[] = [
                $log['datetime'],
                $log['type'],
                $log['status'],
                $log['note'],
            ];
        }

        if ($logs->isEmpty()) {
            $rows[] = ['-', '-', '-', 'Tidak ada data pada bulan ini'];
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $sheet->getColumnDimension('A')->setWidth(24);
                $sheet->getColumnDimension('B')->setWidth(18);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(36);

                $sheet->getStyle('A1:D' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A3:D3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A3:D3')->getFont()->setBold(true);
            },
        ];
    }
}
