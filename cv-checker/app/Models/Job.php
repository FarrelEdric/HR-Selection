<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'title',
        'description',
        'qualification',
        'benefit',
        'location',
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
