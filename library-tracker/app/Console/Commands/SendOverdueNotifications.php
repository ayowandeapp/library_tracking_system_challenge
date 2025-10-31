<?php

namespace App\Console\Commands;

use App\Models\Loan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendOverdueNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:send-overdue-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $overdues = Loan::whereNull('returned_at')
            ->where('due_at', '<', now())
            ->with('user', 'book')
            ->get();

        foreach ($overdues as $loan) {
            Log::info("Overdue Loan: User {$loan->user->email}, Book: {$loan->book->title}");
        }
    }
}
