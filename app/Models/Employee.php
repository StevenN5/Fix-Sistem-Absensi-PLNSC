<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, Notifiable;
    
    public function getRouteKeyName()
    {
        return 'name';
    }
    protected $table = 'employees';
    protected $fillable = [
        'name',
        'email',
        'pin_code',
        'phone_number',
        'address',
        'birth_date',
        'institution',
        'division_id',
        'mentor_id',
        'internship_start_date',
        'internship_end_date',
        'position',
        'major',
        'profile_photo_path',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'bank_name',
        'bank_account_number',
    ];

  
    protected $hidden = [
        'pin_code', 'remember_token',
    ];


    public function check()
    {
        return $this->hasMany(Check::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
    public function latetime()
    {
        return $this->hasMany(Latetime::class);
    }
    public function leave()
    {
        return $this->hasMany(Leave::class);
    }
    public function overtime()
    {
        return $this->hasMany(Overtime::class);
    }

    public function finalReports()
    {
        return $this->hasMany(FinalReport::class, 'emp_id');
    }

    public function monthlyReports()
    {
        return $this->hasMany(MonthlyReport::class, 'emp_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    public function absenceRequests()
    {
        return $this->hasMany(AbsenceRequest::class, 'emp_id');
    }

    public function schedules()
    {
        return $this->belongsToMany('App\Models\Schedule', 'schedule_employees', 'emp_id', 'schedule_id');
    }


    

}
