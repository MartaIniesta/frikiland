<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserSearchHeader extends Component
{
    public $search = '';

    public function render()
    {
        $users = [];

        if (strlen($this->search) >= 2) {
            $users = User::where('username', 'like', '%' . $this->search . '%')
                ->limit(5)
                ->get();
        }

        return view('livewire.user-search-header', compact('users'));
    }
}
