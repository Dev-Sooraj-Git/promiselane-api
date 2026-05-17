<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['milestone_id', 'amount', 'paid_at', 'method', 'reference', 'notes'];

    public function casts(){
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'date',
        ];
    }

    public function milestone(){
       return $this->belongsTo(Milestone::class);
    }
}
