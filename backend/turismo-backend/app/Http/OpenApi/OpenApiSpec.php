<?php

namespace App\Http\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "API de Turismo Necochea",
    description: "Documentación de la API para la aplicación de Turismo de Necochea"
)]

#[OA\Contact(
    email: "tudai@alumnos.exa.unicen.edu.ar"
)]

#[OA\License(
    name: "Apache 2.0",
    url: "http://www.apache.org/licenses/LICENSE-2.0.html"
)]

#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: "Servidor principal de la API"
)]

class OpenApiSpec
{
}
