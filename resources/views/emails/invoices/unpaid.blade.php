@component('mail::message')
# Dear {{ $invoice->bid->user->username }},

Congratulations on winning this auction. An invoice has been generated on {{ date("jS, M, Y") }}.<br><br>

The payment method is PayPal.<br><br>

Invoice #{{ $invoice->id }}<br>
Amount Due: ${{ number_format($invoice->bid->amount, 2) }}<br>
Due Date: {{ $invoice->bid_session->payment_due->format("jS, M, Y") }}<br>

# Invoice Contents

Sponsored Listing - {{ $invoice->server->name }} (id:{{ $invoice->server->id}}) ({{ $invoice->bid_session->sponsor_from->format("d/m/Y") }} - {{ $invoice->bid_session->sponsor_from->addWeeks($invoice->bid_session->duration_weeks)->format("d/m/Y") }})
<br>
Total: ${{ number_format($invoice->bid->amount, 2) }}

@component('mail::button', ['url' => route("auction.invoice.view", ["invoice" => $invoice->id])])
Pay Invoice
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent
