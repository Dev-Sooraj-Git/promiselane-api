<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'client_name',
        'client_email',
        'total_amount',
        'status',
        'started_at',
        'share_token',
        'completed_at'
    ];

    protected function casts()
    {
        return [
            'total_amount' => 'decimal:2',
            'started_at' => 'date',
            'completed_at' => 'date'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }

    public function timelineEvents()
    {
        return $this->hasMany(TimelineEvent::class);
    }
}
