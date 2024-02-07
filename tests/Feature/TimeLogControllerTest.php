<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\Project;
use App\Models\TimeLog;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimeLogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Create sample projects and time logs in the database
        $projects = Project::factory()->count(3)->create();
        $timeLogs = TimeLog::factory()->count(3)->create();

        // Send a GET request to the index route
        $response = $this->get(route('timeLogs.index'));

        // Assert that the response has a status of 200 (OK)
        $response->assertStatus(200);

        // Assert that the view 'timeLogs.index' is rendered
        $response->assertViewIs('timeLogs.index');

        // Assert that the time logs and projects are passed to the view
        $response->assertViewHas('timeLogs', $timeLogs);
        $response->assertViewHas('projects', $projects);

        // Assert that the number of time logs and projects in the view matches the number created
        $this->assertCount(3, $response['timeLogs']);
        $this->assertCount(3, $response['projects']);
    }

    //Create
    public function testStoreMethodValidatesInput()
    {
        $response = $this->post(route('timeLogs.store'), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['start_time', 'end_time', 'description']);
    }

    public function testStoreMethodRedirectsBackWithErrorsWhenValidationFails()
    {
        $response = $this->post(route('timeLogs.store'), [
            'start_time' => '2024-02-07 08:00:00', // Provide a start time but not end time or description
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['end_time', 'description']);
    }

    public function testStoreMethodCreatesTimeLogAndRedirects()
    {
        $data = [
            'start_time' => '2024-02-07 08:00:00',
            'end_time' => '2024-02-07 17:00:00',
            'description' => 'Worked on project XYZ',
        ];

        $response = $this->post(route('timeLogs.store'), $data);

        $response->assertRedirect(route('timeLogs.index'));
        $this->assertDatabaseHas('time_logs', $data);
    }

    public function testStoreMethodValidatesStartTimeBeforeEndTime()
    {
        $data = [
            'start_time' => '2024-02-07 10:00:00',
            'end_time' => '2024-02-07 08:00:00', // End time before start time
            'description' => 'Invalid time log',
        ];

        $response = $this->post(route('timeLogs.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors('start_time');
    }

    public function testEditReturnsViewWithCorrectData()
    {
        // Create a sample project and time log
        $project = Project::factory()->create(['name' => 'Test Project']);
        $timeLog = TimeLog::factory()->create(['project_id' => $project->id]);

        // Send a GET request to the edit route with the time log ID
        $response = $this->get(route('timeLogs.edit', ['timeLog' => $timeLog->id]));

        // Assert that the response has a status of 200 (OK)
        $response->assertStatus(200);

        // Assert that the view 'timeLogs.edit' is rendered
        $response->assertViewIs('timeLogs.edit');

        // Assert that the time log and projects are passed to the view
        $response->assertViewHas(['timeLog' => $timeLog, 'projects' => Project::all()]);

        // Additional assertions based on your application logic and view structure
    }

    public function testUpdateRedirectsToIndex()
    {
        // Create a sample time log
        $timeLog = TimeLog::factory()->create();

        // Send a PUT request to the update route with valid data
        $response = $this->put(route('timeLogs.update', ['timeLog' => $timeLog->id]), [
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'description' => 'Updated description',
        ]);

        // Assert that the response redirects to the index route
        $response->assertRedirect(route('timeLogs.index'));

        // Assert that the time log in the database is updated with the new data
        $this->assertDatabaseHas('time_logs', [
            'id' => $timeLog->id,
            'start_time' => now()->format('Y-m-d H:i:s'),
            'end_time' => now()->addHours(2)->format('Y-m-d H:i:s'),
            'description' => 'Updated description',
        ]);

    }

    public function testDestroyRedirectsToIndex()
    {
        // Create a sample time log
        $timeLog = TimeLog::factory()->create();

        // Send a DELETE request to the destroy route
        $response = $this->delete(route('timeLogs.destroy', ['timeLog' => $timeLog->id]));

        // Assert that the response redirects to the index route
        $response->assertRedirect(route('timeLogs.index'));

        // Assert that the time log is no longer in the database
        $this->assertDatabaseMissing('time_logs', ['id' => $timeLog->id]);

    }

    // public function testExportCsv()
    // {
    //     // Create sample time logs
    //     $r=TimeLog::factory()->count(3)->create();
    //     Log::info('Factory Content:', $r->toArray());

    //    // Send a GET request to the exportCsv route
    //     $response = $this->get('exportCsv');
    //     //Log::info('Response:',$response->getContent());

    //     // Assert that the response has a status of 200 (OK)
    //     $response->assertStatus(200);

    //     // Assert that the response is a CSV file
    //     $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

    //     // Assert that the response contains a Content-Disposition header with the correct filename
    //     $response->assertHeader('Content-Disposition', 'attachment; filename=time_logs_export_' . now()->format('YmdHis') . '.csv');

        
    //     // Assert that the CSV content contains the correct header
    //     $response->assertSeeText('Project Name,Start Time,End Time,Description');
    //     $timeLogs = TimeLog::with('project')->get();

    //     // Assert that the CSV content contains the data from the time logs
        
    //     foreach ($timeLogs as $timeLog) {
    //         $response->assertSeeText($timeLog->project->name);
    //         $response->assertSeeText($timeLog->start_time);
    //         $response->assertSeeText($timeLog->end_time);
    //         $response->assertSeeText($timeLog->description);
    //     }
        
    // }


    /*public function testEvaluationDaysView()
    {
        // Create sample time logs with different dates
        TimeLog::factory()->create(['start_time' => now()->subDays(2), 'end_time' => now()]);
        TimeLog::factory()->create(['start_time' => now()->subDays(1), 'end_time' => now()]);
        TimeLog::factory()->create(['start_time' => now(), 'end_time' => now()]);

        // Send a GET request to the evaluation days route
        $response = $this->get(route('timeLogs.evaluation.days'));

        // Assert that the response has a status of 200 (OK)
        $response->assertStatus(200);

        // Assert that the view 'timeLogs.evaluation' is rendered
        $response->assertViewIs('timeLogs.evaluation');

        // Assert that the view has the workedHours variable
        $response->assertViewHas('workedHours');

    }*/
}
