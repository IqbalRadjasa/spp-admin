<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Bill;

use App\Jobs\SendOverdueReminderJob;

class SendOverdueReminders extends Command
{
    protected $signature = 'reminders:overdue';

    protected $description = 'Send overdue payment reminders';

    public function handle()
    {
        $bills = Bill::query()
            ->where(
                'status',
                'unpaid'
            )
            ->where(
                'billing_period',
                '<',
                now()->format('Y-m')
            )
            ->where(
                'reminder_attempts',
                '<',
                3
            )
            ->whereHas(
                'student',
                fn($query) =>
                $query->whereNotNull(
                    'parent_phone'
                )
            )
            ->where(function ($query) {
                $query
                    ->whereNull(
                        'last_reminded_at'
                    )
                    ->orWhere(
                        'last_reminded_at',
                        '<=',
                        now()->subDays(7)
                    );
            })
            ->where(
                'escalation_status',
                'normal'
            )
            ->get();

        foreach ($bills as $bill) {
            SendOverdueReminderJob::dispatch($bill);
        }

        $this->info(
            'Overdue reminders dispatched.'
        );
    }
}
