<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\{Report, Post, User};

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $userId = null;
    public $confirmingAction = null;
    public $actionType = null;

    public function mount(User $user = null)
    {
        $this->userId = $user?->id;
    }

    public function confirmAction($reportId, $type)
    {
        $this->confirmingAction = $reportId;
        $this->actionType = $type;
    }

    public function executeAction()
    {
        $report = Report::with('reportable')->findOrFail($this->confirmingAction);

        if ($this->actionType === 'accept') {

            if ($report->reportable instanceof Post) {
                $report->reportable->delete();
            }

            $report->update([
                'status' => 'resolved'
            ]);
        }

        if ($this->actionType === 'dismiss') {
            $report->update([
                'status' => 'reviewed'
            ]);
        }

        $this->reset(['confirmingAction', 'actionType']);
    }

    public function render()
    {
        $reports = Report::with([
            'reporter',
            'reportable.user'
        ])
            ->where('status', 'pending')
            ->when($this->userId, function ($query) {
                $query->whereHasMorph(
                    'reportable',
                    [Post::class],
                    function ($q) {
                        $q->where('user_id', $this->userId);
                    }
                );
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.reports.index', compact('reports'));
    }
}
