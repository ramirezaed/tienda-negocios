<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use Database\factories\CartItemFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cart>
 */

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total' => $this->faker->randomFloat(2, 0, 1000000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
