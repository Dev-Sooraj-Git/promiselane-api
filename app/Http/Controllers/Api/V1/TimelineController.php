<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TimelineEventResource;
use App\Models\Project;
use App\Services\TimelineService;
use Illuminate\Http\JsonResponse;

class TimelineController extends Controller
{
    protected TimelineService $timelineService;

    public function __construct(TimelineService $timelineService)
    {
        $this->timelineService = $timelineService;
        $this->middleware('auth:api');
    }

    public function index(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $events = $this->timelineService->listByProject($project);

        return response()->json([
            'success' => true,
            'data' => TimelineEventResource::collection($events),
        ]);
    }
}
