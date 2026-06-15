<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateData extends Model
{
    protected $table = 'candidate_data';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'birthdate',
        'educational',
        'job_history',
        'skills',
        'summarize',
        'vote',
        'consideration',
        'cv_link',
        'job_id',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
