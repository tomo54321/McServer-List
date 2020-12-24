<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Rules\ServerIP;
use App\Models\Server;
use App\Models\ServerEventLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

class ServerController extends Controller
{

    /**
     * New Controller Instance
     * 
     * @return void
     */
    public function __construct()
    {

        //Only allow 2 request every 1 minute for copy ip event
        $this->middleware("throttle:2,1")->only(["ipCopyEvent"]);
    }
    /**
     * Ping server
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function ping(Request $request){
        $request->validate([
            "ip"=>["required", "string", new ServerIP],
            "port"=>["required", "integer"]
        ]);
        
        try{
            $Query = new MinecraftPing($request->input("ip"), $request->input("port"));
            $server = $Query->Query();
            if(!$server){
                throw new MinecraftPingException("Failed to connect");
            }

            return response()->json([
                "success"=>true,
                "server"=>[
                    "motd"=>$server["description"],
                    "players"=>$server["players"],
                    "version"=>$server["version"]["name"]
                ]
            ]);
        }catch(MinecraftPingException $ex){
            return response()->json([
                "success"=>false,
                "message"=>"We weren't able to connect to your server, please verify that your server is online."
            ]);
        }

    }

    /**
     * IP Copy Event
     * @param \Illuminate\Http\Request $request
     * @param int $server
     * @return \Illuminate\Http\Response
     */
    public function ipCopyEvent(Request $request, $server){
        $srv = Server::findOrFail($server);
        $log = ServerEventLog::where(["ip"=>$request->ip(), "server_id"=>$server])->whereDate("created_at", Carbon::now())->first();
        if(!is_null($log)){
            return response()->json(["success"=>false, "message"=>"Duplicate Event."]);
        }
        ServerEventLog::create([
            "ip"=>$request->ip(),
            "server_id"=>$server,
            "event_type"=>ServerEventLog::EventType("IPCopy")
        ]);

        return response()->json(["success"=>true]);
    }
}
