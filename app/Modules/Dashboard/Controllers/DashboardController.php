<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): JsonResponse
    {
        $service = $this->dashboardService->getDashboardStats();

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return response()->json(['data' => $service->getData()], 200);
    }
}
