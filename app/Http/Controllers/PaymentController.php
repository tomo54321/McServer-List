<?php

namespace App\Http\Controllers;

use App\Transaction;
use Exception;
use Omnipay\Omnipay;

class PaymentController extends Controller
{
    
/**
     * Page to show on success
     * 
     * @param int $transaction
     * @return \Illuminate\View\View
     */
    public function paypal(int $transaction){
        $transaction = Transaction::where("paid", false)
            ->whereHas("server", function($q){
                $q->where("user_id", auth()->user()->id);
            })
            ->where("id", $transaction)
            ->firstOrFail();

        try{
            $response = $this->getGateway()
                ->purchase([
                    "amount" => $transaction->total,
                    "transactionId" => $transaction->id,
                    "currency" => "USD",
                    "returnUrl" => route("feature.success", ["order" => $transaction->id]),
                    "cancelUrl" => route("feature.show", ["payerror" => "1"]),
                    "notifyUrl" => route("payment.ipn.paypal", ["order" => $transaction->id]),
                ])->send();

            if($response->isSuccessful()){
                $transaction->markAsPaid($response->getTransactionReference());

                return redirect(route("feature.complete", ["order" => $transaction->id]));
            } else {
                return redirect(route("feature.show"))->withErrors([
                    "checkout" => $response->getMessage()
                ]);
            }
        } catch(Exception $ex){
            return redirect(route("feature.show"))->withErrors([
                "checkout" => $ex->getMessage()
            ]);
        }
    }

    /**
     * Get the gateway object
     * 
     * @return \Omnipay\PayPal
     */
    public function getGateway(){
        $gateway = Omnipay::create("PayPal_Rest");
        $gateway->setClientId(config("paypal.clientid"));
        $gateway->setSecret(config("paypal.clientsecret"));
        $gateway->setTestMode(config("paypal.mode") == "sandbox");
        return $gateway;
    }

}
