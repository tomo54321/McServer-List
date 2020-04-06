@extends("layouts.app",[
"pagename"=>"Current Auction"
])

@section("content")
<div class="container">
    <h1>Current Sponsored Auction</h1>
    <p>Sponsored servers are always shown at the top of the page when looking at the server list regardless of any
        filters chosen.</p>
    <p>We auction off 4 sponsored slots every 2 weeks. Winning bids will have their server in our sponsored section for
        2 weeks.</p>


    @if(\Session::has("success"))
    <div class="alert alert-success"><strong>Success</strong> {{\Session::get("success")}}</div>
    @endif

    <div class="row">

        @if($auction->is_open)
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
                        <h3>There are no bids!</h3>
                        <p>Start the bidding off from ${{$auction->min_bid}}.</p>
                    </center>
                    @endif
                </div>
            </div>


            @if($servers->count() > 0)
            <div class="card mt-4">
                <div class="card-header">Submit your bid</div>
                <div class="card-body">
                    <form action="{{route('auction.bid')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                <input type="number" name="bid" min={{$auction->min_bid}} value="{{$auction->min_bid}}"
                                    class="form-control @error('bid') is-invalid @enderror" required />
                            </div>
                            @error("bid")
                            <div class="invalid-feedback d-block">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="server">For Server</label>
                            <select name="server" id="server"
                                class="form-control @error('server') is-invalid @enderror">
                                @foreach($servers as $srv)
                                <option value="{{$srv->id}}">{{$srv->name}}</option>
                                @endforeach
                            </select>
                            @error("server")
                            <div class="invalid-feedback d-block">{{$message}}</div>
                            @enderror
                        </div>
                        <input type="submit" class="btn btn-success btn-block" value="Submit Bid" />
                    </form>
                </div>
            </div>
            @endif

        </div>
        @endif
        <div class="col-12 @if($auction->is_open) col-md-6 @endif">
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
                            <th>Bidding Begins</th>
                            <td @if(!$auction->is_open)class="text-danger font-weight-bold"@endif>@if($auction->is_open)
                                {{$auction->begins_at->format("jS M Y \\a\\t h:ia")}} @else
                                {{$auction->begins_at->diffForHumans()}} @endif</td>
                        </tr>
                        <tr>
                            <th>Bidding Finishes</th>
                            <td @if($auction->is_open)class="text-danger font-weight-bold"@endif>@if(!$auction->is_open)
                                {{$auction->finishes_at->format("jS M Y \\a\\t h:ia")}} @else
                                {{$auction->finishes_at->diffForHumans()}} @endif</td>
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