<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductToCartRequest;
use App\Http\Requests\ClearCartRequest;
use App\Http\Requests\RemoveProductFromCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\service\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    //constructor se ejecuta automaticamente cuando se llama al controlador
    public function __construct(private CartService $cartService) {}

    public function index(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener el carrito'], 500);
        }
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



    public function removeProduct(RemoveProductFromCartRequest $request): JsonResponse
    {

        $this->cartService->removeProduct(
            $request->validated('user_id'),
            $request->validated('product_id'),
        );
        return response()->json(['message' => 'producto quitado con éxito'], 200);
    }

    //funcion para eliminar un carrito
    public function deleteCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        try {
            //busca el carrito del usuario
            $cart = Cart::where('user_id', $validated['user_id'])->with('items')->first();
            //verifica si el carrito existe
            if (!$cart) {
                return response()->json(['message' => 'El usuario no tiene ningún carrito creado'], 404);
            }
            //devuelve stock de cada producto antes de borrarlos del carrito
            foreach ($cart->items as $item) {
                // busca producto  en la tabla de productos
                $product = Product::find($item->product_id);

                if ($product) {
                    // incrementamos su stock con la cantidad que el usuario tenía reservada en su carrito
                    $product->increment('stock', $item->quantity);
                }
            }

            //elimina todos los productos 'cart_items'
            CartItem::where('cart_id', $cart->id)->delete();
            //elimina el carrito principal en la tabla 'cart'
            $cart->delete();
            return response()->json(['message' => 'carrito eliminado con exito'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error interno al intentar eliminar el carrito'], 500);
        }
    }
}
