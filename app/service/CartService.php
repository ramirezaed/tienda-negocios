<?php

namespace App\service;

use App\DTO\cart\addProductsToCartDTO;
use App\Exceptions\CartItemNotFoundException;
use App\Exceptions\CartNotFoundException;
use App\Exceptions\InsufficientStockException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;


class CartService
{

    // metodo para devolver stock reutilizable por clear() y deleteCart()
    //Usa Collection en la firma para evitar este error:
    //Parameter $items has no type information available.
    private function restoreStockForItems(Collection $items): void
    {
        //items es la coleccion item el elemento
        foreach ($items as $item) {
            $product = Product::find($item->product_id);
            //si encuentra el producto agrega cantidad al stock
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }
    }

    public function addProduct(addProductsToCartDTO $request): CartItem
    {
        //transaction asegura que se ejecute todo o nada
        //
        return DB::transaction(function () use ($request) {
            //lockforUpdate: bloquea ese producto hasta que la transaction termine
            //evita que dos usuarios quieran comprar el mismo producto al mismo tiempo
            $product = Product::lockForUpdate()->findOrFail($request->product_id);

            //verifica que el stock sea suficiente
            if ($product->stock < $request->quantity) {
                throw new InsufficientStockException("stock insuficiente, el producto seleccionado solo cuenta con {$product->stock} unidades disponibles");
            }

            //busca el carrito que pertece al usuario, si no existe lo crea
            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
            //busca el producto en el carrito, si ya estaba, si no estaba lo agrega
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->first();

            //el producto ya esta en el carrito
            if ($cartItem) {
                $cartItem->quantity += $request->quantity; //suma la cantidad guardada mas la nueva
                $cartItem->sub_total = $cartItem->quantity * $product->price; //calucla el subtotal
                $cartItem->save(); //guarda los cambios
            } else { //si el producto no estaba en el carrito, lo agrega
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'sub_total' => $request->quantity * $product->price,
                ]);
            }

            $product->decrement('stock', $request->quantity); //actualiza stock
            //guarda en total el nuevo resultado
            $cart->update(['total' => $cart->items()->sum('sub_total')]); //suma los subtotales del carrito

            return $cartItem;
        });
    }

    public function clear(int $userId): Cart
    {
        $cart = Cart::with('items')->where('user_id', $userId)->first();
        if (!$cart) {
            throw new CartNotFoundException();
        }
        $this->restoreStockForItems($cart->items);

        $cart->items()->delete();
        $cart->update(['total' => 0.00]);

        return $cart;
    }

    //funcion para quitar un prodcuto del carrito
    public function removeProduct(int $userId, int $productId, int $quantity): Cart
    {
        $cart = Cart::where('user_id', $userId)->first();
        if (!$cart) {
            throw new CartNotFoundException();
        }
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if (!$cartItem) {
            throw new CartItemNotFoundException();
        }

        $product = Product::find($cartItem->product_id);

        if ($quantity >= $cartItem->quantity) {
            // Si piden quitar más o lo mismo que hay, devolvemos todo el stock y borramos el item
            if ($product) {
                $product->increment('stock', $cartItem->quantity);
            }
            $cartItem->delete();
        } else {
            // Si piden quitar menos, restamos esa cantidad del item y la devolvemos al stock del producto
            if ($product) {
                $product->increment('stock', $quantity);
            }
            $cartItem->quantity -= $quantity;
            $cartItem->sub_total = $cartItem->quantity * $cartItem->price; // recalcula el subtotal
            $cartItem->save();
        }

        // Actualiza el total general del carrito
        $cart->update(['total' => $cart->items()->sum('sub_total')]);

        return $cart;
    }

    //servicio par eliminar un carrito
    public function deleteCart(int $userId): void
    {
        //busca el carrito del usuario
        $cart = Cart::with('items')->where('user_id', $userId)->first();
        if (!$cart) {
            throw new CartNotFoundException();
        }
        //devuleve el stock de todos los item que tenia el carrito
        $this->restoreStockForItems($cart->items);
        //elimina los items del carrito
        $cart->items()->delete();
        //elimina el carrito
        $cart->delete();
    }
}
