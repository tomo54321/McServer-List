<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerPing extends Model
{
    /**
     * Attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = [
        'server_id',
        'online_players'
    ];

    /**
     * Server
     * 
     * @return App\Models\Server
     */
    public function server(){
        return $this->belongsTo(Server::class);
    }
    
}
