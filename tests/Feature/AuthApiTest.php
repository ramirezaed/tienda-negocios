<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_un_cliente_se_registra(): void
    {
        //arrange
        $user = [
            "name" => " name test",
            "email" => "email@test.com",
            "password" => "password",
            "password_confirmation" => "password",
            "role" => "cliente",
        ];
        //act realizar la peticion
        $response = $this->postJson('/api/V1/register', $user);
        //assert 
        $response->assertOk()->assertJsonStructure([
            "access_token",
            "token_type",
            "expires_in",
            "user" => ["id", "name", "email", "role"]
        ]);
    }

    public function test_cliente_se_registra_con_datos_incompletos(): void
    {
        //arrange
        $user = [
            "name" => " name test",
            "password" => "password",
            "password_confirmation" => "password",
            "role" => "cliente",
        ];
        //act realizar la peticion
        $response = $this->postJson('/api/V1/register', $user);
        //assert 
        $response->assertUnprocessable();
    }

    public function test_cliente_se_registra_con_password_diferentes(): void
    {
        //arrange
        $user = [
            "name" => " name test",
            "email" => "name test",
            "password" => "password",
            "password_confirmation" => "password1",
            "role" => "cliente",
        ];
        //act realizar la peticion
        $response = $this->postJson('/api/V1/register', $user);
        //assert 
        $response->assertUnprocessable();
    }

    public function test_cliente_se_registra_con_email_duplicado(): void
    {
        //arrange
        User::factory()->create([
            "email" => "email@test.com",
            "password" => "password",
        ]);
        $user = [
            "name" => " name test",
            "email" => "email@test.com",
            "password" => "password",
            "password_confirmation" => "password",
            "role" => "cliente",
        ];
        //act realizar la peticion
        $response = $this->postJson('/api/V1/register', $user);
        //assert 
        $response->assertUnprocessable();
    }

    public function test_cliente_inicia_sesion(): void
    {
        //arrange
        User::factory()->create([
            "email" => "email@test.com",
            "password" => "password",
        ]);

        //act
        $response = $this->postJson('/api/V1/login', [
            "email" => "email@test.com",
            "password" => "password",
        ]);

        //assert
        $response->assertOk()->assertJsonStructure([
            "access_token",
            "token_type",
            "expires_in",
            "user"
        ])
            ->assertJsonPath("user.email", "email@test.com")
            ->assertJsonMissingPath("user.password");
    }

    public function test_cliente_no_puede_iniciar_session(): void
    {
        //arrange
        User::factory()->create([
            "email" => "email@test.com",
            "password" => "password",
        ]);

        //act
        $response = $this->postJson('/api/V1/login', [
            "email" => "1email@test.com",
            "password" => "password",
        ]);

        //assert
        $response->assertUnauthorized();
    }

    public function test_cliente_ingresa_a_su_perfil(): void
    {
        $user = User::factory()->create();

        $token = auth("api")->login($user);
        $this->withToken($token)->getJson("/api/V1/profile")->assertOk()
            ->assertJsonMissingPath("user.password")
            ->assertJsonPath("id", $user->id)
            ->assertJsonPath("name", $user->name)
            ->assertJsonPath("email", $user->email)
            ->assertJsonPath("role", $user->role);
    }

    public function test_cliente_no_puede_acceder_a_su_perfil_sin_autenticacion(): void
    {
        $response = $this->getJson("/api/V1/profile");
        $response->assertUnauthorized();
    }
}
