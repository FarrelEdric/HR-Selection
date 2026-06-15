<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'cv_file',
        'portfolio_file',
        'ktp_file',
        'kk_file',
        'linkedin',
        'job_id',
        'status',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function cvResult()
    {
        return $this->hasOne(CvResult::class);
    }
}
