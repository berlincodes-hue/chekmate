<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDueReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $dueDate = $this->task->due_date->format('F j, Y');
        $daysUntilDue = now()->diffInDays($this->task->due_date, false);

        return (new MailMessage)
            ->subject('Task Due Reminder: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a reminder that you have a task due soon:')
            ->line('Task: ' . $this->task->title)
            ->line('Due Date: ' . $dueDate)
            ->line('Days until due: ' . $daysUntilDue)
            ->line('Priority: ' . ucfirst($this->task->priority))
            ->action('View Task', url('/tasks/' . $this->task->id))
            ->line('Thank you for using our application!');
    }
} 