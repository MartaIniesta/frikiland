<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $postId;
    public $reason = '';
    public $showModal = false;

    protected $rules = [
        'reason' => 'required|min:10|max:1000'
    ];

    #[On('openReportModal')]
    public function openReportModal($postId)
    {
        $this->postId = $postId;
        $this->showModal = true;
    }

    public function submit()
    {
        $this->validate();

        $post = Post::findOrFail($this->postId);

        Report::create([
            'reporter_id' => Auth::id(),
            'reason' => $this->reason,
            'reportable_id' => $post->id,
            'reportable_type' => Post::class,
        ]);

        $this->reset(['reason', 'showModal', 'postId']);
    }

    public function render()
    {
        return view('livewire.reports.create');
    }
}
