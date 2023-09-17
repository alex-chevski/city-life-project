<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="CityLife API",
 *     description="HTTP JSON API",
 *     @OA\Contact(name="")
 * )
 *
 *@OA\Server(
 *     url="HTTPS"
 * )
 *
 * @OA\SecurityScheme(
 *         securityScheme="OAuth2",
 *         type="oauth2",
 *         name="auth",
 *         in="query",
 *     ),
 *
 * @OA\SecuritySchemes(
 *        securityScheme="Bearer",
*         type="apiKey",
*         name="Authorization",
*         in="query",
 * )
 *
 *
 */
class HomeController
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
    public function home()
    {
        return [
            'name' => 'Board API',
        ];
    }
}
