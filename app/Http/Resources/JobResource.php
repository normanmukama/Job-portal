<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title, 
            'description' => $this->description,
            'company' => $this->company,
            'location' => $this->location,
            'salary' => $this->salary,
            'type' => $this->type,
            'category'=>$this->category,
            'user_id'=>$this->user_id
        ];
    }
}

