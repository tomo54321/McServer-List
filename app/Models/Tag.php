<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Disable timestamps
     * 
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Fillable
     * 
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
