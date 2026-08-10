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
}
