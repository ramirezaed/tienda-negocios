<?php

namespace App\service;


class ProductPriceService
{
    public function __construct(
        private DiscountService $discount
    ) {}

    public function finalPrice(float $price, string $paymentMethod = 'tarjeta'): float
    {
        $discount = $this->discount->calculate($price, $paymentMethod);
        $tax = $price * 0.21;

        return $price + $tax - $discount;
    }
}
