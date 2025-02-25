<?php
// app/Http/Controllers/JobApplicationController.php
namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JobApplicationController extends Controller
{
    /**
     * 
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:job_posts,id',
            'user_id' => 'required|exists:users,id',
            'resume_url' => 'required',
            'cover_letter' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        try {
            $job = Job::findOrFail($data['job_id']);

            if ($job->status !== 'active') {
                return response()->json([
                    'message' => 'This job is no longer accepting applications.',
                    'error' => 'job_inactive'
                ], 422);
            }

            $data['status'] = $data['status'] ?? 'pending';
            $application = DB::transaction(function () use ($data) {
                return JobApplication::create($data);
            });

            return response()->json([
                'message' => 'Application submitted successfully',
                'data' => [
                    'id' => $application->id,
                    'job_id' => $application->job_id,
                    'user_id' => $application->user_id,
                    'resume_url' => $application->resume_url,
                    'cover_letter' => $application->cover_letter,
                ]
            ], 201);

        } catch (\Exception $e) {
            // 7. Handle exceptions
            return response()->json([
                'message' => 'Failed to create application',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
