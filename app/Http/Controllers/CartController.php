<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
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

    //funcion para agregar un producto al carrrito
    public function addProduct(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        try {
            // verifica si hay stock suficiente 
            $product = Product::find($validated['product_id']);
            if ($product->stock < $validated['quantity']) {
                return response()->json(['message' => 'no hay stock disponible'], 400);
            }
            // busca el carrito del usuario o crea uno de forma masiva si no existe
            $cart = Cart::firstOrCreate(['user_id' => $validated['user_id']]);
            // verificar si el producto ya estaba en el carrito
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($cartItem) {
                // si ya existe, validamos el stock total acumulado y suma la cantidad
                if ($product->stock < ($cartItem->quantity + $validated['quantity'])) {
                    return response()->json(['message' => 'no hay stock suficiente'], 400);
                }
                $cartItem->increment('quantity', $validated['quantity']);
            } else {
                // si es un producto nuevo en el carrito lo agrega
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'price' => $product->price,
                ]);
            }
            //en esta etapa del proyecto el stock del producto se descuenta al momento de 
            //agregar el producto al carrito
            $product->decrement('stock', $validated['quantity']);
            return response()->json(['message' => 'producto agregado cn exito'], 200);
        } catch (\Exception $e) {
            // return response()->json(['message' => 'error interno al intentar agregar un producto al carrito'], 500);
            return response()->json([
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ], 500);
        }
    }
    //funcion para vaciar el carrito
    public function clear(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);
        try {
            $cart = Cart::where('user_id', $validated['user_id'])->with('items')->first();
            // verifica que el carrito exista
            if (!$cart) {
                return response()->json(['message' => 'El usuario no tiene ningún carrito creado'], 404);
            }
            // devuevle el stock a cada producto 
            foreach ($cart->items as $item) {
                $product = Product::find($item->product_id);

                if ($product) {
                    // incrementael stock con la cantidad
                    $product->increment('stock', $item->quantity);
                }
            }
            // elimina todos los productos de la tabla 'cart_items'
            CartItem::where('cart_id', $cart->id)->delete();
            return response()->json(['message' => 'el carrito se vacio correctamente '], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error innterno al intentar vaciar el carrito'], 500);
        }
    }

    public function removeProduct(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'product_id' => 'required|integer|exists:products,id',
        ]);
        try {
            // busca el carrito del usuario
            $cart = Cart::where('user_id', $validated['user_id'])->first();
            if (!$cart) {
                return response()->json(['message' => 'carrito no encontrado'], 404);
            }
            // busca el producto en el carrito 
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $validated['product_id'])
                ->first();
            if (!$cartItem) {
                return response()->json(['message' => 'producto no encontrado'], 404);
            }
            //Eliminar el registro de la tabla intermedia
            $cartItem->delete();
            //cuando se elimina el producto del carrtio se incrementa su stock 
            $cartItem->decrement('stock', $validated['quantity']);
            return response()->json(['message' => 'producto quitado con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error interno al intentar quitar el producto del carrito'], 500);
        }
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
