<?php

namespace Tests\Unit;

use App\service\DiscountService;
use App\service\ProductPriceService;
use PHPUnit\Framework\TestCase;

class ProducPriceTest extends TestCase
{

    public function test_precio_final_con_tarjeta(): void
    {
        $discountService = new DiscountService();
        $productPriceService = new ProductPriceService($discountService);

        $result = $productPriceService->finalPrice(100, 'tarjeta');

        $this->assertEquals(121, $result);
    }


    public function test_precio_final_con_transferencia(): void
    {
        $discountService = new DiscountService();
        $productPriceService = new ProductPriceService($discountService);

        $result = $productPriceService->finalPrice(100, 'transferencia');

        $this->assertEquals(111, $result);
    }
}
