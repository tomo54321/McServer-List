<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BidSession extends Model
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
        'finishes_at',
        'begins_at',
        'payment_due',
        'min_bid',
        'sponsor_from',
        'duration_weeks'
    ];

    /**
     * Attribute casts to native objects
     * 
     * @var array
     */
    protected $casts = [
        'finishes_at' => 'datetime',
        'begins_at' => 'datetime',
        'sponsor_from' => 'datetime',
        'payment_due' => 'datetime'
    ];

    /**
     * Bids
     * 
     * @return \Illuminate\Support\Collection
     */
    public function bids(){
        return $this->hasMany(Bid::class);
    }

    /**
     * Is open?
     * 
     * @return boolean
     */
    public function getIsOpenAttribute(){
        return $this->begins_at < Carbon::now() && $this->finishes_at > Carbon::now();
    }
}
