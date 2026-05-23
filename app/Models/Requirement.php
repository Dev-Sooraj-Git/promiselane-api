<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    protected $fillable = [
        'project_id',
        'milestone_id',
        'source',
        'content',
        'status',
        'is_in_scope',
        'clarification_notes',
        'attachments'
    ];

    public function casts()
    {
        return [
            'is_in_scope' => 'boolean',
            'attachments' => 'json'
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }
}
