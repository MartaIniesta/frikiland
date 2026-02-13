<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use App\Notifications\ContentRemovedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AcceptUserReports extends Command
{
    protected $signature = 'reports:accept {userId}';
    protected $description = 'Accept all pending reports of a specific user and remove their reported content';

    public function handle()
    {
        $userId = $this->argument('userId');

        $user = User::find($userId);

        if (!$user) {
            $this->error('User not found.');
            return;
        }

        $this->info("Processing reports for user: {$user->name}");

        DB::transaction(function () use ($userId) {

            $reports = Report::with('reportable')
                ->where('status', 'pending')
                ->whereHasMorph(
                    'reportable',
                    [Post::class, PostComment::class],
                    function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    }
                )
                ->get();

            foreach ($reports as $report) {

                $reportable = $report->reportable;

                if (!$reportable) {
                    continue;
                }

                $owner = $reportable->user;

                if ($owner) {
                    $owner->notify(
                        new ContentRemovedNotification(
                            $reportable instanceof Post ? 'post'
                                : ($reportable instanceof PostComment ? 'comment' : 'reply'),
                            Str::limit($reportable->content, 100)
                        )
                    );
                }

                $reportable->delete();

                $report->update([
                    'status' => 'resolved'
                ]);
            }
        });

        $this->info('All reports processed successfully.');
    }
}
