<?php

namespace App\Http\Controllers;

use App\Rules\ServerIP;
use App\Server;
use App\ServerPing;
use App\ServerTag;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

class ServerController extends Controller
{

    /**
     * New controller instance
     */
    public function __construct()
    {
        $this->middleware("auth")->except(["index", "show"]);
    }

    /**
     * Display a listing of the resource.
     * (Route is disabled though as this is the homepage)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("home");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("servers.create")->with(["tags"=>Tag::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required", "min:2", "string"],
            "ip" => ["required", "string", "min:2", new ServerIP],
            "port"=>["required", "string"],
            "banner"=>["nullable", "image", "mimes:jpeg,jpg,png,gif", "max:1024"],
            "header" => ["nullable", "mimes:jpeg,jpg,png,gif", "max:1024"],
            "youtubeid" => ["nullable", "string"],
            "website" => ["nullable", "string"],
            "desc" => ["required" ,"string", "min:10"],
            "vote_ip" => ["required_with:vote_port,voteifier_key", new ServerIP],
            "vote_port" => ["required_with:vote_ip,voteifier_key"],
            "votifier_key" => ["required_with:vote_ip,vote_port"],
            "tags" => ["required", "array", "min:1", "max:5"],
            "tags.*" => ["numeric", "exists:tags,id"]
        ]);


        //Already tracking the server?
        $serv = Server::where([
            "ip"=>$request->input("ip"),
            "port"=>$request->input("port")
        ])->first();
        if(!is_null($serv)){
            return redirect()->back()->withErrors(["ip"=>"This server already exists."]);
        }

        //Query to make sure it's online.
        try{
            $Query = new MinecraftPing($request->input("ip"), $request->input("port"));
            $Query = $Query->Query();
        }catch(MinecraftPingException $ex){
            return redirect()->back()->withErrors(["ip"=>"The server isn't online, please verify it is online"]);
        }

        //Path for images.
        $folder_path = "public/".$request->user()->id;

        //Path exists already?
        if(!is_dir(storage_path("app/".$folder_path))){
            mkdir(storage_path("app/".$folder_path), 0777, true);
        }

        //Save uploaded files if they're provided
        $banner_filename = null;
        $header_filename = null;
        if(!is_null($request->file("banner"))){
            $banner_filename = md5( time().$request->file("banner")->getClientOriginalExtension() ).".".$request->file("banner")->getClientOriginalExtension();
            $banner_path = $request->file("banner")->storeAs($folder_path, $banner_filename);
        }
        if(!is_null($request->file("header"))){
            $header_filename = md5( time().$request->file("header")->getClientOriginalName() ).".".$request->file("header")->getClientOriginalExtension();
            $header_path =  $request->file("header")->storeAs($folder_path, $header_filename);
        }


        //Create an image from base64 favicon.
        $icon_filename = null;
        if(isset($Query["favicon"])){
            $image = str_replace('data:image/png;base64,', '', $Query["favicon"]);
            $image = str_replace(' ', '+', $image);
            $icon_filename = md5( time().\Illuminate\Support\Str::random(12) ).'.png';
            //Save the favicon
            File::put(storage_path("app/".$folder_path)."/".$icon_filename, base64_decode($image));
        }
        //Save to DB
        $server = Server::create([
            "user_id"=>$request->user()->id,
            "name"=>$request->input("name"),
            "description"=>$request->input("desc"),
            'website'=>$request->input("website"),
            "youtube_id"=>$request->input("youtubeid"),

            "banner_path"=>$banner_filename,
            "header_path"=>$header_filename,
            "icon_path"=>$icon_filename,

            "ip"=>$request->input("ip"),
            "port"=>$request->input("port"),

            "online_players"=>$Query["players"]["online"],
            "max_players"=>$Query["players"]["max"],
            "version_string"=>$Query["version"]["name"],
            "is_online"=>true,
            "last_pinged"=>new \DateTime()
        ]);

        //Create initial ping for today.
        ServerPing::create([
            "server_id"=>$server->id,
            "players_online"=>$Query["players"]["online"]
        ]);

        foreach($request->input("tags") as $tag){
            ServerTag::create([
                "server_id"=>$server->id,
                "tag_id"=>$tag
            ]);
        }

        return redirect(route("server.show", ["server"=>$server->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $server = Server::findOrFail($id);
        $server->ping();
        return view("servers.show")->with([
            "server"=>$server,
            "path" => $server->user_id
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
