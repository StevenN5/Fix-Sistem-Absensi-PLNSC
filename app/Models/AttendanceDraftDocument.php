<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceDraftDocument extends Model
{
    protected $fillable = [
        'emp_id',
        'uploaded_by',
        'report_month',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
