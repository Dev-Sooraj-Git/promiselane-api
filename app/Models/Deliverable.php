<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deliverable extends Model
{
    protected $fillable = ['milestone_id', 'file_name', 'path', 'mime_type', 'size', 'notes'];
    protected function casts()
    {
        return [
            'size' => 'integer'
        ];
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }
}
