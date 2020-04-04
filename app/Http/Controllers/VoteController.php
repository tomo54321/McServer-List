<?php

namespace App\Http\Controllers;

use App\Server;
use App\ServerVote;
use Carbon\Carbon;

use D3strukt0r\VotifierClient\ServerType\NuVotifier;
use D3strukt0r\VotifierClient\Vote;
use D3strukt0r\VotifierClient\VoteType\ClassicVote;

use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * Show the vote form
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function vote($id){
        //Only find the server if it's online!
        $server = Server::where([
            "id"=>$id,
            "is_online"=>true
        ])->first();
        if(is_null($server)){ abort(404); }

        return view("servers.vote")->with([
            "server" => $server,
            "path" => $server->user_id
        ]);
    }
    /**
     * Cast vote
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function cast(Request $request, $id){
        //Only find the server if it's online!
        $server = Server::where([
            "id"=>$id,
            "is_online"=>true
        ])->first();
        if(is_null($server)){ abort(404); }

        $request->validate([
            "username" => ["required", "string", "min:4"],
            recaptchaFieldName() => recaptchaRuleName()
        ]);
        
        //Check if user has already voted today!
        $vote = ServerVote::whereDate("created_at", Carbon::now())
        ->where("server_id", $server->id)
        ->where(function($query) use ($request){

            //Check client IP
            $query->where("ip", $request->ip())
            ->orWhere("username", $request->input("username"));
            
            //Check logged in user, if loggedin
            if(\Illuminate\Support\Facades\Auth::check()){
                $query->orWhere("user_id", $request->user()->id);
            }

        })->first();
        //Has already voted?
        if(!is_null($vote)){
            return redirect()->back()->withErrors(["vote"=>"You can only vote for {$server->name} once per day"]);
        }

        if($server->enabled_votifier){
            $serverType = new NuVotifier($server->votifier_ip, $server->votifier_port, $server->votifier_key);
            $voteType = new ClassicVote($request->input("username"), config("app.name", "Laravel"), config("app.url"));
            $vote = new Vote($voteType, $serverType);
            try {
                $vote->send();
            } catch (\Exception $exception) {
                $server->ping();
                return redirect()->back()->withErrors(["vote"=>"Failed to place vote as we couldn't connect to the server."]);
            }

        }

        ServerVote::create([
            'server_id'=>$server->id,
            'user_id'=> (\Illuminate\Support\Facades\Auth::check() ? $request->user()->id : null) ,
            'ip' => $request->ip(),
            'username' => $request->input("username"),
        ]);

        return redirect(route("server.show", ["server"=>$server->id]))->with("success", "Your vote has been placed for {$server->name}");

    }
}
