<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'type' => 'required|in:full-time,part-time,contract',
            'status' => 'required|in:active,filled,expired',
            'category'=>'required|string',
            'user_id'=>'required'
        ];
    }
}
