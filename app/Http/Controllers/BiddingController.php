<?php

namespace App\Http\Controllers;

use App\BidSession;
use App\Bid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BiddingController extends Controller
{
    /**
     * New Controller Instance
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Current Auction
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function auction(Request $request){
        $auction = BidSession::orderBy("id", "DESC")->first();

        if($auction->finishes_at < Carbon::now()){
            return redirect(route("auction.previous", ['auction' => $auction->id]));
        }

        return view("auction.current")->with([
            "auction"=>$auction,
            "bids"=>$auction->bids()->take(20)->get(),
            "servers"=>$request->user()->servers
        ]);
    }

    /**
     * Bid on current auction
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function bid(Request $request){
        $auction = BidSession::orderBy("id", "DESC")->first();
        $min = $auction->bids()->orderBy("amount", "DESC")->first();
        $min_bid = (is_null($min) ? $auction->min_bid : $min->amount + 1);
        $request->validate([
            "bid"=>["required", "integer", "min:".$min_bid],
            "server"=>["required", "integer", Rule::exists("servers", "id")->where("user_id", $request->user()->id)]
        ]);

        Bid::create([
            "user_id"=>$request->user()->id,
            "server_id"=>$request->input("server"),
            "bid_session_id"=>$auction->id,
            "amount"=>$request->input("bid")
        ]);

        return redirect()->back()->with("success", "Your bid has been placed");

    }

     /**
     * Previous Auction
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\BidSession $auction
     * @return \Illuminate\Http\Response
     */
    public function previous(Request $request, BidSession $auction){
        $auction = BidSession::orderBy("id", "DESC")->first();

        if($auction->finishes_at > Carbon::now()){
            return redirect(route("auction.current"));
        }

        return view("auction.previous")->with([
            "auction"=>$auction,
            "bids"=>$auction->bids()->take(4)->orderBy("amount", "DESC")->get(), // Winning bids
        ]);
    }
}
