<?php

namespace App\Jobs;

use App\Modules\Tasks\Models\Task;
use App\Notifications\TaskOverdueNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTaskOverdueNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function handle(): void
    {
        $user = $this->task->project->user;
        
        if ($user) {
            $user->notify(new TaskOverdueNotification($this->task));
            
            // Mark as notified
            $this->task->update(['is_overdue_notified' => true]);
        }
    }
}
