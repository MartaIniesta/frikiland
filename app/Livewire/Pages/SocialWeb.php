<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class SocialWeb extends Component
{
    public function mount($feed = 'for_you')
    {
        request()->merge(['feed' => $feed]);
    }

    public function render()
    {
        return view('livewire.pages.social-web');
    }
}
