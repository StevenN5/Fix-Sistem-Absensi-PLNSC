<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FingerDevicesControlller;
use App\Http\Controllers\MagangRegistrationController;

Route::get('/', [MagangRegistrationController::class, 'landing'])->name('welcome');
Route::prefix('magang')->name('magang.')->group(function () {
    Route::get('/daftar', [MagangRegistrationController::class, 'form'])->name('form');
    Route::post('/daftar', [MagangRegistrationController::class, 'submit'])->name('submit');
    Route::get('/status', [MagangRegistrationController::class, 'status'])->name('status');
    Route::get('/cek-status', [MagangRegistrationController::class, 'cekStatusForm'])->name('cek-status');
    Route::post('/cek-status', [MagangRegistrationController::class, 'cekStatusResult'])->name('cek-status.result');
});
Route::get('attended/{user_id}', '\App\Http\Controllers\AttendanceController@attended' )->name('attended');
Route::get('attended-before/{user_id}', '\App\Http\Controllers\AttendanceController@attendedBefore' )->name('attendedBefore');
Auth::routes(['register' => true, 'reset' => false]);

Route::group(['middleware' => ['auth', 'Role'], 'roles' => ['admin']], function () {
    Route::resource('/employees', '\App\Http\Controllers\EmployeeController');
    Route::resource('/employees', '\App\Http\Controllers\EmployeeController');
    Route::get('/attendance', '\App\Http\Controllers\AttendanceController@index')->name('attendance');
  
    Route::get('/latetime', '\App\Http\Controllers\AttendanceController@indexLatetime')->name('indexLatetime');
    Route::get('/leave', '\App\Http\Controllers\LeaveController@index')->name('leave');
    Route::get('/overtime', '\App\Http\Controllers\LeaveController@indexOvertime')->name('indexOvertime');

    Route::get('/admin', '\App\Http\Controllers\AdminController@index')->name('admin');

    Route::resource('/schedule', '\App\Http\Controllers\ScheduleController');

    Route::get('/check', '\App\Http\Controllers\CheckController@index')->name('check');
    Route::get('/sheet-report', '\App\Http\Controllers\CheckController@sheetReport')->name('sheet-report');
    Route::post('check-store','\App\Http\Controllers\CheckController@CheckStore')->name('check_store');
    Route::get('/final-report', '\App\Http\Controllers\FinalReportController@index')->name('final-report.index');
    Route::get('/final-report/export/{month}', '\App\Http\Controllers\FinalReportController@exportMonthZip')->name('final-report.export');
    Route::get('/monthly-report', '\App\Http\Controllers\MonthlyReportController@index')->name('monthly-report.index');
    Route::get('/monthly-report/export/{month}', '\App\Http\Controllers\MonthlyReportController@exportMonthZip')->name('monthly-report.export');
    Route::get('/absence-requests', '\App\Http\Controllers\AbsenceRequestController@index')->name('absence-requests.index');
    Route::patch('/absence-requests/{absenceRequest}', '\App\Http\Controllers\AbsenceRequestController@update')->name('absence-requests.update');
    Route::get('/absence-requests/{absenceRequest}/download', '\App\Http\Controllers\AbsenceRequestController@download')->name('absence-requests.download');
    Route::get('/absence-requests/{absenceRequest}/view', '\App\Http\Controllers\AbsenceRequestController@view')->name('absence-requests.view');
    Route::get('/divisions', '\App\Http\Controllers\DivisionController@index')->name('divisions.index');
    Route::post('/divisions', '\App\Http\Controllers\DivisionController@store')->name('divisions.store');
    Route::patch('/divisions/{division}', '\App\Http\Controllers\DivisionController@update')->name('divisions.update');
    Route::delete('/divisions/{division}', '\App\Http\Controllers\DivisionController@destroy')->name('divisions.destroy');
    Route::get('/mentors', '\App\Http\Controllers\MentorController@index')->name('mentors.index');
    Route::post('/mentors', '\App\Http\Controllers\MentorController@store')->name('mentors.store');
    Route::patch('/mentors/{mentor}', '\App\Http\Controllers\MentorController@update')->name('mentors.update');
    Route::delete('/mentors/{mentor}', '\App\Http\Controllers\MentorController@destroy')->name('mentors.destroy');
    Route::get('/national-holidays', '\App\Http\Controllers\NationalHolidayController@index')->name('national-holidays.index');
    Route::post('/national-holidays', '\App\Http\Controllers\NationalHolidayController@store')->name('national-holidays.store');
    Route::patch('/national-holidays/{nationalHoliday}', '\App\Http\Controllers\NationalHolidayController@update')->name('national-holidays.update');
    Route::delete('/national-holidays/{nationalHoliday}', '\App\Http\Controllers\NationalHolidayController@destroy')->name('national-holidays.destroy');
    Route::get('/attendance-drafts', '\App\Http\Controllers\AttendanceDraftController@adminIndex')->name('attendance-drafts.index');
    Route::get('/attendance-drafts/{document}/view', '\App\Http\Controllers\AttendanceDraftController@view')->name('attendance-drafts.view');
    Route::get('/attendance-drafts/{document}/download', '\App\Http\Controllers\AttendanceDraftController@download')->name('attendance-drafts.download');
    Route::get('/internship-draft-documents', '\App\Http\Controllers\InternshipDraftDocumentController@index')->name('internship-draft-documents.index');
    Route::post('/internship-draft-documents', '\App\Http\Controllers\InternshipDraftDocumentController@store')->name('internship-draft-documents.store');
    Route::get('/internship-draft-documents/{document}/view', '\App\Http\Controllers\InternshipDraftDocumentController@view')->name('internship-draft-documents.view');
    Route::get('/internship-draft-documents/{document}/download', '\App\Http\Controllers\InternshipDraftDocumentController@download')->name('internship-draft-documents.download');
    Route::delete('/internship-draft-documents/{document}', '\App\Http\Controllers\InternshipDraftDocumentController@destroy')->name('internship-draft-documents.destroy');
    Route::get('/admin/magang', [MagangRegistrationController::class, 'adminDashboard'])->name('admin.magang.dashboard');
    Route::get('/admin/magang/export', [MagangRegistrationController::class, 'exportExcel'])->name('admin.magang.export');
    Route::get('/admin/magang/{id}', [MagangRegistrationController::class, 'adminDetail'])->name('admin.magang.detail');
    Route::post('/admin/magang/{id}/status', [MagangRegistrationController::class, 'updateStatus'])->name('admin.magang.update');
    

});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', '\App\Http\Controllers\HomeController@userDashboard')->name('home');
    Route::get('/user/attendance', '\App\Http\Controllers\UserAttendanceController@index')->name('user.attendance.index');
    Route::post('/home/time-in', '\App\Http\Controllers\UserAttendanceController@store')->name('home.timein.store');
    Route::post('/home/time-out', '\App\Http\Controllers\UserAttendanceController@storeLeave')->name('home.timeout.store');
    Route::post('/final-report', '\App\Http\Controllers\FinalReportController@store')->name('final-report.store');
    Route::get('/final-report/{finalReport}/download', '\App\Http\Controllers\FinalReportController@download')->name('final-report.download');
    Route::get('/final-report/{finalReport}/view', '\App\Http\Controllers\FinalReportController@view')->name('final-report.view');
    Route::get('/user/final-report', '\App\Http\Controllers\FinalReportController@userIndex')->name('user.final-report');
    Route::post('/monthly-report', '\App\Http\Controllers\MonthlyReportController@store')->name('monthly-report.store');
    Route::get('/monthly-report/{monthlyReport}/download', '\App\Http\Controllers\MonthlyReportController@download')->name('monthly-report.download');
    Route::get('/monthly-report/{monthlyReport}/view', '\App\Http\Controllers\MonthlyReportController@view')->name('monthly-report.view');
    Route::get('/user/monthly-report', '\App\Http\Controllers\MonthlyReportController@userIndex')->name('user.monthly-report');
    Route::get('/user/profile', '\App\Http\Controllers\ProfileController@edit')->name('user.profile');
    Route::post('/user/profile', '\App\Http\Controllers\ProfileController@update')->name('user.profile.update');
    Route::get('/user/ketidakhadiran', '\App\Http\Controllers\AbsenceRequestController@userIndex')->name('user.absence.index');
    Route::post('/user/ketidakhadiran', '\App\Http\Controllers\AbsenceRequestController@store')->name('user.absence.store');
    Route::get('/user/ketidakhadiran/{absenceRequest}/download', '\App\Http\Controllers\AbsenceRequestController@download')->name('user.absence.download');
    Route::get('/user/ketidakhadiran/{absenceRequest}/view', '\App\Http\Controllers\AbsenceRequestController@view')->name('user.absence.view');
    Route::get('/user/attendance-history', '\App\Http\Controllers\UserAttendanceController@history')->name('user.attendance.history');
    Route::get('/user/attendance-history/draft', '\App\Http\Controllers\AttendanceDraftController@userIndex')->name('user.attendance.draft');
    Route::post('/user/attendance-history/draft', '\App\Http\Controllers\AttendanceDraftController@store')->name('user.attendance.draft.store');
    Route::get('/user/attendance-history/draft/{document}/view', '\App\Http\Controllers\AttendanceDraftController@view')->name('user.attendance.draft.view');
    Route::get('/user/attendance-history/draft/{document}/download', '\App\Http\Controllers\AttendanceDraftController@download')->name('user.attendance.draft.download');
    Route::get('/user/template-dokumen/{document}/view', '\App\Http\Controllers\InternshipDraftDocumentController@view')->name('user.template.view');
    Route::get('/user/template-dokumen/{document}/download', '\App\Http\Controllers\InternshipDraftDocumentController@download')->name('user.template.download');
    Route::get('/user/internship-draft-documents', '\App\Http\Controllers\InternshipDraftDocumentController@userIndex')->name('user.internship-draft-documents');
    Route::post('/user/attendance', '\App\Http\Controllers\UserAttendanceController@store')->name('user.attendance.store');
});

Route::get('lang/{locale}', function ($locale) {
    $allowed = ['id'];
    if (!in_array($locale, $allowed, true)) {
        abort(404);
    }
    session(['locale' => $locale]);
    return back();
})->name('lang.switch');

// Route::get('/attendance/assign', function () {
//     return view('attendance_leave_login');
// })->name('attendance.login');

// Route::post('/attendance/assign', '\App\Http\Controllers\AttendanceController@assign')->name('attendance.assign');


// Route::get('/leave/assign', function () {
//     return view('attendance_leave_login');
// })->name('leave.login');

// Route::post('/leave/assign', '\App\Http\Controllers\LeaveController@assign')->name('leave.assign');


// Route::get('{any}', 'App\http\controllers\VeltrixController@index');
