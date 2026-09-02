<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_usuario_agrega_producto_al_carrito(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "id" => 1,
            "stock" => 10,
            "category_id" => $category->id
        ]);

        $addProduc = [
            "product_id" => $product->id,
            "quantity" => 10
        ];

        $token = auth("api")->login($user);
        $response = $this->withToken($token)->postJson('/api/V1/cart/add', $addProduc);
        $response->assertOk();
    }
    public function test_usuario_no_registrado_agrega_producto_al_carrito(): void
    {

        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "id" => 1,
            "stock" => 10,
            "category_id" => $category->id
        ]);

        $addProduc = [
            "product_id" => $product->id,
            "quantity" => 10
        ];


        $response = $this->postJson('/api/V1/cart/add', $addProduc);
        $response->assertUnauthorized();
    }
    //en este test se verifica que el stcok se descuente cuando se agrega al carrito
    public function test_usuario_agrega_dos_veces_el_producto_al_carrito(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "id" => 1,
            "stock" => 10,
            "category_id" => $category->id
        ]);

        $token = auth("api")->login($user);

        // Primera compra: 2 unidades
        $response1 = $this->withToken($token)->postJson('/api/V1/cart/add', [
            "product_id" => $product->id,
            "quantity" => 9
        ]);
        $response1->assertOk();

        // Segunda compra: 3 unidades más
        $response2 = $this->withToken($token)->postJson('/api/V1/cart/add', [
            "product_id" => $product->id,
            "quantity" => 3
        ]);
        $response2->assertUnprocessable();
    }

    public function test_usuario_no_registrado_agrega_dos_veces_el_producto_al_carrito(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "id" => 1,
            "stock" => 10,
            "category_id" => $category->id
        ]);

        // Primera compra: 2 unidades
        $response1 = $this->postJson('/api/V1/cart/add', [
            "product_id" => $product->id,
            "quantity" => 9
        ]);
        $response1->assertUnauthorized();

        // Segunda compra: 3 unidades más
        $response2 = $this->postJson('/api/V1/cart/add', [
            "product_id" => $product->id,
            "quantity" => 3
        ]);
        $response2->assertUnauthorized();
    }


    public function test_un_usuario_puede_ver_su_carrito(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 1500,
            "stock" => 10
        ]);

        // Act
        $token = auth("api")->login($user);
        $this->withToken($token)->postJson('/api/V1/cart/add', [
            "product_id" => $product->id,
            "quantity" => 2
        ]);

        $response = $this->withToken($token)->getJson('/api/V1/cart/');

        // Assert
        $response->assertOk();
    }
    public function test_un_usuario_no_registrado_puede_ver_su_carrito(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 1500,
            "stock" => 10
        ]);

        // Act

        $this->postJson('/api/V1/cart/add', [
            "product_id" => $product->id,
            "quantity" => 2
        ]);

        $response = $this->getJson('/api/V1/cart/');

        // Assert
        $response->assertUnauthorized();
    }

    public function test_usuario_quita_producto_del_carrito(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "stock" => 10,
            "category_id" => $category->id,
            "price" => 1500
        ]);

        // Primero agregar el producto al carrito
        $token = auth("api")->login($user);

        $this->withToken($token)->postJson('/api/V1/cart/add', [
            "product_id" => $product->id,
            "quantity" => 5
        ]);

        // Act
        $removeProduct = [
            "product_id" => $product->id,
            "quantity" => 2
        ];

        $response = $this->withToken($token)->postJson('/api/V1/cart/remove', $removeProduct);

        // Assert
        $response->assertOk();

        // Verificar que la cantidad se redujo
        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertEquals(3, $cart->items->first()->quantity);
    }


    public function test_usuario_no_registrado_quita_producto_del_carrito(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "stock" => 10,
            "category_id" => $category->id,
            "price" => 1500
        ]);

        // Primero agregar el producto al carrito
        $this->postJson('/api/V1/cart/add', [
            "product_id" => $product->id,
            "quantity" => 5
        ]);

        // Act
        $removeProduct = [
            "product_id" => $product->id,
            "quantity" => 2
        ];

        $response = $this->postJson('/api/V1/cart/remove', $removeProduct);

        // Assert
        $response->assertUnauthorized();
    }

    public function test_usuario_realiza_checkout(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "stock" => 10,
            "category_id" => $category->id,
            "price" => 1500
        ]);

        // Primero agregar el producto al carrito
        $token = auth("api")->login($user);

        $this->withToken($token)->postJson('/api/V1/cart/add', [
            "product_id" => $product->id,
            "quantity" => 5
        ]);

        $shipping_address = ["shipping_address" => "direccion de envio test"];
        $response = $this->withToken($token)->postJson('/api/V1/checkout', $shipping_address);
        // $response->assertCreated();
        $response->assertCreated()
            ->assertJson([
                'message' => 'Compra confirmada con éxito.',
                'order' => [
                    'user_id' => $user->id,
                    'shipping_address' => 'direccion de envio test',
                ]
            ])
            ->assertJsonStructure([
                'message',
                'order' => [
                    'id',
                    'user_id',
                    'shipping_address',
                    'tax',
                    'shipping_cost',
                    'total',
                    'created_at',
                    'updated_at',
                    'items' => [
                        '*' => [
                            'id',
                            'order_id',
                            'product_id',
                            'quantity',
                            'price',
                            'sub_total',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]
            ]);
    }

    public function test_usuario_no_registrado_realiza_checkout(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "stock" => 10,
            "category_id" => $category->id,
            "price" => 1500
        ]);

        $this->postJson('/api/V1/cart/add', [
            "product_id" => $product->id,
            "quantity" => 5
        ]);

        $shipping_address = ["shipping_address" => "direccion de envio test"];
        $response = $this->postJson('/api/V1/checkout', $shipping_address);
        // $response->assertCreated();
        $response->assertUnauthorized();
    }
}
