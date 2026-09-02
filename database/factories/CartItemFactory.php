<?php

namespace Database\factories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        // Crear un producto para obtener su precio
        $product = Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 10);
        $price = $product->price;
        $sub_total = $price * $quantity;

        return [
            'cart_id' => Cart::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price,
            'sub_total' => $sub_total,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
