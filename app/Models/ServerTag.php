<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerTag extends Model
{
    /**
     * Disable timestamps
     * 
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Attributes that are mass assignable
     * 
     * @var array
     */
    protected $fillable = [
        'server_id',
        'tag_id'
    ];

    /**
     * Server
     * 
     * @return App\Models\Server
     */
    public function server(){
        return $this->belongsTo(Server::class);
    }
    
    /**
     * Actual Tag
     * 
     * @return App\Models\Tag
     */
    public function tag(){
        return $this->belongsTo(Tag::class);
    }

    /**
     * Get the tags name
     * 
     * @return string
     */
    public function getNameAttribute(){
        return $this->tag->name;
    }
}
