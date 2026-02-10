<div>
    <x-header>
        <x-slot:search>
            <livewire:user-search-header />
        </x-slot:search>
    </x-header>

    <x-banner-categories class="banner-categories-chat">
        <a href="{{ route('chat.index') }}" class="cat active">
            <i class="bx bx-chat" style="font-size: 20px;"></i>
            CHATS
        </a>
    </x-banner-categories>

    <div class="chat-main">
        <div class="chat-complete">

            {{-- SIDEBAR --}}
            <livewire:chat.chat-sidebar />

            {{-- CHAT ACTIVO --}}
            <livewire:chat.chat-window :conversation="$conversation" :key="'chat-' . $conversation->id" />

        </div>
    </div>
</div>
