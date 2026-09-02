<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\DTO\cart\addProductsToCartDTO;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\service\CartService;
use stdClass;
use App\service\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class ResumenCarritoTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;

    private function DatosSimulados(): array
    {
        Auth::shouldReceive('id')->andReturn(1);

        User::create([
            'id' => 1,
            'name' => 'Usuario de prueba',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'client',
        ]);

        Category::create([
            'id' => 1,
            'name' => 'Categoría de prueba'
        ]);

        // Creamos el primer producto (ID 99)
        $producto1 = Product::create([
            'id' => 99,
            'name' => 'Producto 1',
            'description' => 'descripcion 1',
            'price' => 1500.00,
            'stock' => 10,
            'category_id' => 1
        ]);

        // Creamos el segundo producto (ID 100)
        $producto2 = Product::create([
            'id' => 100,
            'name' => 'Producto 2',
            'description' => 'descripcion 2',
            'price' => 2500.00,
            'stock' => 5,
            'category_id' => 1
        ]);

        // Devolvemos ambos productos dentro de un array indexado
        return [$producto1, $producto2];
    }

    //esta funciona se utiliza para simuar un item
    private function item(float $price, int $quantity): stdClass
    {
        //crea el objeto producto en la clase standar
        $product = new stdClass();
        //simula el precio del producto
        $product->price = $price;

        //crea un item 
        $item = new stdClass();
        $item->product = $product;
        $item->quantity = $quantity;
        return $item;
    }

    public function test_calcular_carrito_con_envio(): void
    {
        //arrange: se crea el carrito cn un item que vale 20000
        $item = $this->item(20000, 1);

        //act: se calcula el resumen del carrito
        $subtotal = $item->product->price * $item->quantity;
        //el servicio calcuateSumary ya recibe el subtotal como parametro, 
        //sobre ese subtotal hace los calculos de impuesto y agrega envio
        $summary = (new CheckoutService())->calculateSummary($subtotal);

        //assert: verifica que el resumen calculado sea correcto
        $this->assertEquals([
            "subtotal" => 20000,
            "tax" => 4200,
            "shipping_cost" => 5000,
            "total" => 29200,
        ], $summary);
    }

    public function test_agregar_producto_con_cantidad_igual_al_stock(): void
    {
        //arrange
        [$product1] = $this->DatosSimulados();
        $dto = new addProductsToCartDTO($product1->id, 10);

        // act
        $cartItem = (new CartService())->addProduct($dto);

        // assert
        $this->assertEquals(10, $cartItem->quantity);
        $this->assertEquals(15000.00, $cartItem->sub_total);
        $this->assertEquals(0, $product1->fresh()->stock);

        $cart = Cart::where('user_id', 1)->first();
        $this->assertEquals(15000.00, $cart->total);
    }

    public function test_agregar_producto_con_cantidad_mayor_al_stock(): void
    {
        //arrange
        [$product1] = $this->DatosSimulados();

        //act
        $dto2 = new addProductsToCartDTO($product1->id, 11);

        $this->expectException(\App\Exceptions\InsufficientStockException::class);

        (new CartService())->addProduct($dto2);
    }

    public function test_quitar_producto_del_carrito(): void
    {
        // arrange
        [$product1] = $this->DatosSimulados();

        $cartService = new CartService();

        // agrega 5 
        $cartService->addProduct(new addProductsToCartDTO($product1->id, 5));

        // DTO para quitar las 5 unidades 
        $dto = new \App\DTO\cart\removeProductsToCartDTO($product1->id, 5);

        // act: Ejecutar el servicio
        $cart = $cartService->removeProduct($dto);

        //  assert
        $this->assertEmpty($cart->items); //verifica que queda vcacio
        $this->assertEquals(10, $product1->fresh()->stock); //el stock del producto vuelve a ser 10
        $this->assertEquals(0, $cart->total); //total del carrito es 0
    }
    public function test_eliminar_carrito_vacia_los_items_y_restaura_el_stock(): void
    {
        // arrange
        [$product1, $product2] = $this->DatosSimulados();
        $cartService = new CartService();

        //agrega productos al carrito
        $cartService->addProduct(new addProductsToCartDTO($product1->id, 4));  // Stock 10 -> 6
        $cartService->addProduct(new addProductsToCartDTO($product2->id, 2));  // Stock 5 -> 3

        // act: ejecuta el metodo para eliminar  el carrito
        $cartService->deleteCart();

        // Assert
        // El carrito del usuario con ID 1 no debe existir
        $this->assertNull(Cart::where('user_id', 1)->first());

        // el stock de ambos productos vulve a su stock original
        $this->assertEquals(10, $product1->fresh()->stock);
        $this->assertEquals(5, $product2->fresh()->stock);
    }
    public function test_limpiar_carrito_restaurar_stock_items(): void
    {
        // arrange: 
        [$product1, $product2] = $this->DatosSimulados();
        $cartService = new CartService();

        // agrega productos al carritos
        $cartService->addProduct(new addProductsToCartDTO($product1->id, 4));  // stock 10 -> 6
        $cartService->addProduct(new addProductsToCartDTO($product2->id, 2));  // stock 5 -> 3

        // Act : ejecuta la funcion para limpiar el carrito
        $cart = $cartService->clear();

        // assert
        // el carrito existe pero esta vacio
        $this->assertEmpty($cart->refresh()->items);
        // el total  del carrito debe ser 0
        $this->assertEquals(0.00, $cart->total);
        // el stock de ambos productos debe regresar  originales
        $this->assertEquals(10, $product1->fresh()->stock);
        $this->assertEquals(5, $product2->fresh()->stock);
    }
}
