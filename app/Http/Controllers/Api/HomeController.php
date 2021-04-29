<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\OpenApi(
 *    @OA\Server(
 *         url="localhost:8080/api",
 *         description="API server"
 *     ),
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Board API",
 *         description="HTTP JSON API",
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     securityScheme="OAuth2",
 *     @OA\Flow(
 *         flow="password",
 *         tokenUrl="https://localhost:8080/oauth/token",
 *         scopes={}
 *     )
 * )
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     in="header",
 *     securityScheme="bearer",
 *     name="Authorization"
 * )
 *
 * @OA\Schema(
 *         schema="ErrorModel",
 *         type="object",
 *         required={"code", "message"},
 *         @OA\Property(
 *             property="code",
 *             type="integer",
 *         ),
 *         @OA\Property(
 *             property="message",
 *             type="string"
 *         )
 * )
 */
class HomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/",
     *     tags={"Info"},
     *     @OA\Response(
     *         response="200",
     *         description="API version",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="version", type="string")
     *         ),
     *     )
     * )
     */
    public function home(): array
    {
        return [
            'name' => 'Laravel board API',
        ];
    }
}
