<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;

class ProductCarousel extends Component
{
    public function render()
    {
        $products = Product::where('active', true)
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.products.product-carousel', [
            'products' => $products
        ]);
    }
}
