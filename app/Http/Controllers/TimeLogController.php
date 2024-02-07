<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

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
        // Fetch and process your data for the evaluation
        // Example: Get worked hours per day
        $workedHours = TimeLog::selectRaw('DATE(start_time) as date, SUM(TIMESTAMPDIFF(HOUR, start_time, end_time)) as total_hours')
            ->groupBy('date')
            ->get()
            ->pluck('total_hours', 'date');

        return view('timeLogs.evaluation', compact('workedHours'));
    }

    public function evaluationMonths()
    {
        // Fetch and process your data for the evaluation
        // Example: Get worked hours per month
        $workedHours = TimeLog::select(
                DB::raw('DATE_FORMAT(start_time, "%Y-%m") as month'),
                DB::raw('SUM(TIMESTAMPDIFF(HOUR, start_time, end_time)) as total_hours')
            )
            ->groupBy('month')
            ->get()
            ->pluck('total_hours', 'month');

        return view('timeLogs.evaluation', compact('workedHours'));
    }

    public function evaluationProject()
    {
        // Fetch and process your data for the evaluation
        // Example: Get worked hours per month by Project
        $workedHours = TimeLog::select(
            'projects.name as project_name',
            DB::raw('DATE_FORMAT(time_logs.start_time, "%Y-%m") as month'),
            DB::raw('SUM(TIMESTAMPDIFF(HOUR, time_logs.start_time, time_logs.end_time)) as total_hours')
        )
        ->join('projects', 'time_logs.project_id', '=', 'projects.id')
        ->groupBy('projects.name', 'month')
        ->get()
        ->groupBy('project_name');
       // dd($workedHours);

    return view('timeLogs.eva-proj', compact('workedHours'));
    
    }

    public function exportCsv()
    {
        
        $timeLogs = TimeLog::with('project')->get();

        $csvFileName = 'time_logs_export_' . now()->format('YmdHis') . '.csv';
       
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $handle = fopen('php://output', 'w');

        // Add CSV header
        fputcsv($handle, array('Project Name','Start Time', 'End Time', 'Description'));

        // Add data
        foreach ($timeLogs as $timeLog) {
            fputcsv($handle, array(
                $timeLog->project->name,
                $timeLog->start_time,
                $timeLog->end_time,
                $timeLog->description,
            ));
        }

        fclose($handle);
       
        return Response::stream(
            function () use ($handle) {
            },
            200,
            $headers
        ); 
    }
}
