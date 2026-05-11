<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'amount',
        'due_date',
        'status',
        'order_index',
        'paid_at'
    ];

    protected function casts()
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'order_index' => 'integer'
        ];
    }

    public function Project()
    {
        return $this->belongsTo(Project::class);
    }
}
