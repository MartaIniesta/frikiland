<?php

namespace App\Livewire\products;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\products\Cart;

class Products extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';
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

    #[On('search-products')]
    public function updateSearch($search)
    {
        $this->search = $search;
        $this->resetPage();
    }

    #[On('filter-my-products')]
    public function my_products()
    {
        $this->resetPage(); // Importante: volver a la página 1 al filtrar
        $this->view = 'my-products';
    }

    public function mount()
    {
        $tab = request('tab');
        $this->search = '';
        $productId = request('product');

        if ($productId) {
            $this->showProduct($productId);
            return;
        }

        if ($tab === 'mine') {
            $this->view = 'my-products';
        } elseif ($tab === 'cart') {
            $this->view = 'cart';
        } else {
            $this->view = 'index';
        }
    }

    public function removeOldImage($index)
    {
        // Obtenemos las imágenes actuales en un array
        $images = $this->selected_product->images;

        // Eliminamos la del índice seleccionado
        unset($images[$index]);

        // Reindexamos el array y lo guardamos de nuevo en el modelo temporalmente
        $this->selected_product->images = array_values($images);
    }

    public function editProduct($id)
    {
        $this->resetPage();
        $this->selected_product = Product::findOrFail($id);

        // En lugar de fill(), asignamos manualmente
        // y dejamos 'images' como un array vacío para las NUEVAS fotos
        $this->name = $this->selected_product->name;
        $this->sku = $this->selected_product->sku;
        $this->description = $this->selected_product->description;
        $this->price = $this->selected_product->price;
        $this->stock = $this->selected_product->stock;
        $this->active = $this->selected_product->active;

        $this->images = []; // <--- ESTO ES VITAL: Limpia las fotos temporales

        $this->view = 'edit';
    }

    public function updateProduct()
    {
        $this->validate([
            'name' => 'required|min:3',
            'sku' => 'nullable|unique:products,sku,' . $this->selected_product->id,
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($this->selected_product->id);

        // Mantener imágenes actuales y añadir las nuevas
        $imagePaths = $product->images ?? [];
        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
        }

        $product->update([
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'active' => $this->active,
            'images' => $imagePaths,
        ]);

        session()->flash('message', '¡Producto actualizado correctamente!');
        $this->backToIndex();
    }

    public function showProduct($id)
    {
        $this->resetPage();
        // Forzamos la limpieza antes de cargar el nuevo
        $this->selected_product = null;

        // Cargamos el nuevo producto
        $this->selected_product = Product::findOrFail($id);

        // Cambiamos la vista
        $this->view = 'show';

        // Opcional: Si usas paginación en la misma página, esto ayuda a resetear el estado
        $this->dispatch('scroll-to-top');
    }

    public function createProduct()
    {
        $this->resetPage();
        $this->view = 'create';
    }

    public function deleteProduct($id)
    {
        $product = Product::where('user_id', auth()->id())->findOrFail($id);

        // Opcional: Eliminar imágenes del storage antes de borrar el producto
        if ($product->images) {
            foreach ($product->images as $path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
        }

        $product->delete();
        session()->flash('message', 'Producto eliminado correctamente.');
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
            'user_id' => auth()->id(),
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


    #[On('addToCart')]
    public function addToCart($productId)
    {
        if (!auth()->check()) {
            $this->dispatch('notify', 'Inicia sesión para comprar');
            return;
        }

        $product = Product::findOrFail($productId);

        // Obtenemos o creamos el carrito del usuario
        $cart = \App\Models\Cart::firstOrCreate(['user_id' => auth()->id()]);

        // Verificamos si ya existe para manejar la cantidad
        $existing = $cart->products()->where('product_id', $productId)->first();

        if ($existing) {
            if ($existing->pivot->quantity < $product->stock) {
                $cart->products()->updateExistingPivot($productId, [
                    'quantity' => $existing->pivot->quantity + 1
                ]);
            }
        } else {
            // syncWithoutDetaching añade el registro a cart_items sin borrar los otros
            $cart->products()->attach($productId, [
                'quantity' => 1,
                'price_at_purchase' => $product->price
            ]);
        }

        $this->dispatch('notify', '¡Añadido a tu carrito!');
    }

    #[On('cart')]
    public function showCartView()
    {
        $this->view = 'cart';
    }

    public function render()
    {
        $query = Product::latest();

        if ($this->view === 'my-products') {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('active', true);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('categories', function ($cat) {
                        $cat->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        return view('livewire.products.products', [
            'products' => $query->paginate(8)
        ]);
    }
}
