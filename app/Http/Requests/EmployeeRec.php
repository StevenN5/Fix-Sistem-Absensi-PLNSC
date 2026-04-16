<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRec extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $employeeParam = $this->route('employee');
        $employeeId = null;
        if ($employeeParam instanceof Employee) {
            $employeeId = $employeeParam->id;
        } elseif (is_string($employeeParam)) {
            $employeeId = Employee::where('name', $employeeParam)->value('id');
        }

        return [
            'name' => 'required|string|min:3|max:64',
            'phone_number' => 'required|string|min:6|max:25',
            'address' => 'required|string|min:6|max:500',
            'birth_date' => 'required|date',
            'institution' => 'required|string|min:2|max:255',
            'internship_start_date' => 'nullable|date',
            'internship_end_date' => 'nullable|date|after_or_equal:internship_start_date',
            'position' => 'required|string|min:3|max:64',
            'major' => 'nullable|string|min:3|max:64',
            'email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($employeeId)],
            'schedule' => 'required|exists:schedules,slug',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:25',
            'emergency_contact_relation' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
        ];
    }
}
