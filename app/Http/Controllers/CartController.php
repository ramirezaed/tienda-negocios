<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    //funcion para ver el carrito
    public function index(Request $request)
    {
        try {
            //input se utiliza para recuper datos que el cliente envia a travez de una peticion http
            $userId = $request->input('user_id');
            //busca el carrito que pertenece al usuario y devuleve el primero
            $cart = Cart::where('user_id', $userId)->with('items.product')->first();
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
            return response()->json(['message' => 'error interno al intentar agregar un producto al carrito'], 500);
        }
    }
    //funcion para vaciar el carrito
    public function clear(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);
        try {
            $cart = Cart::where('user_id', $validated['user_id'])->first();
            if ($cart) {
                // elimina todos los productos del carrito
                CartItem::where('cart_id', $cart->id)->delete();
            }
            return response()->json(['message' => 'se vacio correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error innterno al intentar vaciar el carrito'], 500);
        }
    }
}
