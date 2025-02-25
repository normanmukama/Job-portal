<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'job_id' => $this->job_id,
            'user_id' => $this->user_id,
            'resume_url' => $this->resume_url,
            'cover_letter' => $this->cover_letter,
            // 'status' => $this->status,
            'job' => new JobResource($this->whenLoaded('job')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}

