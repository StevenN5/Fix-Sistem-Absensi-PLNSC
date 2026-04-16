<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Latetime;
use App\Models\Overtime;
use App\Models\MonthlyReport;
use App\Models\FinalReport;
use App\Models\AbsenceRequest;
use App\Models\AttendanceDraftDocument;
use App\Http\Requests\EmployeeRec;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeController extends Controller
{
   
    public function index()
    {
        $employees = Employee::with('division')->get();
        $usersByEmail = User::all()->keyBy('email');

        foreach ($employees as $employee) {
            $user = $employee->email ? ($usersByEmail[$employee->email] ?? null) : null;
            if (!$user) {
                continue;
            }

            $needsUpdate = false;
            if (!$employee->phone_number && $user->phone_number) {
                $employee->phone_number = $user->phone_number;
                $needsUpdate = true;
            }
            if (!$employee->address && $user->address) {
                $employee->address = $user->address;
                $needsUpdate = true;
            }
            if (!$employee->birth_date && $user->birth_date) {
                $employee->birth_date = $user->birth_date;
                $needsUpdate = true;
            }
            if (!$employee->institution && $user->institution) {
                $employee->institution = $user->institution;
                $needsUpdate = true;
            }
            if (!$employee->internship_start_date && $user->internship_start_date) {
                $employee->internship_start_date = $user->internship_start_date;
                $needsUpdate = true;
            }
            if (!$employee->internship_end_date && $user->internship_end_date) {
                $employee->internship_end_date = $user->internship_end_date;
                $needsUpdate = true;
            }
            if (!$employee->profile_photo_path && $user->profile_photo_path) {
                $employee->profile_photo_path = $user->profile_photo_path;
                $needsUpdate = true;
            }
            if (!$employee->emergency_contact_name && $user->emergency_contact_name) {
                $employee->emergency_contact_name = $user->emergency_contact_name;
                $needsUpdate = true;
            }
            if (!$employee->emergency_contact_phone && $user->emergency_contact_phone) {
                $employee->emergency_contact_phone = $user->emergency_contact_phone;
                $needsUpdate = true;
            }
            if (!$employee->emergency_contact_relation && $user->emergency_contact_relation) {
                $employee->emergency_contact_relation = $user->emergency_contact_relation;
                $needsUpdate = true;
            }
            if (!$employee->bank_name && $user->bank_name) {
                $employee->bank_name = $user->bank_name;
                $needsUpdate = true;
            }
            if (!$employee->bank_account_number && $user->bank_account_number) {
                $employee->bank_account_number = $user->bank_account_number;
                $needsUpdate = true;
            }

            if ($needsUpdate) {
                $employee->save();
            }
        }

        return view('admin.employee')->with(['employees'=> $employees, 'schedules'=>Schedule::all()]);
    }

    public function show(Employee $employee)
    {
        $user = $employee->email ? User::where('email', $employee->email)->first() : null;

        $stats = [
            'attendance_count' => Attendance::where('emp_id', $employee->id)->count(),
            'leave_count' => Leave::where('emp_id', $employee->id)->count(),
            'latetime_count' => Latetime::where('emp_id', $employee->id)->count(),
            'overtime_count' => Overtime::where('emp_id', $employee->id)->count(),
            'absence_request_count' => AbsenceRequest::where('emp_id', $employee->id)->count(),
            'monthly_report_count' => MonthlyReport::where('emp_id', $employee->id)->count(),
            'final_report_count' => FinalReport::where('emp_id', $employee->id)->count(),
            'draft_document_count' => AttendanceDraftDocument::where('emp_id', $employee->id)->count(),
        ];

        return view('admin.employee-show', compact('employee', 'user', 'stats'));
    }

    public function store(EmployeeRec $request)
    {
        $request->validated();

        $employee = new Employee;
        $employee->name = $request->name;
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->birth_date = $request->birth_date;
        $employee->institution = $request->institution;
        $employee->internship_start_date = $request->internship_start_date;
        $employee->internship_end_date = $request->internship_end_date;
        $employee->position = $request->position;
        $employee->major = $request->major;
        $employee->emergency_contact_name = $request->emergency_contact_name;
        $employee->emergency_contact_phone = $request->emergency_contact_phone;
        $employee->emergency_contact_relation = $request->emergency_contact_relation;
        $employee->bank_name = $request->bank_name;
        $employee->bank_account_number = $request->bank_account_number;
        $employee->email = $request->email;
        $employee->pin_code = bcrypt($request->pin_code);
        if ($request->hasFile('profile_photo')) {
            $employee->profile_photo_path = $request->file('profile_photo')->store('profile-photos', 'public');
        }
        $employee->save();

        if($request->schedule){

            $schedule = Schedule::whereSlug($request->schedule)->first();

            $employee->schedules()->attach($schedule);
        }

        // $role = Role::whereSlug('emp')->first();

        // $employee->roles()->attach($role);

        $user = User::where('email', $employee->email)->first();
        if ($user) {
            $user->phone_number = $employee->phone_number;
            $user->address = $employee->address;
            $user->birth_date = $employee->birth_date;
            $user->institution = $employee->institution;
            $user->internship_start_date = $employee->internship_start_date;
            $user->internship_end_date = $employee->internship_end_date;
            $user->emergency_contact_name = $employee->emergency_contact_name;
            $user->emergency_contact_phone = $employee->emergency_contact_phone;
            $user->emergency_contact_relation = $employee->emergency_contact_relation;
            $user->bank_name = $employee->bank_name;
            $user->bank_account_number = $employee->bank_account_number;
            if ($employee->profile_photo_path) {
                $user->profile_photo_path = $employee->profile_photo_path;
            }
            $user->save();
        }

        flash()->success('Berhasil','Data karyawan berhasil dibuat.');

        return redirect()->route('employees.index')->with('success');
    }

 
    public function update(EmployeeRec $request, Employee $employee)
    {
        $request->validated();
        $oldEmail = $employee->email;

        $employee->name = $request->name;
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->birth_date = $request->birth_date;
        $employee->institution = $request->institution;
        $employee->internship_start_date = $request->internship_start_date;
        $employee->internship_end_date = $request->internship_end_date;
        $employee->position = $request->position;
        $employee->major = $request->major;
        $employee->emergency_contact_name = $request->emergency_contact_name;
        $employee->emergency_contact_phone = $request->emergency_contact_phone;
        $employee->emergency_contact_relation = $request->emergency_contact_relation;
        $employee->bank_name = $request->bank_name;
        $employee->bank_account_number = $request->bank_account_number;
        $employee->email = $request->email;
        $employee->pin_code = bcrypt($request->pin_code);
        if ($request->hasFile('profile_photo')) {
            if ($employee->profile_photo_path && Storage::disk('public')->exists($employee->profile_photo_path)) {
                Storage::disk('public')->delete($employee->profile_photo_path);
            }
            $employee->profile_photo_path = $request->file('profile_photo')->store('profile-photos', 'public');
        }
        $employee->save();

        if ($request->schedule) {

            $employee->schedules()->detach();

            $schedule = Schedule::whereSlug($request->schedule)->first();

            $employee->schedules()->attach($schedule);
        }

        $user = User::where('email', $oldEmail)->first();
        if (!$user) {
            $user = User::where('email', $employee->email)->first();
        }
        if ($user) {
            $user->name = $employee->name;
            $user->email = $employee->email;
            $user->phone_number = $employee->phone_number;
            $user->address = $employee->address;
            $user->birth_date = $employee->birth_date;
            $user->institution = $employee->institution;
            $user->internship_start_date = $employee->internship_start_date;
            $user->internship_end_date = $employee->internship_end_date;
            $user->emergency_contact_name = $employee->emergency_contact_name;
            $user->emergency_contact_phone = $employee->emergency_contact_phone;
            $user->emergency_contact_relation = $employee->emergency_contact_relation;
            $user->bank_name = $employee->bank_name;
            $user->bank_account_number = $employee->bank_account_number;
            if ($employee->profile_photo_path) {
                $user->profile_photo_path = $employee->profile_photo_path;
            }
            $user->save();
        }

        flash()->success('Berhasil','Data karyawan berhasil diperbarui.');

        return redirect()->route('employees.index')->with('success');
    }


    public function destroy(Employee $employee)
    {
        $employee->delete();
        flash()->success('Berhasil','Data karyawan berhasil dihapus.');
        return redirect()->route('employees.index')->with('success');
    }
}
