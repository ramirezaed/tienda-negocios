<?php

namespace App\Exceptions;

use Exception;

class CartNotFoundException extends Exception
{
    // codigo http 404 Not Found recurso no encontrado
    protected $code = 404;
    //constructor se ejecuta automaticamente cuando se llama a la excepion
    //recibe como parametro el msj, o por defecto tiene uno
    public function __construct($message = 'El carrito de compras solicitado no existe o ya fue eliminado.')
    {
        //parent, hace referencia a la clase padre
        parent::__construct($message, $this->code);
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage()
        ], $this->code);
    }
}
