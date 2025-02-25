<?php

// app/Models/JobApplication.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_applications';
    protected $fillable = [
        'job_id',
        'user_id',
        'resume_url',
        'cover_letter',
        'status',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPost::class, 'job_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
