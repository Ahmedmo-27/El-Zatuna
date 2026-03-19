<?php

namespace App\OpenApi;

/**
 * Base OpenAPI spec for Swagger UI.
 * Add more @OA\* annotations to your API controllers to document endpoints.
 *
 * @OA\Info(
 *     title="El-Zatuna LMS API",
 *     version="1.0.0",
 *     description="API documentation for El-Zatuna LMS"
 * )
 *
 * @OA\Server(
 *     url="/api",
 *     description="API base URL"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Use the JWT token from login/register. Header: Authorization: Bearer {token}"
 * )
 *
 * @OA\Get(
 *     path="/v1",
 *     summary="API info",
 *     description="Returns API version and documentation links",
 *     operationId="getApiInfo",
 *     tags={"General"},
 *     @OA\Response(
 *         response=200,
 *         description="API info",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="status", type="string", example="stable"),
 *             @OA\Property(property="message", type="string", example="LMS API v1"),
 *             @OA\Property(property="version", type="string", example="v1"),
 *             @OA\Property(property="documentation", type="string", description="Link to docs")
 *         )
 *     )
 * )
 */
class BaseSpec
{
}
