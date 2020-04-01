<?php

namespace App;

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
     * @return App\Server
     */
    public function server(){
        return $this->belongsTo("App\Server");
    }
    
    /**
     * Actual Tag
     * 
     * @return App\Tag
     */
    public function tag(){
        return $this->belongsTo("App\Tag");
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
