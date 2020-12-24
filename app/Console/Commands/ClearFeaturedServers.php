<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Server;
use Illuminate\Console\Command;

class ClearFeaturedServers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servers:featured';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears featured servers that have expired';

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
        $servers = Server::where("featured_until", "<", Carbon::now());
        $servers->update(["featured_until"=>null]);
        $this->info("Servers Updated");
    }
}
