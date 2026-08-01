<?php

namespace App\Modules\Auth\Services;

use App\Core\BaseService;
use App\Modules\Auth\Repositories\AuthRepository;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseService
{
    protected AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $data): self
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->authRepository->create($data);
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->setData([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(array $credentials): self
    {
        $user = $this->authRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->setError('Invalid credentials', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->setData([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout($user): self
    {
        $user->currentAccessToken()->delete();
        return $this->setData(['message' => 'Logged out successfully']);
    }
}
