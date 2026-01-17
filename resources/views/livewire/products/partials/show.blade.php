        <div class="max-w-6xl mx-auto bg-gray-100 p-6 rounded-2xl shadow-sm border border-gray-200 animate-fade-in">

    {{-- NIVEL 1: IMAGEN (IZQUIERDA) E INFO (DERECHA) --}}
    <div class="flex flex-col md:flex-row gap-10 items-start">
        
        {{-- CARRUSEL DE IMAGEN MÁS PEQUEÑA --}}
        <div class="w-full md:w-[380px] shrink-0" x-data="{ 
                activeImage: 0, 
                images: {{ json_encode(array_map(fn($img) => str_starts_with($img, 'http') ? $img : asset('storage/'.$img), $selected_product->images)) }} 
             }">
            <div class="relative aspect-square bg-gray-50 rounded-xl border border-gray-200 overflow-hidden group">
                <template x-for="(img, index) in images" :key="index">
                    <img x-show="activeImage === index" :src="img" 
                         class="absolute inset-0 w-full h-full object-contain p-4 transition-opacity duration-300">
                </template>
                
                {{-- Flechas --}}
                <div class="absolute inset-0 flex items-center justify-between px-2 opacity-0 group-hover:opacity-100 transition">
                    <button @click="activeImage = (activeImage - 1 + images.length) % images.length" class="bg-white/90 p-1 rounded-full shadow-sm"><i class="bx bx-chevron-left text-xl"></i></button>
                    <button @click="activeImage = (activeImage + 1) % images.length" class="bg-white/90 p-1 rounded-full shadow-sm"><i class="bx bx-chevron-right text-xl"></i></button>
                </div>
            </div>
            {{-- Dots --}}
            <div class="flex justify-center gap-1.5 mt-3">
                <template x-for="(img, index) in images" :key="index">
                    <button @click="activeImage = index" :class="activeImage === index ? 'bg-blue-900 w-4' : 'bg-gray-300 w-1.5'" class="h-1.5 rounded-full transition-all"></button>
                </template>
            </div>
        </div>

        {{-- INFORMACIÓN A LA DERECHA --}}
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-gray-900 uppercase">{{ $selected_product->name }}</h2>
            <p class="text-sm text-gray-400 mb-4">SKU: {{ $selected_product->sku }}</p>
            
            <div class="flex items-center gap-2 mb-4 text-yellow-400">
                <i class="bx bxs-star"></i><i class="bx bxs-star"></i><i class="bx bxs-star"></i><i class="bx bxs-star"></i><i class="bx bx-star text-gray-300"></i>
                <span class="text-gray-500 text-sm">(12 opiniones)</span>
            </div>

            <p class="text-3xl font-bold text-blue-900 mb-6">${{ number_format($selected_product->price, 2) }}</p>

            <div class="bg-gray-50 p-4 rounded-xl mb-6">
                <h4 class="font-bold text-gray-700 text-sm mb-2">Sobre este producto</h4>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $selected_product->description }}</p>
            </div>

            <button class="w-full md:w-auto px-8 py-3 bg-blue-900 text-white rounded-lg font-bold hover:bg-blue-800 transition">
                Añadir al carrito
            </button>
        </div>
    </div>

    {{-- NIVEL 2: OPINIONES COLAPSABLES --}}
    <div x-data="{ open: false }" class="mt-12 border-t border-gray-100 pt-6">
        <button @click="open = !open" class="flex items-center justify-between w-full py-2 group">
            <span class="text-lg font-bold text-gray-800">Opiniones del producto</span>
            <i class="bx text-2xl transition-transform" :class="open ? 'bx-chevron-up rotate-180' : 'bx-chevron-down'"></i>
        </button>
        
        <div x-show="open" x-collapse x-cloak class="mt-4 grid gap-4">
            {{-- Ejemplo de opinión --}}
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div class="flex justify-between mb-2">
                    <span class="font-bold text-sm">Usuario Demo</span>
                    <span class="text-xs text-gray-400">Hace 2 días</span>
                </div>
                <p class="text-sm text-gray-600 italic">"¡Excelente calidad! Superó mis expectativas."</p>
            </div>
        </div>
    </div>

    {{-- NIVEL 3: CARRUSEL HORIZONTAL DE PRODUCTOS --}}
    <div class="mt-12 border-t border-gray-100 pt-8" x-data="{ 
            scroll: 0,
            scrollNext() { this.$refs.container.scrollBy({ left: 300, behavior: 'smooth' }) },
            scrollPrev() { this.$refs.container.scrollBy({ left: -300, behavior: 'smooth' }) }
         }">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">También te puede interesar</h3>
            <div class="flex gap-2">
                <button @click="scrollPrev" class="p-2 border border-gray-200 rounded-full hover:bg-gray-50"><i class="bx bx-chevron-left"></i></button>
                <button @click="scrollNext" class="p-2 border border-gray-200 rounded-full hover:bg-gray-50"><i class="bx bx-chevron-right"></i></button>
            </div>
        </div>

        <div x-ref="container" class="flex gap-4 overflow-x-auto scrollbar-hide snap-x snap-mandatory pb-4">
            @foreach($products as $related)
                <div class="min-w-[200px] max-w-[200px] snap-start group cursor-pointer" wire:click="showProduct({{ $related->id }})">
                    <div class="aspect-square bg-gray-50 rounded-lg overflow-hidden border border-gray-100 mb-2">
                        <img src="{{ str_starts_with($related->images[0] ?? '', 'http') ? $related->images[0] : asset('storage/'.$related->images[0]) }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition">
                    </div>
                    <p class="text-sm font-bold truncate">{{ $related->name }}</p>
                    <p class="text-sm text-blue-900">${{ number_format($related->price, 2) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>