<?php

namespace App\Console\Commands;

use App\Tiket;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CloseExpiredOpenTiket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiredtiket:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close Tiket yang open lebih dari 3 hari';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $expiredTiket = Tiket::where('tiketStatus', 1)
            ->whereDate('created_at', '<', Carbon::now()->subDays(3))
            ->get();
        foreach ($expiredTiket as $tiket) {
            $tiket->tiketStatus = 3;
            $tiket->remarkFeedback = "Automatic Reject. Expired Approval.";
            $tiket->save();
        }
        echo "DONE";
        logger("******** Close Expired Tiket ");
        return "DONE";
    }
}
