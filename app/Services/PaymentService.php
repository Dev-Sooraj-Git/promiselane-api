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
        $payment = $milestone->payments()->create($data);

        app(TimelineService::class)->log(
            $milestone->project,
            'payment_recorded',
            "Payment of ₹{$payment->amount} recorded for milestone '{$milestone->title}'"
        );

        return $payment;
    }

    public function delete(Payment $payment)
    {
        app(TimelineService::class)->log(
            $payment->milestone->project,
            'payment_deleted',
            "Payment of ₹{$payment->amount} deleted from milestone '{$payment->milestone->title}'"
        );
        return $payment->delete();
    }
}
