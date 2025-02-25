<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobController extends Controller
{
    public function __construct(private JobService $jobService)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $jobs = $this->jobService->getJobs($request->all());
        return JobResource::collection($jobs);
    }

    public function store(CreateJobRequest $request): JsonResponse
    {
        $job = $this->jobService->createJob($request->validated());
        return response()->json(new JobResource($job), 201);
    }


}
