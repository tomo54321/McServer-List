<?php

namespace App\Http\Controllers;

use App\Rules\ServerIP;
use App\Rules\ValidCountry;
use App\Server;
use App\ServerPing;
use App\ServerTag;
use App\ServerVote;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use Intervention\Image\ImageManagerStatic as Image;

use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

class ServerController extends Controller
{

    /**
     * New controller instance
     */
    public function __construct()
    {
        $this->middleware("auth")->except(["index", "show", "vote", "cast"]);

        $this->middleware("verified")->only(["create", "store", "edit", "update"]);

        //Only allow 2 requests every 1 minute to update a server.
        // $this->middleware("throttle:2,1")->only(["update"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            "query" => ["nullable", "string", "min:3"],
            "category" => ["nullable", "exists:tags,id"],
            "sortby" => ["nullable", "in:votes,players,updated,added"]
        ]);
        $servers = Server::withCount("votes")->where("is_online", true);

        //Category Filter
        if (!is_null($request->input("category"))) {
            $servers->whereHas("tags", function($q) use ($request){
                $q->where("tag_id", $request->input("category"));
            });
        }
        //Order By Filter
        $servers->orderBy(
            $this->getColNameFromSort($request->input("sortby")),
            "DESC"
        );
        
        //Query
        if (!is_null($request->input("query"))) {
            $servers->where("name", "like", "%{$request->input("query")}%");
        }

        $promoted_servers = Server::where("featured_until", "!=", null)->get();

        return view("home")->with([
            "tags" => Tag::all(),
            "servers" => $servers->paginate(10),
            "promoted" => $promoted_servers,
            "query" => $request->input("query")
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("servers.create")
        ->with([
            "tags" => Tag::all(),
            "countries" => json_decode(\Countries::getList('en', 'json'), true)
        ]);
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
            "port" => ["required", "string"],
            "banner" => ["nullable", "image", "mimes:jpeg,jpg,png,gif", "max:1024"],
            "header" => ["nullable", "mimes:jpeg,jpg,png,gif", "max:2048"],
            "youtubeid" => ["nullable", "string"],
            "website" => ["nullable", "string"],
            "discord" => ["nullable", "string"],
            "country" => ["nullable", "string", "min:2", "max:2", new ValidCountry],
            "desc" => ["required", "string", "min:10"],
            "vote_ip" => ["required_with:votifier_key", new ServerIP],
            "vote_port" => ["required_with:vote_ip,votifier_key"],
            "votifier_key" => ["required_with:vote_ip"],
            "tags" => ["required", "array", "min:1", "max:5"],
            "tags.*" => ["numeric", "exists:tags,id"],
            recaptchaFieldName() => recaptchaRuleName(),
        ]);

        //Already tracking the server?
        $serv = Server::where([
            "ip" => $request->input("ip"),
            "port" => $request->input("port")
        ])->first();
        if (!is_null($serv)) {
            return redirect()->back()->withErrors(["ip" => "This server already exists."]);
        }

        //Query to make sure it's online.
        try {
            $Query = new MinecraftPing($request->input("ip"), $request->input("port"));
            $Query = $Query->Query();
        } catch (MinecraftPingException $ex) {
            return redirect()->back()->withErrors(["ip" => "The server isn't online, please verify it is online"]);
        }

        //Path for images.
        $folder_path = "public/" . $request->user()->id;

        //Path exists already?
        if (!is_dir(storage_path("app/" . $folder_path))) {
            mkdir(storage_path("app/" . $folder_path), 0777, true);
        }

        //Save uploaded files if they're provided
        $banner_filename = null;
        $header_filename = null;
        if (!is_null($request->file("banner"))) {
            $banner_filename = md5(time() . $request->file("banner")->getClientOriginalExtension()) . "." . $request->file("banner")->getClientOriginalExtension();
            $banner_path = $request->file("banner")->storeAs($folder_path, $banner_filename);
        }
        if (!is_null($request->file("header"))) {
            $header_filename = md5(time() . $request->file("header")->getClientOriginalName()) . ".jpg";
            $header_path = storage_path("app/" . $folder_path) . "/" .$header_filename;
            Image::make($request->file("header"))->fit(1110, 200)->save($header_path , 60, "jpg");
            unset($header_path);
        }


        //Create an image from base64 favicon.
        $icon_filename = null;
        if (isset($Query["favicon"])) {
            $image = str_replace('data:image/png;base64,', '', $Query["favicon"]);
            $image = str_replace(' ', '+', $image);
            $icon_filename = md5(time() . \Illuminate\Support\Str::random(12)) . '.png';
            //Save the favicon
            File::put(storage_path("app/" . $folder_path) . "/" . $icon_filename, base64_decode($image));
        }
        //Save to DB
        $server = Server::create([
            "user_id" => $request->user()->id,
            "name" => $request->input("name"),
            "description" => $request->input("desc"),
            'website' => $request->input("website"),
            'discord' => $request->input("discord"),
            "youtube_id" => $request->input("youtubeid"),

            "banner_path" => $banner_filename,
            "header_path" => $header_filename,
            "icon_path" => $icon_filename,

            "ip" => $request->input("ip"),
            "port" => $request->input("port"),

            "online_players" => $Query["players"]["online"],
            "max_players" => $Query["players"]["max"],
            "version_string" => $Query["version"]["name"],
            "is_online" => true,
            "last_pinged" => new \DateTime()
        ]);

        //Create initial ping for today.
        ServerPing::create([
            "server_id" => $server->id,
            "online_players" => $server->online_players
        ]);

        foreach ($request->input("tags") as $tag) {
            ServerTag::create([
                "server_id" => $server->id,
                "tag_id" => $tag
            ]);
        }

        return redirect(route("server.show", ["server" => $server->id]));
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
            "server" => $server,
            "path" => $server->user_id,
            "alltime_votes" => $server->votes()->count(),
            "month_votes" => $server->votes()->whereBetween("created_at", [\Carbon\Carbon::now()->firstOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->count()
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
        $server = Server::where([
            "id" => $id,
            "user_id" => Auth::user()->id
        ])->first();
        if (is_null($server)) {
            abort(404);
        }

        return view("servers.edit")->with([
            "tags" => Tag::all(),
            "server_tags" => $server->tags,
            "countries" => json_decode(\Countries::getList('en', 'json'), true),
            "server" => $server
        ]);
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
        $server = Server::where([
            "id" => $id,
            "user_id" => Auth::user()->id
        ])->first();

        $request->validate([
            "name" => ["required", "min:2", "string"],
            "ip" => ["required", "string", "min:2", new ServerIP],
            "port" => ["required", "string"],
            "banner" => ["nullable", "image", "mimes:jpeg,jpg,png,gif", "max:1024"],
            "header" => ["nullable", "mimes:jpeg,jpg,png,gif", "max:2048"],
            "youtubeid" => ["nullable", "string"],
            "website" => ["nullable", "string"],
            "discord" => ['nullable', 'string'],
            "country" => ["nullable", "string", "min:2", "max:2", new ValidCountry],
            "desc" => ["required", "string", "min:10"],
            "vote_ip" => ["required_with:votifier_key", new ServerIP],
            "vote_port" => ["required_with:vote_ip,votifier_key"],
            "votifier_key" => ["required_with:vote_ip"],
            "tags" => ["required", "array", "min:1", "max:5"],
            "tags.*" => ["numeric", "exists:tags,id"],
            recaptchaFieldName() => recaptchaRuleName(),
        ]);


        //Already tracking the server?
        $track = Server::where([
            "ip" => $request->input("ip"),
            "port" => $request->input("port")
        ])
            ->where("user_id", "!=", $request->user()->id)
            ->first();
        if (!is_null($track)) {
            return redirect()->back()->withErrors(["ip" => "This server already exists."]);
        }

        //Query to make sure it's online.
        if (!$this->basic_ping($request->input("ip"), $request->get("port"))) {
            return redirect()->back()->withErrors(["ip" => "The server isn't online, please verify it is online"]);
        }

        //Path for images.
        $folder_path = "public/" . $request->user()->id;

        //Path exists already?
        if (!is_dir(storage_path("app/" . $folder_path))) {
            mkdir(storage_path("app/" . $folder_path), 0777, true);
        }

        //Save uploaded files & override and previous files, if they're provided
        $banner_filename = $server->banner_path;
        $header_filename = $server->header_path;
        if (!is_null($request->file("banner"))) {

            if ($server->has_banner) {
                unlink(storage_path("app/" . $folder_path) . "/" . $server->banner_path);
            }

            $banner_filename = md5(time() . $request->file("banner")->getClientOriginalExtension()) . "." . $request->file("banner")->getClientOriginalExtension();
            $banner_path = $request->file("banner")->storeAs($folder_path, $banner_filename);
        }
        if(!is_null($request->input("remove_banner")) && $server->has_banner && is_null($request->file("banner")) ){
            unlink(storage_path("app/" . $folder_path) . "/" . $server->banner_path);
            $banner_filename = null;
        }

        if (!is_null($request->file("header"))) {

            if ($server->has_header) {
                unlink(storage_path("app/" . $folder_path) . "/" . $server->header_path);
            }

            $header_filename = md5(time() . $request->file("header")->getClientOriginalName()) . ".jpg";
            $header_path = storage_path("app/" . $folder_path) . "/" .$header_filename;
            Image::make($request->file("header"))->fit(1110, 200)->save($header_path , 60, "jpg");
            unset($header_path);
        }
        if(!is_null($request->input("remove_header")) && $server->has_header && is_null($request->file("header")) ){
            unlink(storage_path("app/" . $folder_path) . "/" . $server->header_path);
            $header_filename = null;
        }


        //Save to DB
        $server->update([
            "name" => $request->input("name"),
            "description" => $request->input("desc"),
            'website' => $request->input("website"),
            'discord' => $request->input("discord"),
            'country' => $request->input("country"),
            "youtube_id" => $request->input("youtubeid"),

            "banner_path" => $banner_filename,
            "header_path" => $header_filename,

            "votifier_ip" => $request->input("vote_ip"),
            "votifier_port" => $request->input("vote_port"),
            "votifier_key" => $request->input("votifier_key"),

            "ip" => $request->input("ip"),
            "port" => $request->input("port")
        ]);

        //Clear all tags and add them again
        $server->tags()->delete();
        foreach ($request->input("tags") as $tag) {
            ServerTag::create([
                "server_id" => $server->id,
                "tag_id" => $tag
            ]);
        }

        return redirect()->back()->with("success", "Server has been updateded successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $server = Server::where([
            "id" => $id,
            "user_id" => Auth::user()->id
        ])->first();
        
        $server->delete();
        return redirect(route("home"))->with("success", "The server has been deleted successfully");
    }

    /**
     * Get the column name from sort by GET param
     * @param string $sortBy
     * @return string
     */
    private function getColNameFromSort($sortBy)
    {
        switch ($sortBy) {
            case "players":
                return "online_players";
                break;
            case "updated":
                return "updated_at";
                break;
            case "added":
                return "created_at";
                break;
            default:
                return "votes_count";
        }
    }

    /**
     * Basic Ping to server
     * 
     * @param string $ip
     * @param int $port
     * @return boolean
     */
    private function basic_ping($ip, $port = 25565)
    {

        try {
            $Query = new MinecraftPing($ip, $port);

            $returnable = true;
        } catch (MinecraftPingException $e) {
            $returnable = false;
        }

        if ($Query !== null) {
            $Query->Close();
        }

        return $returnable;
    }
}
