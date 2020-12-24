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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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
            "dates" => $range->toArray(),
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
        
        $start_previous = $order->paid_at->copy()->addDays(-$order->days_for);
        $end_previous = $order->feature_until->copy()->addDays(-$order->days_for);
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
        $votes_sql = ServerVote::whereDate("created_at", ">=", $range->first())
            ->whereDate("created_at", "<=", $range->last())
            ->where("server_id", $server->id)
            ->groupBy("created_at")
            ->orderBy("created_at", "DESC")
        ->get([
            DB::raw("DATE(created_at) as date"),
            DB::raw("COUNT(*) as 'votes'")
        ])->toArray();
        $ipcopies_sql = ServerEventLog::whereDate("created_at", ">=", $range->first())
            ->whereDate("created_at", "<=", $range->last())
            ->where([
                "server_id" => $server->id,
                "event_type" => ServerEventLog::EventType("IPCopy")
            ])
            ->groupBy("created_at")
            ->orderBy("created_at", "DESC")
        ->get([
            DB::raw("DATE(created_at) as date"),
            DB::raw("COUNT(*) as 'copies'")
        ])->toArray();
        $player_count_sql = ServerPing::whereDate("created_at", ">=", $range->first())
                ->whereDate("created_at", "<=", $range->last())
                ->where("server_id", $server->id)
                ->orderBy("date", "DESC")
                ->groupBy("date")
        ->get([
            DB::raw("DATE(created_at) as date"),
            DB::raw("MAX(online_players) as playercount")
        ])->toArray();

        $votes = [];
        $ipcopies = [];
        $player_count = [];
                    
        foreach($range as $date){

            $format = $date->format("Y-m-d");
            $v = array_search($format, array_column($votes_sql, "date"));
            $ip = array_search($format, array_column($ipcopies_sql, "date"));
            $pc = array_search($format, array_column($player_count_sql, "date"));

            if($v !== false){ $votes[] = $votes_sql[$v]["votes"]; } else { $votes[] = 0; }
            if($ip !== false){ $ipcopies[] = $ipcopies_sql[$ip]["copies"]; } else { $ipcopies[] = 0; }
            if($pc !== false){ $player_count[] = $player_count_sql[$pc]["playercount"]; } else { $player_count[] = 0; }
            
        }      
        

        return [
            "votes" => $votes,
            "players" => $player_count,
            "ipcopies" => $ipcopies
        ];
    }
}
