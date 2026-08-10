<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    // codigo http 422 nnprocessable content (entidad no procesable)
    protected $code = 422;
    //recibe como parametro el msj, o por defecto tiene uno
    public function __construct($message = 'El producto seleccionado no cuenta con stock suficiente.')
    {
        //parent, hace referencia a la clase padre
        parent::__construct($message, $this->code);
    }
    //funcion para mostrar el mensaje y el codigo de error
    public function render($request)
    {
        return response()->json([
            //getMessage, obtieen el msj que se envia por parametro
            'message' => $this->getMessage()
        ], $this->code);
    }
}
