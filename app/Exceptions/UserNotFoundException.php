<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    //codigo http 404 not found recurso no encotnrado
    protected $code = 404;

    public function __construct($message = 'No hay datos del usuario.')
    {
        parent::__construct($message, $this->code);
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage()
        ], $this->code);
    }
}
