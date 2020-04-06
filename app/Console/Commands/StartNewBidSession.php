<?php

namespace App\Console\Commands;

use App\BidSession;
use Carbon\Carbon;
use Illuminate\Console\Command;

class StartNewBidSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bidding:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start new bidding session if one is due.';

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
     * @return mixed
     */
    public function handle()
    {
        $last_session = BidSession::where("finishes_at", ">", Carbon::now())->count();
        if($last_session > 0){
            $this->error("Session is already on going!");
            return;
        }
        $sponsor_duration = 2; // Default duration
        $new_session = BidSession::create([
            "begins_at"=>Carbon::now()->addWeek(1),
            "finishes_at"=>Carbon::now()->addDays(8),
            "payment_due"=>Carbon::now()->addDays(10),
            "sponsor_from"=>Carbon::now()->addWeeks(2),
        ]);

        $this->info("New bidding session has been started.");
        $this->info("Bidding Session Details - ");
        $this->info("ID: ".$new_session->id);
        $this->info("Opens: ".$new_session->begins_at->format("jS M Y \\a\\t h:ia"));
        $this->info("Closes: ".$new_session->finishes_at->format("jS M Y \\a\\t h:ia"));
        $this->info("Payment Due: ".$new_session->payment_due->format("jS M Y \\a\\t h:ia"));
        $this->info("Sponsor Start: ".$new_session->sponsor_from->format("jS M Y \\a\\t h:ia"));
        $this->info("Sponsor End: ".$new_session->sponsor_from->addWeeks($sponsor_duration)->format("jS M Y \\a\\t h:ia"));
    }
}
