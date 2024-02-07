<?php

namespace App\Services;
use App\Models\TimeLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TimeLogService
{
    // Add your service methods here

    public static function getWorkedHourPerDay()
    {
        $workedHours = TimeLog::selectRaw('DATE(start_time) as date, SUM(TIMESTAMPDIFF(HOUR, start_time, end_time)) as total_hours')
            ->groupBy('date')
            ->get()
            ->pluck('total_hours', 'date');
        return $workedHours;    
    }

    public static function getWorkedHourPerMonth()
    {
        $workedHours = TimeLog::select(
            DB::raw('DATE_FORMAT(start_time, "%Y-%m") as month'),
            DB::raw('SUM(TIMESTAMPDIFF(HOUR, start_time, end_time)) as total_hours')
        )
        ->groupBy('month')
        ->get()
        ->pluck('total_hours', 'month');
        return $workedHours;
    }

    public static function getWorkedHourMonthlyByProject()
    {
        $workedHours = TimeLog::select(
            'projects.name as project_name',
            DB::raw('DATE_FORMAT(time_logs.start_time, "%Y-%m") as month'),
            DB::raw('SUM(TIMESTAMPDIFF(HOUR, time_logs.start_time, time_logs.end_time)) as total_hours')
        )
        ->join('projects', 'time_logs.project_id', '=', 'projects.id')
        ->groupBy('projects.name', 'month')
        ->get()
        ->groupBy('project_name');

        return $workedHours;
    }

    public static function generateCSV()
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
            $projectName = $timeLog->project->name ?? 'No Project'; // Use 'No Project' if project name is not available
        
            fputcsv($handle, array(
                $projectName,
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