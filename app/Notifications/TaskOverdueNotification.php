<?php

namespace App\Notifications;

use App\Modules\Tasks\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via(object $notifiable): array
    {
        return ['mail']; // using mail for notifications
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->error()
                    ->subject('Task Overdue: ' . $this->task->title)
                    ->line('The following task has exceeded its due date: ' . $this->task->title)
                    ->line('Project: ' . $this->task->project->name)
                    ->line('Due Date: ' . $this->task->due_date)
                    ->action('View Project', url('/api/projects/' . $this->task->project_id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'project_id' => $this->task->project_id,
            'message' => 'Task "' . $this->task->title . '" is overdue.',
        ];
    }
}
