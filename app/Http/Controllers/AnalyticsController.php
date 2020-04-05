<?php

namespace App\Http\Controllers;

use App\Server;
use App\ServerEventLog;
use App\ServerPing;
use App\ServerVote;
use Carbon\Carbon;
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
        $server = \App\Server::where([
            "id" => $server_id,
            "user_id" => $request->user()->id
        ])->first();
        if (is_null($server)) {
            abort(404);
        }

        $range = \Carbon\CarbonPeriod::create(Carbon::now()->subDays(7), Carbon::now());
        
        $votes = [];
        $players = [];
        $ipcopies = [];
        foreach($range as $date){
            $votes[] = ServerVote::whereDate("created_at", $date)->where("server_id", $server_id)->count();
            $pc = ServerPing::whereDate("created_at", $date)->where("server_id", $server_id)->first();
            $ipcopies[] = ServerEventLog::whereDate("created_at", $date)->where(["server_id" => $server_id, "event_type"=>ServerEventLog::EventType("IPCopy")])->count();
            $players[] = is_null($pc) ? 0 : $pc->online_players;
            unset($pc);
            unset($date);
        }

        return view("analytics.basic")->with([
            "server" => $server,
            "dates" => $range,
            "votes" => $votes,
            "players" => $players,
            "ipcopies" => $ipcopies
        ]);

    }
}
