<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $name = $this->faker->words(3, true);

        return [
            'sku' => strtoupper(Str::random(8)),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 5, 500),
            'stock' => $this->faker->numberBetween(0, 100),
            'active' => $this->faker->boolean(90),
            // PASA EL ARRAY DIRECTO, SIN json_encode
            'images' => [
                'products/post.jpg',
                $this->faker->imageUrl(640, 480, 'product', true),
            ],
        ];
    }
}
