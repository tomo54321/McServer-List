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
    protected $timestamps = false;

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
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::belongsTo
     */
    public function server(){
        return $this->belongsTo("App\Server");
    }

    /**
     * Tag
     * 
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::belongsTo
     */
    public function tag(){
        return $this->belongsTo("App\Tag");
    }
}
