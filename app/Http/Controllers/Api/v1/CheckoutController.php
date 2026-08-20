<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SummaryFormRequest;
use App\Http\Resources\SummaryResource;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\View\AnonymousComponent;
use ReturnTypeWillChange;

class CheckoutController extends Controller
{
    //inyecta el servicio checkout en el contructor, se ejecuta automaticamente
    public function __construct(private CheckoutService $checkoutService) {}

    public function summary(SummaryFormRequest $request): JsonResponse
    {
        //request es un objeto que viene de formrequest, se extrae el id que es 
        //int que espera el servicio
        $sumary = $this->checkoutService->getSummary($request->users()->id);
        return response()->json($sumary);
    }
}
