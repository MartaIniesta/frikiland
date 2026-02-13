<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $confirmingDelete = null;
    public $roles = [];

    protected $updatesQueryString = ['search'];

    public function mount()
    {
        $this->roles = Role::pluck('name')->toArray();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateRole($userId, $role)
    {
        $user = User::findOrFail($userId);

        if ($user->hasRole('admin') && $role !== 'admin') {
            $adminCount = User::role('admin')->count();

            if ($adminCount <= 1) {
                return;
            }
        }

        $user->syncRoles([$role]);
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->confirmingDelete);

        if ($user->id === Auth::id()) {
            $this->confirmingDelete = null;
            return;
        }

        if ($user->hasRole('admin')) {
            $adminCount = User::role('admin')->count();

            if ($adminCount <= 1) {
                $this->confirmingDelete = null;
                return;
            }
        }

        $user->delete();
        $this->confirmingDelete = null;
    }

    public function render()
    {
        $users = User::withCount([
            'posts',
            'comments',
        ])
            ->withCount([
                'posts as reports_count' => function ($query) {
                    $query->whereHas('reports');
                }
            ])
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('username', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.users.index', compact('users'));
    }
}
