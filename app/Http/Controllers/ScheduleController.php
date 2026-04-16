<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\ScheduleEmp;

class ScheduleController extends Controller
{
    private const FIXED_TIME_IN = '08:00:00';
    private const FIXED_TIME_OUT = '16:30:00';

   
    public function index()
    {
        Schedule::query()->update([
            'time_in' => self::FIXED_TIME_IN,
            'time_out' => self::FIXED_TIME_OUT,
        ]);

        return view('admin.schedule')->with('schedules', Schedule::all());
        flash()->success('Berhasil','Jadwal berhasil dibuat.');

    }


    public function store(ScheduleEmp $request)
    {
        $request->merge([
            'time_in' => '08:00',
            'time_out' => '16:30',
        ]);

        $request->validated();

        $schedule = new schedule;
        $schedule->slug = $request->slug;
        $schedule->time_in = self::FIXED_TIME_IN;
        $schedule->time_out = self::FIXED_TIME_OUT;
        $schedule->save();




        flash()->success('Berhasil','Jadwal berhasil dibuat.');
        return redirect()->route('schedule.index');

    }

    public function update(ScheduleEmp $request, Schedule $schedule)
    {
        $request->merge([
            'time_in' => '08:00',
            'time_out' => '16:30',
        ]);

        $request->validated();

        $schedule->slug = $request->slug;
        $schedule->time_in = self::FIXED_TIME_IN;
        $schedule->time_out = self::FIXED_TIME_OUT;
        $schedule->save();
        flash()->success('Berhasil','Jadwal berhasil diperbarui.');
        return redirect()->route('schedule.index');


    }

  
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        flash()->success('Berhasil','Jadwal berhasil dihapus.');
        return redirect()->route('schedule.index');
    }
}
