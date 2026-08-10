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

    public function addProduct(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            //verfica que el prodcuto exista
            $product = Product::findOrFail($data['product_id']);
            if ($product->stock < $data['quantity']) {
                return response()->json(['message' => 'No hay stock suficiente'], 400);
            }
            //busca el carrito que pertenece al usuario, si no tiene lo crea
            $cart = Cart::firstOrCreate(['user_id' => $data['user_id']]);

            //verifica si ese producto ya esta en el carro
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            //si el producto ya esta en el carrito suma la cantidad y se calcula el total
            if ($cartItem) {
                $quantity = $cartItem->quantity + $data['quantity'];
                $cartItem->update([
                    'quantity' => $quantity,
                    'sub_total' => $quantity * $product->price,
                ]);
            } else {
                //si el producto no estaba en el carrito crea un nuevo cartItem
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $data['quantity'],
                    'price' => $product->price,
                    'sub_total' => $data['quantity'] * $product->price,
                ]);
            }

            //en esta etapa del proyecto el stock se decuenta al agregar al acarrito
            //descuenta el stock de los productos
            $product->decrement('stock', $data['quantity']);
            $cart->update([
                'total' => CartItem::where('cart_id', $cart->id)
                    ->sum('sub_total')
            ]);

            return response()->json(['message' => 'Producto agregado con exito']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error innterno al agregar un producto al carrito'], 500);
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
            $cart->update(["total" => 0.00]);
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
            //elimina el item
            $cartItem->delete();
            ///Suma los subtotales de los productos que queden
            $nuevoTotal = CartItem::where('cart_id', $cart->id)->sum('sub_total');

            $cart->update(['total' => $nuevoTotal]);

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
