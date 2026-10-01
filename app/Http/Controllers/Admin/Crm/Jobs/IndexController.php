<?php

namespace App\Http\Controllers\Admin\Crm\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index()
    {
        $queuedJobs = DB::table('jobs')->get();

        foreach ($queuedJobs as $job) {
            $job->payload = json_decode($job->payload, true);
            $job->reserved_at = Carbon::createFromTimestamp($job->reserved_at)->toDateTimeString();
            $job->available_at = Carbon::createFromTimestamp($job->available_at)->toDateTimeString();
            $job->created_at = Carbon::createFromTimestamp($job->created_at)->toDateTimeString();
        }

        $failedJobs = DB::table('failed_jobs')->get();

        foreach ($failedJobs as $job) {
            $job->payload = json_decode($job->payload, true);
        }

        // Check if queue:work process is running
        $isQueueWorkerRunning = $this->isQueueWorkerRunning();

        return view('admin.crm.jobs.index', compact('queuedJobs', 'failedJobs', 'isQueueWorkerRunning'));
    }

    public function show($id)
    {
        $job = DB::table('jobs')->find($id);
        if (!$job) {
            $job = DB::table('failed_jobs')->find($id);
            if (!$job) {
                abort(404, 'Job not found');
            }
            $job->status = 'failed';
        } else {
            $job->status = 'queued';
        }

        return view('admin.crm.jobs.show', compact('job'));
    }

    public function retry($id)
    {
        \Artisan::call('queue:retry', ['id' => $id]);

        return redirect()->route('admin.crm.jobs.index')->with('success', 'Job retried successfully');
    }

    public function forget($id)
    {
        \Artisan::call('queue:forget', ['id' => $id]);

        return redirect()->route('admin.crm.jobs.index')->with('success', 'Job forgotten successfully');
    }

    private function isQueueWorkerRunning()
    {
        if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
            // Windows
            $process = new Process(['tasklist']);
            $process->run();
            $output = $process->getOutput();

            return strpos($output, 'php.exe') !== false && strpos($output, 'artisan queue:work') !== false;
        } else {
            // Unix-like systems
            $process = new Process(['pgrep', '-fl', 'queue:work']);
            $process->run();

            return $process->isSuccessful();
        }
    }

    public function runNow(Request $request, $job)
    {
        // Find the job instance (assuming $job is the ID or identifier of the job)
        $jobInstance = YourJob::find($job);

        if (!$jobInstance) {
            return back()->with('error', 'Job not found.');
        }

        // Dispatch the job immediately
        dispatch_now($jobInstance);

        return back()->with('success', 'Job executed successfully.');
    }

    public function removeJob($id)
    {
        // Remove job from the jobs table
        $deleted = DB::table('jobs')->where('id', $id)->delete();

        if ($deleted) {
            return back()->with('success', 'Job removed successfully.');
        } else {
            return back()->with('error', 'Failed to remove the job.');
        }
    }

    public function removeFailedJob($id)
    {
        // Remove job from the failed_jobs table
        $deleted = DB::table('failed_jobs')->where('id', $id)->delete();

        if ($deleted) {
            return back()->with('success', 'Failed job removed successfully.');
        } else {
            return back()->with('error', 'Failed to remove the failed job.');
        }
    }
}
