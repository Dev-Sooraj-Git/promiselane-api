<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:bug,feature,other',
            'message' => 'required|string|min:5|max:1000',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted. Thank you!',
        ], 201);
    }
}
