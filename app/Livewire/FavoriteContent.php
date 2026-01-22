<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class FavoriteContent extends Component
{
    public Model $model;
    public bool $isFavorite = false;

    public function mount(Model $model)
    {
        $this->model = $model;

        if (Auth::check()) {
            $this->isFavorite = $model->favorites()
                ->where('user_id', Auth::id())
                ->exists();
        }
    }

    public function toggle()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $favorite = $this->model
            ->favorites()
            ->where('user_id', Auth::id())
            ->first();

        if ($favorite) {
            $favorite->delete();
            $this->isFavorite = false;
        } else {
            $this->model->favorites()->create([
                'user_id' => Auth::id(),
            ]);
            $this->isFavorite = true;
        }
    }

    public function render()
    {
        return view('livewire.favorite-content');
    }
}
