<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductToCartRequest;
use App\Http\Requests\ClearCartRequest;
use App\Http\Requests\RemoveProductFromCartRequest;
use App\Models\Cart;
use App\service\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    //constructor se ejecuta automaticamente cuando se llama al controlador
    public function __construct(private CartService $cartService) {}

    public function index(Request $request): JsonResponse
    {
        //input se utiliza para recuper datos que el cliente envia a travez de una peticion http
        $userId = $request->input('user_id');
        // muestra el carrito con los datos selecionado
        $cart = Cart::where('user_id', $userId)
            ->with(['items.product' => function ($query) {
                $query->select('id', 'name', 'description', 'price', 'category_id');
            }])->first();

        if (!$cart) {
            return response()->json(['message' => 'el carrito esta vacio', 'items' => []], 200);
        }
        return response()->json($cart, 200);
    }

    //funcion para agregar producto al carrito
    public function addProduct(AddProductToCartRequest $request)
    {
        //  ejecutas la logica de negocio
        //si falla algo lanza las exepciones que estan en el servicio
        $cartItem = $this->cartService->addProduct(
            $request->validated('user_id'),
            $request->validated('product_id'),
            $request->validated('quantity')
        );

        // si cumple con todo agrega el producto al carrito
        return response()->json([$cartItem], 200);
    }


    //funcion para vaciar el carrito
    public function clear(ClearCartRequest $request): JsonResponse
    {
        $this->cartService->clear($request->validated('user_id'));
        return response()->json(['message' => 'el carrito se vacio correctamente'], 200);
    }


    //funcion para quitar un producto del carrito
    public function removeProduct(RemoveProductFromCartRequest $request): JsonResponse
    {
        $this->cartService->removeProduct(
            $request->validated('user_id'),
            $request->validated('product_id'),
        );
        return response()->json(['message' => 'producto quitado con éxito'], 200);
    }

    //funcion para eliminar un carrito
    public function destroy(ClearCartRequest $request): JsonResponse
    {
        $this->cartService->deleteCart($request->validated('user_id'));
        return response()->json(['message' => 'carrito eliminado con éxito'], 200);
    }
}
