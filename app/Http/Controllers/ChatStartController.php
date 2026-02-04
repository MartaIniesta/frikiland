<?php

namespace App\Http\Controllers;

use App\Models\{User, ChatRequest, Conversation};
use App\Notifications\ChatRequestReceived;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChatStartController
{
    use AuthorizesRequests;

    public function __invoke(User $user)
    {
        $from = Auth::user();
        $to   = $user;

        abort_if($from->id === $to->id, 403);

        $this->authorize('create', [ChatRequest::class, $to]);

        $conversation = Conversation::create([
            'status' => 'pending',
            'initiator_id' => $from->id,
        ]);

        $conversation->users()->attach([
            $from->id,
            $to->id,
        ]);

        $chatRequest = ChatRequest::create([
            'from_user_id'    => $from->id,
            'to_user_id'      => $to->id,
            'conversation_id' => $conversation->id,
            'status'          => 'pending',
        ]);

        $to->notify(
            new ChatRequestReceived($chatRequest)
        );

        return redirect()->route('chat.show', $conversation);
    }
}
