<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\MilestoneResource;
use App\Http\Resources\TimelineEventResource;

class ShareController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
        $this->middleware('auth:api', ['except' => ['show']]);
    }

    public function generate(Request $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $token = $this->projectService->generateShareToken($project);

        return response()->json([
            'success' => true,
            'message' => 'Share link generated.',
            'data' => [
                'url' => url("/share/{$token}"),
                'token' => $token,
            ],
        ], 201);
    }

    public function revoke(Request $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $this->projectService->revokeShareToken($project);

        return response()->json([
            'success' => true,
            'message' => 'Share link revoked.',
        ]);
    }

    public function show(string $token): JsonResponse
    {
        $project = $this->projectService->findByShareToken($token);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired share link.',
            ], 404);
        }

        $data = $this->projectService->getShareData($project);

        return response()->json([
            'success' => true,
            'data' => [
                'project' => new ProjectResource($data['project']),
                'milestones' => MilestoneResource::collection($data['milestones']),
                'timeline' => TimelineEventResource::collection($data['timeline']),
                'payments' => $data['payments'],
            ],
        ]);
    }
}
