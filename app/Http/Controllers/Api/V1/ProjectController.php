<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
        $this->middleware('auth:api');
    }

    public function index(Request $request): JsonResponse
    {
        $projects = $this->projectService->list(
            Auth::id(),
            $request->query('status')
        );

        return response()->json([
            'success' => true,
            'data' => ProjectResource::collection($projects),
        ]);
    }
}
