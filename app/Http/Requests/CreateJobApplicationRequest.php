<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'job_id' => 'required|exists:jobs,id',
            'user_id' => 'required|exists:users,id',
            'resume_url' => 'required|url',
            'cover_letter' => 'required|string',
        ];
    }
}
