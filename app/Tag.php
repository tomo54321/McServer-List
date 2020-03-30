<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Disable timestamps
     * 
     * @var boolean
     */
    protected $timestamps = false;

    /**
     * Fillable
     * 
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
