<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\ServerEventLog;
use App\Models\ServerPing;
use App\Models\ServerVote;
use App\Models\Transaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * New Controller Instance
     * 
     * @return void
     */
    public function __construct()
    {
        //Require auth
        $this->middleware("auth");     
    }

    /**
     * View the analytics
     * 
     * @param int $server_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function basic($server_id, Request $request){
        $server = Server::where([
            "id" => $server_id,
            "user_id" => $request->user()->id
        ])->first();
        if (is_null($server)) {
            abort(404);
        }

        $range = \Carbon\CarbonPeriod::create(Carbon::now()->subDays(7), Carbon::now());

        $analytics = $this->getAnalyticsFromDateRange($server, $range);

        return view("analytics.basic")->with([
            "server" => $server,
            "dates" => $range,
            "votes" => $analytics["votes"],
            "players" => $analytics["players"],
            "ipcopies" => $analytics["ipcopies"]
        ]);

    }

    

    /**
     * View analytics for an order
     * 
     * @param int $order_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function order($order_id, Request $request){
        $order = Transaction::where("id", $order_id)
            ->whereHas("server", function($q){
                $q->where("user_id", auth()->user()->id);
            })
            ->firstOrFail();

        $server = $order->server;
        $feature_range = CarbonPeriod::create($order->paid_at, $order->feature_until);
        
        $start_previous = $order->paid_at->copy()->addDays($order->days_for);
        $end_previous = $order->feature_until->copy()->addDays($order->days_for);
        $previous_range = CarbonPeriod::create($start_previous, $end_previous);

        $feature_analytics = $this->getAnalyticsFromDateRange($server, $feature_range);
        $previous_analytics = $this->getAnalyticsFromDateRange($server, $previous_range);



        return view("analytics.order")->with([
            "order" => $order,
            "dates" => $feature_range,
            "featured" => $feature_analytics,
            "previous" => $previous_analytics
        ]);

    }

    /**
     * Get the analytics from a date range
     * 
     * @param \App\Models\Server $server
     * @param \Carbon\CarbonPeriod $range
     * @return array
     */
    private function getAnalyticsFromDateRange(\App\Models\Server $server, \Carbon\CarbonPeriod $range){
        $votes = [];
        $players = [];
        $ipcopies = [];
        foreach($range as $date){

            $votes[] = ServerVote::whereDate("created_at", $date)->where("server_id", $server->id)->count();
            $pc = ServerPing::whereDate("created_at", $date)->where("server_id", $server->id)->orderBy("online_players", "DESC")->first();
            $ipcopies[] = ServerEventLog::whereDate("created_at", $date)->where(["server_id" => $server->id, "event_type"=>ServerEventLog::EventType("IPCopy")])->count();

            $players[] = is_null($pc) ? 0 : $pc->online_players;

            unset($pc);
            unset($date);
        }

        return [
            "votes" => $votes,
            "players" => $players,
            "ipcopies" => $ipcopies
        ];
    }
}
