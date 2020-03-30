<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'header_path',
        'banner_path',
        'description',
        'youtube_id',
        'votifier_key',
        'votifier_port',
        'ip',
        'port',
        'featured_until',
        'standing_out_until',
        'last_pinged',
    ];
    
    
    /**
     * Tags
     * 
     * @return Illuminate\Support\Collection
     */
    public function tags(){
        return $this->hasManyThrough("App\Tag", "App\ServerTag");
    }
}
