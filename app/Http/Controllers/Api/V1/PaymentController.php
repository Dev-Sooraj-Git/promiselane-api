<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Milestone;
use App\Models\Payment;
use App\Models\Project;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    protected PaymentService $PaymentService;

    public function __construct(PaymentService $PaymentService)
    {
        $this->PaymentService = $PaymentService;
        $this->middleware('auth:api');
    }

    public function index(Project $project, Milestone $milestone): JsonResponse
    {
        $this->authorize('view', $project);
        $payments = $this->PaymentService->listByMilestone($milestone);

        return response()->json([
            "success" => true,
            "data" => PaymentResource::collection($payments)
        ]);
    }

    public function store(Request $request, Project $project, Milestone $milestone): JsonResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) use ($milestone) {
                    $alreadyPaid = $milestone->payments()->sum('amount');
                    $remaining = $milestone->amount - $alreadyPaid;
                    if ($value > $remaining) {
                        $fail("Amount ₹{$value} exceeds remaining balance of ₹{$remaining} for this milestone.");
                    }
                },
            ],
            'paid_at' => 'required|date',
            'method' => 'required|in:bank_transfer,upi,cash,other',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $payment = $this->PaymentService->create($milestone, $request->only(['amount', 'paid_at', 'method', 'reference', 'notes']));

        return response()->json([
            "success" => true,
            "message" => "Payment details Added",
            "data"  => new PaymentResource($payment)
        ], 201);
    }

    public function destroy(Project $project, Milestone $milestone, Payment $payment): JsonResponse
    {
        $this->authorize('delete', $project);
        $this->PaymentService->delete($payment);

        return response()->json([
            "success" => true,
            "message" => "Payment Detial deleted"
        ]);
    }
}
