<?php

namespace App\Livewire\products;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class Products extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $view = 'index'; // Valores: index, show, create, edit

    public $name, $sku, $description, $price = 0, $stock = 0, $active = true;
    public $images = []; // Para las nuevas imágenes del formulario


    protected $rules = [
        'name' => 'required|min:3',
        'sku' => 'nullable|unique:products,sku',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'images.*' => 'image|max:2048', // Validación de imágenes
    ];

    public $selected_product = null;

    public function showProduct($id)
    {
        $this->selected_product = Product::findOrFail($id);
        $this->view = 'show';
    }

    public function createProduct()
    {
        $this->view = 'create';
    }

    public function backToIndex()
    {
        $this->selected_product = null;
        $this->view = 'index';
    }

    public function addProduct()
    {
        $this->validate();

        // Guardar imágenes en disco y obtener las rutas
        $imagePaths = [];
        foreach ($this->images as $image) {
            $imagePaths[] = $image->store('products', 'public');
        }

        Product::create([
            'sku'         => $this->sku,
            'name'        => $this->name,
            'slug'        => Str::slug($this->name) . '-' . rand(100, 999),
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,
            'active'      => $this->active,
            'images'      => $imagePaths, // Guardamos el array directamente
        ]);

        $this->reset(['name', 'sku', 'description', 'price', 'stock', 'images']);
        session()->flash('message', 'Producto creado con éxito.');
    }

    public function render()
    {
        return view('livewire.products.products', [
            'products' => Product::latest()->paginate(8)
        ]);
    }
}