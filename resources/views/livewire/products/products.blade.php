<main class="wrap-main">
    <section class="main-content">
        
        {{-- ==========================================================
             FORMULARIO DE CREACIÓN (Solo para usuarios autenticados)
             ========================================================== --}}
        @auth
            <article class="create-post-box" x-data="{ dragging: false }" 
                x-on:dragover.prevent="dragging = true"
                x-on:dragleave.prevent="dragging = false"
                x-on:drop.prevent="dragging = false; $wire.uploadMultiple($event.dataTransfer.files, 'images')"
                :class="{ 'dragging': dragging }">
                
                <div class="create-post-top" style="flex-direction: column; gap: 15px;">
                    <h2 style="font-weight: bold; font-size: 1.2rem; color: #333;">Nuevo Producto</h2>
                    
                    {{-- Nombre del Producto --}}
                    <input type="text" wire:model="name" class="create-textarea" 
                        placeholder="Nombre del producto..." style="height: 45px; border: 1px solid #e1e1e1; padding: 0 15px;">
                    
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        {{-- SKU --}}
                        <input type="text" wire:model="sku" placeholder="SKU (Opcional)" 
                            style="flex: 1; min-width: 150px; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                        
                        {{-- Precio --}}
                        <div style="position: relative; display: flex; align-items: center;">
                            <span style="position: absolute; left: 10px; color: #888;">$</span>
                            <input type="number" step="0.01" wire:model="price" placeholder="Precio" 
                                style="width: 110px; padding: 10px 10px 10px 25px; border-radius: 8px; border: 1px solid #ddd;">
                        </div>

                        {{-- Stock --}}
                        <input type="number" wire:model="stock" placeholder="Stock" 
                            style="width: 90px; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>

                    {{-- Descripción --}}
                    <textarea wire:model="description" class="create-textarea" 
                        placeholder="Describe las características del producto..." style="min-height: 80px;"></textarea>
                </div>

                <div class="create-actions">
                    <div class="left-actions">
                        {{-- Input de Imágenes --}}
                        <label for="imageInput" style="cursor: pointer; display: flex; align-items: center; gap: 5px; color: #555;">
                            <i class="bx bx-image-add" style="font-size: 1.6rem;"></i>
                            <span style="font-size: 0.9rem;">Añadir fotos</span>
                        </label>
                        <input type="file" id="imageInput" wire:model="images" multiple hidden>
                        
                        {{-- Switch de Activo --}}
                        <label style="margin-left: 20px; display: flex; align-items: center; gap: 8px; font-size: 0.9rem; cursor: pointer;">
                            <input type="checkbox" wire:model="active" style="width: 16px; height: 16px;"> 
                            Activo
                        </label>
                    </div>

                    <button wire:click="addProduct" class="btn-post" wire:loading.attr="disabled">
                        <span wire:loading.remove>Guardar Producto</span>
                        <span wire:loading>Subiendo...</span>
                    </button>
                </div>

                {{-- PREVIEW DE IMÁGENES ANTES DE SUBIR --}}
                @if ($images && count($images) > 0)
                    <div style="display: flex; gap: 8px; margin-top: 15px; flex-wrap: wrap; background: #f8f9fa; padding: 10px; border-radius: 8px;">
                        @foreach($images as $index => $img)
                            <div style="position: relative;">
                                <img src="{{ $img->temporaryUrl() }}" 
                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 2px solid #fff; shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>
        @endauth

        {{-- ==========================================================
             LISTADO DE PRODUCTOS (Usa la variable $product)
             ========================================================== --}}
        <h3 style="margin: 30px 0 15px 0; font-weight: bold; color: #444;">Catálogo de Productos</h3>

        @forelse ($products as $product)
            <article class="posts" style="margin-bottom: 20px; border: 1px solid #f0f0f0;">
                <div class="wrap-profile">
                    {{-- Icono representativo --}}
                    <div class="img-profile" style="background: #eef2f7; display: flex; align-items: center; justify-content: center; color: #5dade2;">
                        <i class="bx bx-package" style="font-size: 1.5rem;"></i>
                    </div>

                    <div class="profile-name">
                        <p style="font-weight: 600; font-size: 1.1rem; margin: 0;">{{ $product->name }}</p>
                        <span style="font-size: 0.85rem; color: #888;">SKU: {{ $product->sku ?? 'Sin código' }}</span>
                    </div>

                    <div class="right-content">
                        <span style="font-size: 1.2rem; font-weight: bold; color: #27ae60;">
                            ${{ number_format($product->price, 2, ',', '.') }}
                        </span>
                    </div>
                </div>

                <p class="text-main-content" style="margin: 15px 0; color: #555; line-height: 1.5;">
                    {{ $product->description }}
                </p>

                {{-- GALERÍA DE IMÁGENES GUARDADAS --}}
                @if($product->images && is_array($product->images))
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; margin: 15px 0;">
                        @foreach($product->images as $path)
                            @php
                                // Detectar si es URL externa (faker) o local (storage)
                                $src = str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
                            @endphp
                            <img src="{{ $src }}" 
                                 alt="Imagen de {{ $product->name }}" 
                                 style="width: 100%; height: 120px; object-fit: cover; border-radius: 10px; border: 1px solid #f1f1f1;">
                        @endforeach
                    </div>
                @endif

                {{-- BARRA DE INFORMACIÓN Y ACCIONES --}}
                <div class="content-icons" style="border-top: 1px solid #f5f5f5; padding-top: 12px;">
                    <div class="content-icons-left" style="display: flex; gap: 20px; color: #666; font-size: 0.9rem;">
                        <span title="Stock disponible">
                            <i class="bx bx-store-alt"></i> Stock: <strong>{{ $product->stock }}</strong>
                        </span>

                        <span style="color: {{ $product->active ? '#27ae60' : '#e74c3c' }}; font-weight: 500;">
                            <i class="bx bx-circle"></i> {{ $product->active ? 'Publicado' : 'Borrador' }}
                        </span>
                    </div>

                    <div class="content-icons-right">
                        <button style="background: none; border: none; padding: 5px; cursor: pointer; color: #3498db;" title="Editar">
                            <i class="bx bx-edit-alt" style="font-size: 1.3rem;"></i>
                        </button>
                        <button style="background: none; border: none; padding: 5px; cursor: pointer; color: #e74c3c;" title="Eliminar">
                            <i class="bx bx-trash" style="font-size: 1.3rem;"></i>
                        </button>
                    </div>
                </div>
            </article>
        @empty
            <div style="text-align: center; padding: 50px; color: #999; background: #f9f9f9; border-radius: 15px;">
                <i class="bx bx-search-alt" style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
                <p>No hay productos registrados en el catálogo.</p>
            </div>
        @endforelse

    </section>
</main>