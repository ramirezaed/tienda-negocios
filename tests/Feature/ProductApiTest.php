<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;


    public function test_ver_lista_productos(): void
    {
        Category::factory()->create();
        Product::factory()->create();

        $response = $this->getJson('/api/V1/products');
        // $response->assertOk();
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'stock',
                        'disponible',
                        'actualizado',
                        'category_id',
                        'category name'
                    ]
                ]
            ]);
    }

    public function test_usuario_agrega_producto(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $product = [
            "name" => "producto_test",
            "description" => "mesa + 6 sillas",
            "price" => 15000,
            "stock" => 1,
            "category_id" => $category->id
        ];

        // Act
        $token = auth("api")->login($user);
        $response = $this->withToken($token)->postJson('/api/V1/products', $product);

        // Assert - Verificar estructura
        $response->assertCreated()
            ->assertJsonPath('name', 'producto_test')
            ->assertJsonPath('description', 'mesa + 6 sillas')
            ->assertJsonPath('price', 15000)
            ->assertJsonPath('stock', 1)
            ->assertJsonPath('category_id', $category->id)
            ->assertJsonPath('category name', $category->name)
            ->assertJsonPath('disponible', true);
    }

    public function test_usuario_no_registrado_agrega_producto(): void
    {
        // Arrange
        $category = Category::factory()->create();

        $product = [
            "name" => "producto_test",
            "description" => "mesa + 6 sillas",
            "price" => 15000,
            "stock" => 10,
            "category_id" => $category->id
        ];

        // Act

        $response = $this->postJson('/api/V1/products', $product);

        // Assert - Verificar estructura
        $response->assertUnauthorized();
    }
    public function test_usuario_agrega_producto_nombre_duplicado(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        Product::factory()->create([
            "name" => "producto_test"
        ]);

        $product = [
            "name" => "producto_test",
            "description" => "mesa + 6 sillas",
            "price" => 15000,
            "stock" => 10,
            "category_id" => $category->id
        ];

        // Act
        $token = auth("api")->login($user);
        $response = $this->withToken($token)->postJson('/api/V1/products', $product);

        // Assert - Verificar estructura
        $response->assertUnprocessable();
    }
    public function test_usuario_agrega_producto_con_datos_incompletos(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $product = [
            "name" => "producto_test",
            "description" => "mesa + 6 sillas",
            "stock" => 10,
            "category_id" => $category->id
        ];

        // Act
        $token = auth("api")->login($user);
        $response = $this->withToken($token)->postJson('/api/V1/products', $product);

        // Assert - Verificar estructura
        $response->assertUnprocessable();
    }
    public function test_usuario_agrega_producto_con_stock_cero(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $product = [
            "name" => "producto_test",
            "description" => "mesa + 6 sillas",
            "price" => 15000,
            "stock" => 0,
            "category_id" => $category->id
        ];

        // Act
        $token = auth("api")->login($user);
        $response = $this->withToken($token)->postJson('/api/V1/products', $product);

        // Assert - Verificar estructura
        $response->assertUnprocessable();
    }

    public function test_usuario_modifica_producto(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "name" => "producto_test",
            "category_id" => $category->id
        ]);

        $productUpdate = [
            "name" => "producto_modificado",
        ];

        // Act
        $token = auth("api")->login($user);
        $response = $this->withToken($token)->putJson("/api/V1/products/{$product->id}", $productUpdate);

        // Assert - Verificar estructura
        $response->assertOk()
            ->assertJsonPath('name', 'producto_modificado')
            ->assertJsonPath('description', $product->description)
            ->assertJsonPath('price', $product->price)
            ->assertJsonPath('stock', $product->stock)
            ->assertJsonPath('category_id', $category->id)
            ->assertJsonPath('category name', $category->name)
            ->assertJsonPath('disponible', true);
    }

    public function test_usuario_no_registrado_modifica_producto(): void
    {
        // Arrange
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "name" => "producto_test",
            "category_id" => $category->id
        ]);

        $productUpdate = [
            "name" => "producto_modificado",
        ];

        // Act
        $response = $this->putJson("/api/V1/products/{$product->id}", $productUpdate);

        // Assert - Verificar estructura
        $response->assertUnauthorized();
    }

    public function test_usuario_elimina_producto(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "category_id" => $category->id
        ]);

        // Act
        $token = auth("api")->login($user);
        $response = $this->withToken($token)->deleteJson("/api/V1/products/{$product->id}");

        // Assert
        $response->assertOk();
    }

    public function test_usuario_no_registrado_elimina_producto(): void
    {
        // Arrange

        $category = Category::factory()->create();
        $product = Product::factory()->create([
            "category_id" => $category->id
        ]);
        // Act
        $response = $this->deleteJson("/api/V1/products/{$product->id}");
        // Assert
        $response->assertUnauthorized();
    }

    public function test_usuario_elimina_producto_no_existente(): void
    {
        // Arrange
        $user = User::factory()->create();
        $id = 99999;

        // Act
        $token = auth("api")->login($user);
        $response = $this->withToken($token)->deleteJson("/api/V1/products/{$id}");

        // Assert
        $response->assertNotFound();
    }
}
