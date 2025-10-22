<?php

use App\Models\Loan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->timestamp('due_at')->nullable();
        });
        //can move to a seeder file
        $loans = Loan::get();
        foreach ($loans as $loan) {
            if ($loan->loaned_at) {
                $dueAt = Carbon::parse($loan->loaned_at)->addDays(14);
                Loan::where('id', $loan->id)->update(['due_at' => $dueAt]);

            }
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            //
        });
    }
};
