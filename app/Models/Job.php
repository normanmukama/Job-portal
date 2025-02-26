<?php

// app/Models/Job.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_posts';
    protected $fillable = [
        'title',
        'description',
        'company',
        'location',
        'salary',
        'type',
        'status', 
        'category',
        'user_id'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
