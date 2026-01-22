<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ContentPrivacy extends Component
{
    public string $type = 'favorites';
    public string $visibility = 'public';

    public bool $open = false;
    public bool $saved = false;

    public function mount()
    {
        $privacy = Auth::user()
            ->contentPrivacies()
            ->where('type', $this->type)
            ->first();

        $this->visibility = $privacy->visibility ?? 'public';
    }

    public function toggle()
    {
        $this->open = ! $this->open;
        $this->saved = false;
    }

    public function save()
    {
        Auth::user()
            ->contentPrivacies()
            ->updateOrCreate(
                ['type' => $this->type],
                ['visibility' => $this->visibility]
            );

        $this->saved = true;
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.user.content-privacy');
    }
}
