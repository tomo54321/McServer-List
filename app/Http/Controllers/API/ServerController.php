<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Rules\ServerIP;
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
        //Only allow 3 requests every 1 minute to ping a server.
        $this->middleware("throttle:3,1")->only(["ping"]);
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
}
