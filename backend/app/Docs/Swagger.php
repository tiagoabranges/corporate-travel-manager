<?php

namespace App\Docs;

/**
 * @OA\Info(
 *     title="Corporate Travel API",
 *     version="1.0.0",
 *     description="API de gerenciamento de viagens corporativas"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Ambiente Local"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class Swagger {}
