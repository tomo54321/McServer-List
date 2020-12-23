@extends('layouts.app',[
"pagename"=>"Payment Success"
])

@section('content')
<div class="container">
    <h1>Featured Slot Success</h1>
    <p>Your payment has been successful and we are currently processing it. Your order number is #{{ $tx->id }}. You will receive an email when your order has been processed and your server has been moved to the featured section. Please note it may take up to 24hrs for your server to be included in our random server ip.</p>
    <div class="alert alert-success">Your order has been completed and has been sent for processing.</div>
    <a href="{{ route('server.show', ['server' => $tx->server_id]) }}" class="btn btn-success">View Server</a>
</div>
@endsection
