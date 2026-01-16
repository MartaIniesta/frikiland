<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'active',
        'images', // Aquí se guardará el array de rutas
    ];

    protected $casts = [
        'images' => 'array',   // Crucial para evitar el error del foreach
        'active' => 'boolean',
        'price'  => 'decimal:2'
    ];

    // Relaciones (mantengo las que ya tenías)
    public function categories() {
        return $this->belongsToMany(ProductCategory::class, 'category_product');
    }

    public function carts() {
        return $this->belongsToMany(Cart::class, 'cart_items')->withPivot('quantity', 'price_at_purchase')->withTimestamps();
    }
}