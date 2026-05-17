<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'milestone_id' => $this->milestone_id,
            'amount' => $this->amount,
            'paid_at' => $this->paid_at,
            'method' => $this->method,
            'reference' => $this->reference,
            'notes' => $this->notes,
            'created_at' => $this->created_at
        ];
    }
}
