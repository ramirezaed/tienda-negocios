<?php

namespace App\Exceptions;

use Exception;

class CartItemNotFoundException extends Exception
{
    // codigo http 404 not found recurso no encontrado
    protected $code = 404;
    //constructor se ejecuta automaticamente cuando se llama a la excepion
    //recibe como parametro el msj, o por defecto tiene uno
    public function __construct($message = 'El producto seleccionado no se encuentra en tu carrito de compras.')
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
