<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServerOfflineNotification extends Model
{
    /**
     * Attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = [
        'server_id',
    ];

    /**
     * Server
     * 
     * @return App\Server
     */
    public function server(){
        return $this->belongsTo("App\Server");
    }
}
