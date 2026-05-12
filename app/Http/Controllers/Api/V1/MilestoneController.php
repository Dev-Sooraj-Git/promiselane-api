<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Project;
use App\Services\MilestoneService;
use App\Http\Resources\MilestoneResource;



class MilestoneController extends Controller
{
    protected MilestoneService $milestoneService;

    public function __construct(MilestoneService $milestoneService)
    {
        $this->milestoneService = $milestoneService;
        $this->middleware('auth:api');
    }

    public function index(Project $project)
    {

        $this->authorize('view', $project);
        $milestones = $this->milestoneService->listByProject($project);

        return response()->json(
            [
                'success' => true,
                'data' => MilestoneResource::collection($milestones),
            ]
        );
    }
}
