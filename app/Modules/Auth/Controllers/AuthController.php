<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $service = $this->authService->register($request->validated());

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return response()->json(['data' => $service->getData()], 200);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $service = $this->authService->login($request->validated());

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return response()->json(['data' => $service->getData()], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $service = $this->authService->logout($request->user());

        if ($service->hasError()) {
            return response()->json(['error' => $service->getError()], $service->getErrorCode());
        }

        return response()->json(['data' => $service->getData()], 200);
    }
}
