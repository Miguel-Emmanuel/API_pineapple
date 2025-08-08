<?php
namespace Database\Factories;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->words(3, true),
            'precio' => fake()->randomFloat(2, 10, 1000),
            'stock' => fake()->numberBetween(0, 100),
            'descripcion' => fake()->paragraph(),
            'imagen' => 'imagenes/uniformes.png'
        ];
    }
}
