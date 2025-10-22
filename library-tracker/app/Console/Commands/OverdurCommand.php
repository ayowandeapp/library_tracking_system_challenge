<?php

namespace App\Console\Commands;

use App\Mail\OverdueLoanMail;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class OverdurCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:overdur-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Overdue Loan Notification';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $loans = Loan::whereNull('returned_at')->where('due_at', '<', $today)
            ->get();

        foreach ($loans as $loan) {
            Mail::to($loan->user->email)->send(new OverdueLoanMail($loan));

        }

    }
}
