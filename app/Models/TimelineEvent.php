<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineEvent extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'event_type',
        'title',
        'description',
        'metadata',
    ];

    protected function casts()
    {
        return [
            'metadata' => 'json',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
