<?php

namespace App\Modules\Tasks\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tasks\Requests\StoreTaskRequest;
use App\Modules\Tasks\Requests\UpdateTaskRequest;
use App\Modules\Tasks\Resources\TaskCollection;
use App\Modules\Tasks\Resources\TaskResource;
use App\Modules\Tasks\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request): JsonResponse|TaskCollection
    {
        $filters = $request->only(['status', 'priority', 'title', 'per_page', 'sort_by', 'sort_order']);
        $service = $this->taskService->listTasks($filters);

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return (new TaskCollection($service->getData()))->response()->setStatusCode(200);
    }

    public function store(StoreTaskRequest $request): JsonResponse|TaskResource
    {
        $service = $this->taskService->createTask($request->validated());

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return (new TaskResource($service->getData()))->response()->setStatusCode(201);
    }

    public function update(UpdateTaskRequest $request, int $id): JsonResponse|TaskResource
    {
        $service = $this->taskService->updateTask($id, $request->validated());

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return (new TaskResource($service->getData()))->response()->setStatusCode(200);
    }

    public function destroy(int $id): JsonResponse
    {
        $service = $this->taskService->deleteTask($id);

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return response()->json($service->getData(), 200);
    }
}
