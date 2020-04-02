<?php

namespace App;

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
    public function owner(){
        return $this->belongsTo("App\User", "user_id");
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
    public function getEnabledVotifierAttribute(){
        return !is_null($this->votifier_key) && !is_null($this->votifier_port) && !is_null($this->votifier_ip);
    }

    /**
     * Get full IP (If server has default port don't show it)
     * 
     * @return string
     */
    public function getFullIpAttribute(){
        return $this->ip.(($this->port==25565) ? "" : ":".$this->port);
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


                $ping = ServerPing::whereDate("created_at", new \DateTime())
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
                if($ex_notification < 1){
                    //Create Server Offline Notification
                    ServerOfflineNotification::create([
                        "server_id" => $this->id
                    ]);
                }
                
            }
        }
    }
}
