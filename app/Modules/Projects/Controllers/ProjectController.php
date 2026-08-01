<?php

namespace App\Modules\Projects\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Projects\Requests\StoreProjectRequest;
use App\Modules\Projects\Requests\UpdateProjectRequest;
use App\Modules\Projects\Resources\ProjectCollection;
use App\Modules\Projects\Resources\ProjectResource;
use App\Modules\Projects\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Request $request): JsonResponse|ProjectCollection
    {
        $filters = $request->only(['per_page', 'sort_by', 'sort_order']);
        $service = $this->projectService->listUserProjects($filters);

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return new ProjectCollection($service->getData());
    }

    public function store(StoreProjectRequest $request): JsonResponse|ProjectResource
    {
        $service = $this->projectService->createProject($request->validated());

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return (new ProjectResource($service->getData()))->response()->setStatusCode(200);
    }

    public function show(int $id): JsonResponse|ProjectResource
    {
        $service = $this->projectService->viewProject($id);

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return new ProjectResource($service->getData());
    }

    public function update(UpdateProjectRequest $request, int $id): JsonResponse|ProjectResource
    {
        $service = $this->projectService->updateProject($id, $request->validated());

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return new ProjectResource($service->getData());
    }

    public function destroy(int $id): JsonResponse
    {
        $service = $this->projectService->deleteProject($id);

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return response()->json($service->getData(), 200);
    }
}
