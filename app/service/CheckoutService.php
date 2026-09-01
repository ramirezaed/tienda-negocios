<?php

namespace App\service;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CheckoutService
{
    private const TAX = 0.21; //define el valor de el impuesto
    private const SHIPPING_COST = 5000.00; //define el valor fijo del envio

    //funcion para obtener carrito, valida que exista y que tenga items
    public function getValidCart(): Cart
    {
        $userId = auth()->id();
        $cart = Cart::with('items')->where('user_id', $userId)->first();
        //isEmpty -> metodo de laravel, verifica que la coleccion esta vacia
        //si no hay carrito, o si esta vacio
        if (!$cart || $cart->items->isEmpty()) {
            //exeption  formateada en bootstrap/app
            throw new BadRequestException(400, "El carrito está vacío.");
        }
        return $cart;
    }

    //funcion para calcular el total con envio e impuesto
    public function calculateSummary(float $subtotal): array
    {
        $tax = round($subtotal * self::TAX, 2); //calcula el valor del impuesto 
        $shippingCost =  self::SHIPPING_COST; //asigna el valor al costo de envio
        $total = $subtotal + $tax + $shippingCost; //calcula el total 

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_cost' => $shippingCost,
            'total' => $total,
        ];
    }

    //Calcula el totoal, llamando a funcion privada que calcula el con envio e imuesto
    public function getSummary(): array
    {
        $cart = $this->getValidCart();
        return $this->calculateSummary($cart->total);
    }

    // confirmacion de compra
    public function processCheckout(string $address): Order
    {
        //DB::transaction -> se ejecuta todo o nada
        return DB::transaction(function () use ($address) {
            $userId = auth()->id();
            $cart = $this->getValidCart();
            $summary = $this->calculateSummary($cart->total);

            $order = Order::create([
                'user_id' => $userId,
                'shipping_address' => $address,
                //...sumary mete todos los elementos de un array dentro de otro
                ...$summary,
            ]);

            //recorre el carrito, por cada item, agrega en orderItem
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'sub_total' => $item->sub_total,
                ]);
            }

            //elimina el carrtio , sin devolver stock
            $cart->items()->delete();
            $cart->update(['total' => 0.00]);

            return $order;
        });
    }
}
