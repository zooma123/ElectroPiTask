<?php

namespace App\Console\Commands;

use App\Jobs\SendTaskOverdueNotificationJob;
use App\Modules\Tasks\Models\Task;
use Illuminate\Console\Command;

class CheckOverdueTasksCommand extends Command
{
    protected $signature = 'tasks:check-overdue';

    protected $description = 'Check for overdue tasks and dispatch notification jobs.';

    public function handle()
    {
        $overdueTasks = Task::with('project.user')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString())
            ->whereNotIn('status', ['Done'])
            ->where('is_overdue_notified', false)
            ->get();

        $count = 0;
        foreach ($overdueTasks as $task) {
            SendTaskOverdueNotificationJob::dispatch($task);
            $count++;
        }

        $this->info("Dispatched $count overdue task notification jobs.");
    }
}
