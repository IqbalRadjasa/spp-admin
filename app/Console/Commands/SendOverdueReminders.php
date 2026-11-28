<?php

namespace App\Console\Commands;

use App\Jobs\SendOverdueReminderJob;
use App\Models\Bill;
use Illuminate\Console\Command;

class SendOverdueReminders extends Command
{
    protected $signature = 'reminders:overdue';

    protected $description = 'Send overdue payment reminders';

    public function handle(): int
    {
        $bills = Bill::query()
            ->where('status', 'unpaid')
            ->where('billing_period', '<', now()->format('Y-m'))
            ->where('reminder_attempts', '<', 3)
            ->where('escalation_status', 'normal')
            ->where(function ($query) {
                $query->whereNull('last_reminded_at')
                    ->orWhere('last_reminded_at', '<=', now()->subDays(7));
            })
            ->whereHas('student.parents.studentParent', function ($query) {
                $query->whereNotNull('phone')
                    ->where('phone', '!=', '');
            })
            ->with([
                'student.parents' => function ($query) {
                    $query->withPivot('relationship');
                }
            ])
            ->get();

        foreach ($bills as $bill) {
            SendOverdueReminderJob::dispatch($bill);
        }

        $this->info("Overdue reminders dispatched ({$bills->count()} bills).");

        return self::SUCCESS;
    }
}