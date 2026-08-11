<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Tienda Laravel API',
    description: 'API para la gestión de una tienda',
    contact: new OA\Contact(
        email: 'admin@tienda.com'
    )
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Servidor local'
)]
class SwaggerInfo {}
