<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\TimeLogService;
//use Symfony\Component\HttpFoundation\StreamedResponse;


class TimeLogController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        $timeLogs = TimeLog::all();
        return view('timeLogs.index', compact('timeLogs','projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('timeLogs.create',compact('projects'));
    }

    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'start_time' => 'required|before:end_time',
            'end_time' => 'required',
            'description' => 'required',
        ];
        // Custom error messages
        $messages = [
            'start_time.before' => 'Start time must be before end time.',
        ];
        // Validate the request
        $validator = Validator::make($request->all(), $rules, $messages);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        TimeLog::create($request->all());
        return redirect()->route('timeLogs.index');
    }

    public function edit($id)
    {
        $timeLog = TimeLog::findOrFail($id);
        $projects = Project::all();
        return view('timeLogs.edit', compact('timeLog','projects'));
    }

    public function update(Request $request, $id)
    {
        $timeLog = TimeLog::findOrFail($id);
        $timeLog->update($request->all());
        return redirect()->route('timeLogs.index');
    }

    public function destroy($id)
    {
        $timeLog = TimeLog::findOrFail($id);
        $timeLog->delete();
        return redirect()->route('timeLogs.index');
    }

    public function evaluationDays()
    {
        // Example: Get worked hours per day
        $workedHours = TimeLogService::getWorkedHourPerDay();
        return view('timeLogs.evaluation', compact('workedHours'));
    }

    public function evaluationMonths()
    {
        // Example: Get worked hours per month
        $workedHours = TimeLogService::getWorkedHourPerMonth();
        return view('timeLogs.evaluation', compact('workedHours'));
    }

    public function evaluationProject()
    {
        // Example: Get worked hours per month by Project
        $workedHours = TimeLogService::getWorkedHourMonthlyByProject();
        return view('timeLogs.eva-proj', compact('workedHours'));
    }

    public function exportCsv()
    {
        return TimeLogService::generateCSV();
    }
}
