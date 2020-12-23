<?php

namespace App\Http\Controllers;

use App\Server;
use App\Transaction;
use Carbon\Carbon;
use Exception;
use Omnipay\Omnipay;

class FeatureController extends Controller
{
    /**
     * Show the featured server pricing form
     * 
     * @return \Illuminate\View\View
     */
    public function show(){
        $featuredServerCount = Server::whereNotNull("featured_until")->count();
        $transactions = Transaction::where("created_at", ">", Carbon::now()->addMinutes(-5))
        ->where("paid", false)
        ->count();

        $availableSlots = $featuredServerCount < config("serverlist.maxsponsors");
        if($availableSlots){
            if($transactions >= (config("serverlist.maxsponsors") - $featuredServerCount)){ // Someone is already buying the slots
                $availableSlots = false;
            }
        }

        $myServers = auth()
        ->user()
            ->servers()
            ->whereNull("featured_until")
            ->where("is_online", true)
            ->whereDate("created_at", "<=", Carbon::now()->addWeeks(-1))
        ->get();

        return view("featured.show")->with([
            "available" => $availableSlots,
            "servers" => $myServers
        ]);
    }

    /**
     * Checkout
     * 
     * @param \App\Http\Requests\Feature\CheckoutRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkout(\App\Http\Requests\Feature\CheckoutRequest $request){
        $tx = Transaction::create([
            "server_id" => $request->input("server"),
            "days_for" => $request->input("feature_length"),
            "price_per_day" => config("serverlist.sponsorpriceperday")
        ]);

        // Do paypal stuff here

        try{
            $response = $this->getGateway()
                ->purchase([
                    "amount" => $tx->total,
                    "transactionId" => $tx->id,
                    "currency" => "USD",
                    "returnUrl" => route("feature.success", ["order" => $tx->id]),
                    "cancelUrl" => route("feature.show", ["payerror" => "1"]),
                    "notifyUrl" => route("payment.ipn.paypal", ["order" => $tx->id]),
                ])->send();

            if($response->isRedirect()){
                $response->redirect();
            } else {
                return back()->withErrors([
                    "checkout" => $response->getMessage()
                ]);
            }
        } catch(Exception $ex){
            return back()->withErrors([
                "checkout" => $ex->getMessage()
            ]);
        }


    }

    /**
     * Page to show on success
     * 
     * @param int $transaction
     * @return \Illuminate\View\View
     */
    public function complete(int $transaction){
        $transaction = Transaction::where("id", $transaction)
            ->whereHas("server", function($q){
                $q->where("user_id", auth()->user()->id);
            })
            ->firstOrFail();

        return view("featured.success")->with([
            "tx" => $transaction
        ]);
    }

    /**
     * Get the gateway object
     * 
     * @return \Omnipay\PayPal
     */
    private function getGateway(){
        return (new PaymentController)->getGateway();
    }

}
