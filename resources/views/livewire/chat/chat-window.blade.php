<div class="chat-window">
    @php
        $authUser = auth()->user();

        $initiator = $conversation->users->firstWhere('id', $conversation->initiator_id);

        $receiver = $conversation->users->firstWhere('id', '!=', $conversation->initiator_id);
    @endphp

    {{-- ================= CABECERA ================= --}}
    <div class="name-user-chat">
        <div class="img-user">
            <img src="{{ asset($receiver->avatar) }}" width="40">
            <div class="circulo-verde"></div>
        </div>
        {{ $receiver->name }}
    </div>

    {{-- ================= MENSAJES ================= --}}
    <div class="messages">
        @foreach ($messages as $message)
            <div class="message {{ $message->user_id === auth()->id() ? 'sent' : 'received' }}">
                <p>{{ $message->content }}</p>
            </div>
        @endforeach
    </div>

    {{-- ================= ACEPTAR / RECHAZAR (solo receptor) ================= --}}
    @if ($conversation->isPending() && auth()->id() === $receiver->id)
        <div class="chat-request-actions">
            <form method="POST" action="{{ route('chat-requests.accept', $conversation->chatRequest->id) }}">
                @csrf
                <button type="submit" class="btn-accept">
                    Aceptar
                </button>
            </form>

            <form method="POST" action="{{ route('chat-requests.reject', $conversation->chatRequest->id) }}">
                @csrf
                <button type="submit" class="btn-reject">
                    Rechazar
                </button>
            </form>
        </div>
    @endif

    {{-- ================= RECHAZADO ================= --}}
    @if ($conversation->isRejected())
        <div class="chat-blocked">
            Esta conversación ha sido rechazada.
        </div>
    @endif

    {{-- ================= ESPERANDO RESPUESTA (solo iniciador) ================= --}}
    @if ($conversation->isPending() && auth()->id() === $initiator->id)
        <div class="chat-pending">
            Esperando respuesta del usuario…
        </div>
    @endif

    {{-- ================= INPUT (activo o iniciador pendiente) ================= --}}
    @if ($conversation->isActive() || ($conversation->isPending() && auth()->id() === $initiator->id))
        <div class="chat-input">
            <input type="text" wire:model.defer="content" wire:keydown.enter="send"
                placeholder="Escribe un mensaje…">

            <button wire:click="send">
                <i class="bx bx-send send"></i>
            </button>
        </div>
    @endif
</div>
