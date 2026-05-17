<?php

namespace App\Services;

use App\Models\Milestone;
use App\Models\Payment;

class PaymentService
{
    public function listByMilestone(Milestone $milestone)
    {
        return $milestone->payments()->latest()->get();
    }

    public function create(Milestone $milestone, array $data)
    {
        return $milestone->payments()->create($data);
    }

    public function delete(Payment $payment)
    {
        return $payment->delete();
    }
}
