<?php

namespace App\service;

use App\Exceptions\InsufficientStockException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function addProduct(int $userId, int $productId, int $quantity): CartItem
    {
        //transaction asegura que se ejecute todo o nada
        //
        return DB::transaction(function () use ($userId, $productId, $quantity) {
            //lockforUpdate: bloquea ese producto hasta que la transaction termine
            //evita que dos usuarios quieran comprar el mismo producto al mismo tiempo
            $product = Product::lockForUpdate()->findOrFail($productId);

            //verifica que el stock sea suficiente
            if ($product->stock < $quantity) {
                throw new InsufficientStockException("stock insuficiente, el producto seleccionado solo cuenta con {$product->stock} unidades disponibles");
            }

            //busca el carrito que pertece al usuario, si no existe lo crea
            $cart = Cart::firstOrCreate(['user_id' => $userId]);
            //busca el producto en el carrito, si ya estaba, si no estaba lo agrega
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->first();

            //el producto ya esta en el carrito
            if ($cartItem) {
                $cartItem->quantity += $quantity; //suma la cantidad guardada mas la nueva
                $cartItem->sub_total = $cartItem->quantity * $product->price; //calucla el subtotal
                $cartItem->save(); //guarda los cambios
            } else { //si el producto no estaba en el carrito, lo agrega
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'sub_total' => $quantity * $product->price,
                ]);
            }

            $product->decrement('stock', $quantity); //actualiza stock
            //guarda en total el nuevo resultado
            $cart->update(['total' => $cart->items()->sum('sub_total')]); //suma los subtotales del carrito

            return $cartItem;
        });
    }
}
