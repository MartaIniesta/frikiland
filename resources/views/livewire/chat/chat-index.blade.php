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
            <livewire:chat.chat-sidebar />

            <livewire:chat.chat-available-friends />

            <livewire:chat.chat-pending-sidebar />
        </div>
    </div>
</div>
