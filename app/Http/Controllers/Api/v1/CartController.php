<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\cart\AddProductToCartRequest;
use App\Http\Requests\cart\RemoveProductFromCartRequest;
use App\Http\Resources\cart\addProductResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\service\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //constructor se ejecuta automaticamente cuando se llama al controlador
    public function __construct(private CartService $cartService) {}

    public function index(Request $request): JsonResponse
    {
        //se obtiene el user id con la funcion auth
        $userId = auth('api')->id();
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
    public function addProduct(AddProductToCartRequest $request): JsonResponse
    {
        $cartItem = $this->cartService->addProduct($request->toDTO());
        return response()->json(new addProductResource($cartItem), 200);
    }


    //funcion para vaciar el carrito
    public function clear(): JsonResponse
    {
        $this->cartService->clear(auth('api')->id());
        return response()->json(['message' => 'el carrito se vacio correctamente'], 200);
    }


    //funcion para quitar un producto del carrito
    public function removeProduct(RemoveProductFromCartRequest $request): JsonResponse
    {
        $this->cartService->removeProduct(
            auth('api')->id(),
            $request->validated('product_id'),
            $request->validated('quantity')
        );
        return response()->json(['message' => 'producto quitado con éxito'], 200);
    }

    //funcion para eliminar un carrito
    public function destroy(): JsonResponse
    {
        $this->cartService->deleteCart(auth('api')->id());
        return response()->json(['message' => 'carrito eliminado con éxito'], 200);
    }
}
