<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccess extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Invoice
     * @var \App\Models\Transaction $invoice
     */
    private $tx;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Transaction $invoice
     * @return void
     */
    public function __construct(\App\Models\Transaction $tx)
    {
        $this->tx = $tx;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Payment Success")
        ->to($this->tx->server->owner->email)
        ->markdown('emails.payment.success')
        ->with([
            "tx" => $this->tx
        ]);
    }
}
