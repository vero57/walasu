<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WalasStudentController;
use App\Http\Controllers\Api\WalasAttendanceController;
use App\Http\Controllers\Api\WalasCaseNoteController;
use App\Http\Controllers\Api\WalasHomeVisitController;
use App\Http\Controllers\Api\WalasAchievementController;
use App\Http\Controllers\Api\WalasTeacherController;

/*
|--------------------------------------------------------------------------
| API Routes for Walas -> Kesiswaan Integration
|--------------------------------------------------------------------------
*/

Route::prefix('v1/walas')->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | SECTION A: Student & Class Data
    |--------------------------------------------------------------------------
    */
    Route::prefix('students')->controller(WalasStudentController::class)->group(function () {
        Route::get('/', 'index')                    ->name('walas.students.index');
        Route::get('/{id}', 'show')                 ->name('walas.students.show');
        Route::get('/by-class/{classId}', 'byClass')->name('walas.students.byClass');
        Route::get('/search', 'search')             ->name('walas.students.search');
    });

    /*
    |--------------------------------------------------------------------------
    | SECTION B: Attendance Data
    |--------------------------------------------------------------------------
    */
    Route::prefix('attendance')->controller(WalasAttendanceController::class)->group(function () {
        Route::get('/', 'index')                           ->name('walas.attendance.index');
        Route::get('{studentId}/month/{month}', 'monthAttendance')
                                                            ->name('walas.attendance.month');
        Route::get('{studentId}/stats', 'stats')           ->name('walas.attendance.stats');
        Route::get('flagged', 'flagged')                   ->name('walas.attendance.flagged');
        Route::get('class/{classId}/date/{date}', 'classAttendanceByDate')
                                                            ->name('walas.attendance.classDate');
    });

    /*
    |--------------------------------------------------------------------------
    | SECTION C: Case Notes
    |--------------------------------------------------------------------------
    */
    Route::prefix('case-notes')->controller(WalasCaseNoteController::class)->group(function () {
        Route::get('/', 'index')                           ->name('walas.caseNotes.index');
        Route::get('byStudent/{studentId}', 'byStudent')   ->name('walas.caseNotes.byStudent');
        Route::get('byWalas/{walasId}', 'byWalas')         ->name('walas.caseNotes.byWalas');
        Route::get('{studentId}/recent', 'recent')         ->name('walas.caseNotes.recent');
        Route::post('sync', 'sync')                        ->name('walas.caseNotes.sync');
    });

    /*
    |--------------------------------------------------------------------------
    | SECTION D: Home Visits
    |--------------------------------------------------------------------------
    */
    Route::prefix('home-visits')->controller(WalasHomeVisitController::class)->group(function () {
        Route::get('/', 'index')                           ->name('walas.homeVisits.index');
        Route::get('{id}', 'show')                         ->name('walas.homeVisits.show');
        Route::get('student/{studentId}', 'byStudent')     ->name('walas.homeVisits.byStudent');
        Route::get('walas/{walasId}', 'byWalas')           ->name('walas.homeVisits.byWalas');
        Route::get('class/{classId}/date/{date}', 'byClassDate')
                                                            ->name('walas.homeVisits.byClassDate');
        Route::get('stats', 'stats')                       ->name('walas.homeVisits.stats');
    });

    /*
    |--------------------------------------------------------------------------
    | SECTION E: Achievements & Prestasi Siswa
    |--------------------------------------------------------------------------
    */
    Route::prefix('achievements')->controller(WalasAchievementController::class)->group(function () {
        Route::get('/', 'index')                           ->name('walas.achievements.index');
        Route::get('{id}', 'show')                         ->name('walas.achievements.show');
        Route::get('student/{studentId}', 'byStudent')     ->name('walas.achievements.byStudent');
        Route::get('type/{type}', 'byType')                ->name('walas.achievements.byType');
        Route::get('walas/{walasId}', 'byWalas')           ->name('walas.achievements.byWalas');
        Route::get('stats', 'stats')                       ->name('walas.achievements.stats');
        Route::get('type-stats', 'byTypeStats')            ->name('walas.achievements.typeStats');
    });

    /*
    |--------------------------------------------------------------------------
    | SECTION F: Walas (Teacher) Data
    |--------------------------------------------------------------------------
    */
    Route::prefix('walas')->controller(WalasTeacherController::class)->group(function () {
        Route::get('/', 'index')                           ->name('walas.walas.index');
        Route::get('/{id}', 'show')                        ->name('walas.walas.show');
        Route::get('/search', 'search')                    ->name('walas.walas.search');
    });
});

/*
|--------------------------------------------------------------------------
| Default Laravel API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
