<?php

namespace App\service;

class DiscountService
{
    public function calculate(float $amount, string $paymentMethod): float
    {
        // solo transferencia tiene 10% de descuento
        if ($paymentMethod === 'transferencia') {
            return $amount * 0.10;
        }
        // Tarjeta u otros metodos no 
        return 0;
    }
}
