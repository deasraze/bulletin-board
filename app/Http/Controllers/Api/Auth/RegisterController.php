<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\UseCases\Auth\RegisterService;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    private RegisterService $service;

    public function __construct(RegisterService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Profile"},
     *     @OA\Parameter(name="body", in="path", required=true, @OA\Schema(ref="#/components/schemas/RegisterRequest")),
     *     @OA\Response(
     *         response=201,
     *         description="Success response",
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $this->service->register($request);

        return response()->json([
            'success' => 'Check your email and click on the link to verify.'
        ], Response::HTTP_CREATED);
    }
}
