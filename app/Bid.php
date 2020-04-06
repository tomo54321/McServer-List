<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{

    /**
     * Attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = [
        'server_id',
        'user_id',
        'bid_session_id',
        'amount',
    ];

    /**
     * Attribute casts to native objects
     * 
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Server
     * 
     * @return \App\Server
     */
    public function server(){
        return $this->belongsTo(Server::class);
    }

    /**
     * User
     * 
     * @return \App\User
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Bidding Session
     * 
     * @return \App\BidSession
     */
    public function bid_session(){
        return $this->belongsTo(BidSession::class);
    }
}
