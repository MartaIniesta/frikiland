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

        // evitar doble reporte
        if (Report::where('reporter_id', Auth::id())
            ->where('reportable_id', $this->postId)
            ->where('reportable_type', Post::class)
            ->exists()
        ) {
            return;
        }

        Report::create([
            'reporter_id'     => Auth::id(),
            'reportable_id'   => $this->postId,
            'reportable_type' => Post::class,
            'reason'          => $this->reason,
            'status'          => 'pending',
        ]);

        $this->reset(['reason', 'showModal', 'postId']);
    }

    public function render()
    {
        return view('livewire.reports.create');
    }
}
