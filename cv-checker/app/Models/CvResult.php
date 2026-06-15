<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvResult extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'birthdate',
        'position',
        'score',
        'consideration',
        'summary',
        'skills',
        'education',
        'job_history',
        'cv_link',
        'processed_at',
    ];

    protected $casts = [
        'score' => 'integer',
        'processed_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'status_badge_color',
    ];

    /**
     * Scope to filter by position.
     */
    public function scopeFilterByPosition($query, $position)
    {
        if ($position) {
            return $query->where('position', $position);
        }
        return $query;
    }

    /**
     * Accessor for status label based on score.
     */
    public function getStatusLabelAttribute()
    {
        if ($this->score >= 8) {
            return 'Recommended';
        } elseif ($this->score >= 5) {
            return 'Consider';
        } else {
            return 'Not Recommended';
        }
    }

    /**
     * Accessor for status badge color based on score.
     */
    public function getStatusBadgeColorAttribute()
    {
        if ($this->score >= 8) {
            return 'bg-green-100 text-green-800 border-green-200';
        } elseif ($this->score >= 5) {
            return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        } else {
            return 'bg-red-100 text-red-800 border-red-200';
        }
    }
}
