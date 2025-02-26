<?php
// app/Services/JobApplicationService.p

namespace App\Services;

use App\Models\Job;
use App\Models\JobApplication;
use App\Exceptions\JobNotActiveException;
use Illuminate\Support\Facades\DB;
use Exception;

class JobApplicationService
{
    /**
     * Create a new job application
     *
     * @param array $data
     * @return JobApplication
     * @throws JobNotActiveException
     */



public function createApplication(array $data): JobApplication
{
    // Check if job exists and is active
    $job = Job::findOrFail($data['job_id']);

    if ($job->status !== 'active') {
        throw new JobNotActiveException('This job is no longer accepting applications.');
    }

    // Set default status if not provided
    if (!isset($data['status'])) {
        $data['status'] = 'pending';
    }

    try {
        return DB::transaction(function () use ($data) {
            return JobApplication::create($data);
        });
    } catch (Exception $e) {
        logger()->error('Failed to create job application: ' . $e->getMessage());
        throw $e;
    }
}
}
