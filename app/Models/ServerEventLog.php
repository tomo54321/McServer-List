<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerEventLog extends Model
{

    /**
     * Event Types
     * 
     * @var array
     */
    static $TYPES = [ "IPCopy" ];

    /**
     * Attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = [
        'server_id',
        'ip',
        'event_type',
    ];

    /**
     * Cast attributes to native objects
     * 
     * @var array
     */
    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];

    /**
     * Server
     * 
     * @return \App\Models\Server
     */
    public function server(){
        return $this->belongsTo(Server::class);
    }

    /**
     * Get Event Type
     * 
     * @return string
     */
    public function getEventAttribute(){
        return self::$TYPES[$this->event_type];
    }

    /**
     * Get Event Type from array
     * @param string $event_name
     * @return int|null
     */
    public static function EventType($event_name){
        return array_search($event_name, self::$TYPES);
    }

}
