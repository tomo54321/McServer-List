<?php
return [
    'mode'    => env("PAYPAL_MODE", 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'clientid'    => env('PAYPAL_ID', ''),
    'clientsecret'      => env('PAYPAL_SECRET', ''),
];