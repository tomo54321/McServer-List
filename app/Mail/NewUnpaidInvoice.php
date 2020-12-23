<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUnpaidInvoice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Invoice
     * @var \App\BidInvoice $invoice
     */
    private $invoice;

    /**
     * Create a new message instance.
     *
     * @param \App\BidInvoice $invoice
     * @return void
     */
    public function __construct(\App\BidInvoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("New Auction Invoice")
        ->to($this->invoice->bid->user->email)
        ->markdown('emails.invoices.unpaid')
        ->with([
            "invoice" => $this->invoice
        ]);
    }
}
