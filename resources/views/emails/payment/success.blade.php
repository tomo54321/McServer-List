@component('mail::message')
# Dear {{ $tx->server->owner->username }},

Thank you for your payment! This is your email to let you know it has been processed successfully.<br><br>

The payment method is PayPal.<br><br>

Order #{{ $tx->id }}<br>
Amount Due: ${{ number_format($tx->total, 2) }}<br>
Feature Days: {{ $tx->days_for }} Days<br>

# Order Contents

Featured Listing - {{ $tx->server->name }} (id:{{ $tx->server->id}}) ({{ $tx->paid_at->format("d/m/Y, h:i:s a") }} - {{ $tx->paid_at->copy()->addDays($tx->days_for)->format("d/m/Y, h:i:s a") }})
<br>
Total: ${{ number_format($tx->total, 2) }}
<br><br>

Thank you, if you have any questions please contact us using the form on our website.<br>

Regards,<br>
{{ config('app.name') }}
@endcomponent
