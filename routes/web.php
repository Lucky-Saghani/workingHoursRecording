<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('timeLogs', TimeLogController::class);
Route::get('/evaluation/days', [TimeLogController::class, 'evaluationDays'])->name('timeLogs.evaluation.days');
Route::get('/evaluation/months', [TimeLogController::class, 'evaluationMonths'])->name('timeLogs.evaluation.months');
Route::get('/evaluation/project', [TimeLogController::class, 'evaluationProject'])->name('timeLogs.evaluation.project');
Route::get('/exportCsv', [TimeLogController::class, 'exportCsv'])->name('timeLogs.exportCsv');
Route::resource('projects', ProjectController::class);

