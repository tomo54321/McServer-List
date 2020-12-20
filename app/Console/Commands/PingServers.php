<?php

namespace App\Console\Commands;

use App\Server;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PingServers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servers:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping servers that haven\'t been pinged in the last hour.';

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
        $servers = Server::where("last_pinged", "<", Carbon::now()->subHours(1))->get();
        foreach($servers as $srv){
            if(!$srv->is_online){

                /**
                 * If servers are offline for more than 30 days
                 * they are removed.
                 */
                if(Carbon::now()->diff($srv->offline_since)->days > 30){
                    $srv->delete();
                    continue;//Move to next item and don't ping!
                }

            }
            $srv->ping();
        }
        $this->info("Servers have been pinged successfully");
    }
}
