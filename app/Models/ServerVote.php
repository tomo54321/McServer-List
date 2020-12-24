<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerVote extends Model
{
    /**
     * Attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = [
        'server_id',
        'user_id',
        'ip',
        'username',
    ];

    /**
     * Cast attributes to native objects
     * 
     * @var array
     */
    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime"
    ];

    /**
     * Server voted for
     *
     * @return \App\Models\Server
     */
    public function server(){
        return $this->belongsTo(Server::class);
    }
}
