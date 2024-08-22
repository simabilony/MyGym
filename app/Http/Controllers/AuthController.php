<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Services\ApiResponseService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * AuthController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login a user.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $response = $this->authService->login($credentials);

        if ($response['status'] === 'error') {
            return ApiResponseService::error($response['message'], $response['code']);
        }

        return ApiResponseService::success([
            'user' => $response['user'],
            'authorisation' => [
                'token' => $response['token'],
                'type' => 'bearer',
            ]
        ], 'Login successful', $response['code']);
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $response = $this->authService->register($data);

        return ApiResponseService::success([
            'user' => $response['user'],
            'authorisation' => [
                'token' => $response['token'],
                'type' => 'bearer',
            ]
        ], 'User created successfully', $response['code']);
    }

    /**
     * Logout the current user.
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $response = $this->authService->logout();

        return ApiResponseService::success(null, $response['message'], $response['code']);
    }
}
