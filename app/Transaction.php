<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    
    /**
     * Mass assignable variables
     * 
     * @var array
     */
    protected $fillable = [
        "user_id",
        "server_id",
        "days_for",
        "price_per_day",
        "paid",
        "vendor_tx_id",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * The user
     * 
     * @return \App\User
     */
    public function user(){
        return $this->hasOneThrough(User::class, Server::class);
    }

    /**
     * The server
     * 
     * @return \App\Server
     */
    public function server(){
        return $this->belongsTo(Server::class);
    }

    /**
     * Get the total
     * 
     * @return float
     */
    public function getTotalAttribute(){
        return $this->days_for * $this->price_per_day;   
    }

    /**
     * Mark this order as paid
     * 
     * @param string $vendorTx
     * @return \App\Transaction
     */
    public function markAsPaid($vendorTx){
        $this->paid = true;
        $this->paid_at = Carbon::now();
        $this->vendor_tx_id = $vendorTx;
        $this->save();

        $srv = $this->server;
        $srv->featured_until = Carbon::now()->addDays($this->days_for);
        $srv->save();

        return $this;
    }

}
