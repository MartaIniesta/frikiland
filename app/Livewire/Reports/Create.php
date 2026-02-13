<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $modelId = null;
    public $modelType = null;
    public $reason = '';
    public $showModal = false;

    protected $rules = [
        'reason' => 'required|min:10|max:1000'
    ];

    #[On('openReportModal')]
    public function openReportModal($modelId, $modelType)
    {
        $this->modelId = $modelId;
        $this->modelType = $modelType;
        $this->showModal = true;
    }

    public function submit()
    {
        $this->validate();

        $model = null;

        if ($this->modelType === 'post') {
            $model = Post::find($this->modelId);
        }

        if ($this->modelType === 'comment') {
            $model = PostComment::find($this->modelId);
        }

        if (!$model) {
            $this->resetState();
            return;
        }

        $alreadyReported = Report::where('reporter_id', Auth::id())
            ->where('reportable_id', $model->id)
            ->where('reportable_type', get_class($model))
            ->exists();

        if ($alreadyReported) {
            $this->resetState();
            return;
        }

        Report::create([
            'reporter_id'     => Auth::id(),
            'reason'          => $this->reason,
            'reportable_id'   => $model->id,
            'reportable_type' => get_class($model),
            'status'          => 'pending'
        ]);

        $this->resetState();
    }

    public function close()
    {
        $this->resetState();
    }

    private function resetState()
    {
        $this->reset([
            'reason',
            'showModal',
            'modelId',
            'modelType'
        ]);
    }

    public function render()
    {
        return view('livewire.reports.create');
    }
}
