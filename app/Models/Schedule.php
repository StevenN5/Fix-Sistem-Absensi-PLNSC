<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public const FIXED_TIME_IN = '08:00:00';
    public const FIXED_TIME_OUT = '16:30:00';

    protected static function booted()
    {
        static::saving(function (self $schedule) {
            $schedule->time_in = self::FIXED_TIME_IN;
            $schedule->time_out = self::FIXED_TIME_OUT;
        });
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function getTimeInAttribute($value)
    {
        return self::FIXED_TIME_IN;
    }

    public function getTimeOutAttribute($value)
    {
        return self::FIXED_TIME_OUT;
    }

    public function employees()
    {
        return $this->belongsToMany('App\Models\Employee', 'schedule_employees', 'schedule_id', 'emp_id');
    }
}
