@extends("layouts.app",[
"pagename"=>"Previous Auction"
])

@section("content")
<div class="container">
    <h1>Sponsored Auction</h1>
    <p>Sponsored servers are always shown at the top of the page when looking at the server list regardless of any
        filters chosen.</p>
    <p>We auction off 4 sponsored slots every 2 weeks. Winning bids will have their server in our sponsored section for
        2 weeks.</p>



    <div class="row">

        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">Top 20 Bids</div>
                <div class="card-body">
                    @if($bids->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Server</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bids as $b)
                            <tr>
                                <td>{{$b->server->name}}</td>
                                <td>${{number_format($b->amount, 2)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @else
                    <center>
                        <h3>There were no bids!</h3>
                    </center>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    Current Auction
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Server Time</th>
                            <td>{{date("jS M Y \\a\\t h:ia")}}</td>
                        </tr>
                        <tr>
                            <th>Auction #</th>
                            <td>{{$auction->id}}</td>
                        </tr>
                        <tr>
                            <th>Bidding Started</th>
                            <td class="text-danger font-weight-bold">{{$auction->begins_at->format("jS M Y \\a\\t h:ia")}}</td>
                        </tr>
                        <tr>
                            <th>Bidding Finished</th>
                            <td>{{$auction->finishes_at->format("jS M Y \\a\\t h:ia")}}</td>
                        </tr>
                        <tr>
                            <th>Minimum Starting Bid</th>
                            <td>${{$auction->min_bid}}</td>
                        </tr>
                        <tr>
                            <th>Payment Due</th>
                            <td>{{$auction->payment_due->format("jS M Y \\a\\t h:ia")}}</td>
                        </tr>
                        <tr>
                            <th>Sponsor Duration</th>
                            <td>{{$auction->duration_weeks}} Weeks</td>
                        </tr>
                        <tr>
                            <th>Sponsor Begins</th>
                            <td>{{$auction->sponsor_from->format("jS M Y \\a\\t h:ia")}}</td>
                        </tr>
                        <tr>
                            <th>Sponsor Finishes</th>
                            <td>{{$auction->sponsor_from->addWeeks($auction->duration_weeks)->format("jS M Y \\a\\t h:ia")}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection