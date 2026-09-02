<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\checkout\CheckoutFormRequest;
use App\Http\Requests\summary\SummaryFormRequest;
use App\service\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    //inyecta el servicio checkout en el contructor, se ejecuta automaticamente
    public function __construct(private CheckoutService $checkoutService) {}

    public function summary(SummaryFormRequest $request): JsonResponse
    {
        //obtiene el id del usuario que viene en el request
        //input busca en el request el dato especifico que se le pide -> (user_id)
        // $userId = $request->input('user_id');
        $userId = auth('api')->id();
        //request es un objeto que viene de formrequest, se extrae el id que es 
        //int que espera el servicio
        $sumary = $this->checkoutService->getSummary();
        return response()->json($sumary);
    }


    public function checkout(CheckoutFormRequest $request): JsonResponse
    {

        //llama al servicio checkout, pasa los datos validados del request
        //se registra la compra en la tabla order
        $order = $this->checkoutService->processCheckout(
            $request->validated('shipping_address'),
        );

        return response()->json([
            'message' => 'Compra confirmada con éxito.',
            'order' => $order->load('items')
        ], 201);
    }
}
