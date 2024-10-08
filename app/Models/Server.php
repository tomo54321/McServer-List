<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

class Server extends Model
{
    /**
     * Attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'ip',
        'port',

        'banner_path',
        'header_path',
        'icon_path',

        'description',
        'website',
        'discord',
        'country',

        'youtube_id',

        'votifier_key',
        'votifier_ip',
        'votifier_port',

        'featured_until',
        'standing_out_until',

        'online_players',
        'max_players',
        'version_string',
        'is_online',
        'last_pinged',
    ];

    /**
     * Variables hidden from json arrays
     * 
     * @var array
     */
    protected $hidden = [
        "votifier_key",
        "votifier_ip",
        "votifier_port",
        "featured_until",
        "standing_out_until",
        "version_string",
        "online_players",
        "max_players",
        "offline_since",
        "last_pinged",
        "created_at",
        "updated_at",
        "user_id",
    ];

    /**
     * Cast attributes to native objects
     * 
     * @var array
     */
    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime",
        "last_pinged" => "datetime",
        "offline_since" => "datetime"
    ];

    // this is a recommended way to declare event handlers
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($server) { // before delete() method call this

            //Path for images.
            $folder_path = "public/" . $server->user_id;

            //Path exists already?
            if (is_dir(storage_path("app/" . $folder_path))) {
                if ($server->has_banner) {
                    $banner_full_path = storage_path("app/" . $folder_path) . "/" . $server->banner_path;
                    $banner_jpg_full_path = storage_path("app/" . $folder_path) . "/" . $server->banner_jpg_path;
                    if(file_exists($banner_full_path)) {
                        unlink($banner_full_path);
                    }
                    if(file_exists($banner_jpg_full_path)) {
                        unlink($banner_jpg_full_path);
                    }
                }
                if ($server->has_header) {
                    $header_full_path = storage_path("app/" . $folder_path) . "/" . $server->header_path;
                    if(file_exists($header_full_path)) {
                        unlink($header_full_path);
                    }
                }
                if ($server->has_icon) {
                    $icon_full_path = storage_path("app/" . $folder_path) . "/" . $server->icon_path;
                    if(file_exists($icon_full_path)) {
                        unlink($icon_full_path);
                    }
                }
            }

            $server->votes()->delete();
            $server->cache_pings()->delete();
            $server->offline_notifications()->delete();
        });
    }

    /**
     * Tags
     * 
     * @return Illuminate\Support\Collection
     */
    public function tags()
    {
        return $this->hasMany(ServerTag::class);
    }

    /**
     * Owner
     * 
     * @return \App\Model\User
     */
    public function owner()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    /**
     * Server Pings
     * 
     * @return \Illuminate\Support\Collection
     */
    public function cache_pings()
    {
        return $this->hasMany(ServerPing::class);
    }

    /**
     * Offline notifications
     * @return \Illuminate\Support\Collection
     */
    public function offline_notifications()
    {
        return $this->hasMany(ServerOfflineNotification::class);
    }

    /**
     * Votes
     * 
     * @return \Illuminate\Support\Collection
     */
    public function votes()
    {
        return $this->hasMany(ServerVote::class);
    }

    /**
     * Has Banner?
     * 
     * @return boolean
     */
    public function getHasBannerAttribute()
    {
        return !is_null($this->banner_path);
    }

    /**
     * Has Banner?
     * 
     * @return boolean
     */
    public function getHasHeaderAttribute()
    {
        return !is_null($this->header_path);
    }

    /**
     * Get the banner jpg path
     * 
     * @return string
     */
    public function getBannerJpgPathAttribute(){
        if(!$this->has_banner){
            return null;
        }

        return str_replace(".gif", ".jpg", $this->banner_path);
    }

    /**
     * Has Banner?
     * 
     * @return boolean
     */
    public function getHasIconAttribute()
    {
        return !is_null($this->icon_path);
    }

    /**
     * Enabled Votifier?
     * 
     * @return boolean
     */
    public function getEnabledVotifierAttribute()
    {
        return !is_null($this->votifier_key) && !is_null($this->votifier_port) && !is_null($this->votifier_ip);
    }

    /**
     * Get full IP (If server has default port don't show it)
     * 
     * @return string
     */
    public function getFullIpAttribute()
    {
        return $this->ip . (($this->port == 25565) ? "" : ":" . $this->port);
    }

    /**
     * Ping the server
     * 
     * @return void
     */
    public function ping()
    {
        //More than 10 minutes ago then ping server.
        if ($this->last_pinged->addMinutes(7) < Carbon::now()) {

            //Query to make sure it's online.
            try {
                $Query = new MinecraftPing($this->ip, $this->port);
                $Query = $Query->Query();
                $this->is_online = true;
                $this->online_players = $Query["players"]["online"];
                $this->max_players = $Query["players"]["max"];
                $this->version_string = $Query["version"]["name"];
                $this->last_pinged = new \DateTime();
                $this->offline_since = null;
                $this->save();


                // Check if there's a ping within the hour.
                $ping = ServerPing::where("created_at", ">", Carbon::now()->subHours(1))
                    ->where("server_id", $this->id)->first();
                if (is_null($ping)) {
                    $ping = new ServerPing;
                    $ping->server_id = $this->id;
                    $ping->online_players = $Query["players"]["online"];
                } else if ($ping->online_players < $Query["players"]["online"]) {
                    $ping->online_players = $Query["players"]["online"];
                }
                $ping->save();
            } catch (MinecraftPingException $ex) {
                $this->is_online = false;
                $this->online_players = null;
                $this->max_players = null;
                $this->version_string = null;
                $this->last_pinged = new \DateTime();
                if (is_null($this->offline_since)) {
                    $this->offline_since = new \DateTime();
                }
                $this->save();

                $ex_notification = ServerOfflineNotification::where("server_id", $this->id)
                    ->whereDate("created_at", new \DateTime())
                    ->count();
                //No notifications already
                if ($ex_notification < 1) {
                    //Create Server Offline Notification
                    ServerOfflineNotification::create([
                        "server_id" => $this->id
                    ]);
                }
            }
        }
    }
}
