<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskDueReminder;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Send reminders for tasks that are due soon';

    public function handle()
    {
        $this->info('Checking for tasks due soon...');

        // Get tasks due in the next 3 days that are not completed
        $tasks = Task::where('completed', false)
            ->where('due_date', '>', now())
            ->where('due_date', '<=', now()->addDays(3))
            ->with('user')
            ->get();

        $count = 0;
        foreach ($tasks as $task) {
            // Send notification to the task owner
            $task->user->notify(new TaskDueReminder($task));
            $count++;
        }

        $this->info("Sent {$count} task reminders.");
    }
} 