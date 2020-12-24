@extends('layouts.app',[
    "pagename"=>"Previous Payments"
])

@section('content')
<div class="container">
    <h1>Previous Payments</h1>

    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Server</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Days</th>
                        <th>Featured From</th>
                        <th>Featured Until</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <th><a href="{{route('server.show', ['server'=>$order->server->id])}}">{{$order->server->name}}</a></th>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>{{$order->paid ? "Paid" : "Unpaid"}}</td>
                        <td>{{$order->days_for}} Days</td>
                        <td>{{ $order->paid_at->format("jS M Y \\a\\t h:i:s a") }}</td>
                        <td>{{ $order->paid_at->addDays($order->days_for)->format("jS M Y \\a\\t h:i:s a") }}</td>
                    </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <center>
                            <span class="d-block h1 mb-3">No Payments</span>
                            <p class="text-muted">It looks like you haven't made any payments.</p>
                        </center>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(!empty($orders))
        <div class="card mt-3 mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        {{$orders->links()}}
                    </div>
                    <div class="col-6 text-right">
                        <span class="text-muted">Page {{$orders->currentPage()}} of {{$orders->lastPage()}}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
@endsection