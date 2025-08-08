<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear y autenticar un usuario para las pruebas
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
    }

    public function test_can_list_products()
    {
        Product::factory(3)->create();

        $response = $this->getJson('/api/productos');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'nombre', 'precio', 'stock', 'descripcion', 'imagen_url']
                    ]
                ]);
    }

    public function test_can_create_product()
    {
        Storage::fake('public');

        $response = $this->postJson('/api/productos', [
            'nombre' => 'Test Product',
            'precio' => 99.99,
            'stock' => 10,
            'descripcion' => 'Test Description',
            'imagen' => UploadedFile::fake()->image('product.jpg')
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => ['id', 'nombre', 'precio', 'stock', 'descripcion', 'imagen_url']
                ]);

        $this->assertDatabaseHas('products', [
            'nombre' => 'Test Product',
            'precio' => 99.99,
            'stock' => 10,
        ]);
    }
}
