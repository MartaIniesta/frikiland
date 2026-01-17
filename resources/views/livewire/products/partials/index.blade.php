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
  {{-- CONTENEDOR EN GRID --}}
  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px;">
      @foreach ($products as $product)
          <article style="background: white; border-radius: 12px; border: 1px solid #eee; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.05);" 
                    onmouseover="this.style.transform='scale(1.02)'" 
                    onmouseout="this.style.transform='scale(1)'">
              
              {{-- Imagen Principal --}}
              {{-- Dentro de tu @foreach ($products as $product) --}}
              <div style="width: 100%; height: 150px; background: #f8f9fa; position: relative; overflow: hidden;">
                  @php
                      // Obtenemos la primera ruta del array. Si no existe, queda como null.
                      $firstImagePath = $product->images[0] ?? null;

                      // Construimos la URL: 
                      // 1. Si no hay imagen, usamos un placeholder.
                      // 2. Si la ruta empieza con http, es de Faker.
                      // 3. Si no, es una ruta local de storage.
                      if (!$firstImagePath) {
                          $src = 'https://via.placeholder.com/300x300?text=Sin+Imagen';
                      } else {
                          $src = str_starts_with($firstImagePath, 'http') 
                              ? $firstImagePath 
                              : asset('storage/' . $firstImagePath);
                      }
                  @endphp

                  <img src="{{ $src }}" 
                      alt="{{ $product->name }}" 
                      style="width: 100%; height: 100%; object-fit: cover;">
                  
                  {{-- Etiqueta de precio sobre la imagen --}}
                  <span style="position: absolute; top: 8px; right: 8px; background: rgba(255,255,255,0.9); padding: 2px 8px; border-radius: 20px; font-weight: bold; font-size: 0.85rem; color: #27ae60; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                      ${{ number_format($product->price, 2, ',', '.') }}
                  </span>
              </div>

              {{-- Info resumida --}}
              <div style="padding: 12px; display: flex; flex-direction: column; gap: 5px; flex-grow: 1;">
                  <h4 style="margin: 0; font-size: 0.95rem; color: #333; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                      {{ $product->name }}
                  </h4>
              </div>

              {{-- Botón para entrar --}}
              <button wire:click="showProduct({{ $product->id }})" style="display: block; width: 100%; padding: 10px; text-align: center; background: #f0f2f5; color: #3498db; text-decoration: none; font-size: 0.85rem; font-weight: bold; border-top: 1px solid #eee;">
                  Ver detalles
              </button>
          </article>
      @endforeach
  </div>

  {{-- LINKS DE PAGINACIÓN --}}
  <div style="margin-top: 30px;">
      {{ $products->links() }}
  </div>

  @if($products->isEmpty())
      <div style="text-align: center; padding: 50px; color: #999;">
          <p>No hay productos disponibles.</p>
      </div>
  @endif

</section>