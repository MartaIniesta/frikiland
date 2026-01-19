<div
    x-data="{ loading: false }"
    x-init="
        window.addEventListener('scroll', () => {
            if (loading || !@entangle('hasMoreReplies')) return;

            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200) {
                loading = true;
                $wire.loadReplies().then(() => loading = false);
            }
        })
    "
>
    <x-header>
        <x-slot:menu>
            <div class="menu">
                <a href="{{ route('social-web') }}">
                    <i class="bx bx-left-arrow-alt return"></i>
                </a>
            </div>
        </x-slot:menu>
    </x-header>

    <main class="wrap-main">
        <section class="main-content">

            {{-- POST ORIGINAL --}}
            @include('livewire.posts.comment', [
                'comment' => $post,
                'level' => 0,
                'isRoot' => true,
            ])

            {{-- REPLIES --}}
            @foreach ($replies as $reply)
                @include('livewire.posts.comment', [
                    'comment' => $reply,
                    'level' => 1,
                    'isRoot' => false,
                ])
            @endforeach

            {{-- LOADER --}}
            @if ($hasMoreReplies)
                <div class="text-center py-4 text-gray-500">
                    Cargando más respuestas…
                </div>
            @else
                <div class="text-center py-4 text-gray-400">
                    No hay más respuestas
                </div>
            @endif

        </section>
    </main>
</div>
