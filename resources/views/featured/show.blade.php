@extends('layouts.app',[
"pagename"=>"Purchase featured slot"
])

@section('content')
<div class="container">
    <h1>Purchase Featured Slot</h1>
    <p>We have up to {{ config("serverlist.maxsponsors") }} featured slots up for grabs, starting from just ${{ number_format(config("serverlist.sponsorpriceperday"), 2) }} per day!<small>*</small></p>
    @error("checkout")
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @if($available)
        <div class="row">
            <div class="col-12 col-md-6">
                <form action="{{ route("feature.setup") }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="server">Select your server</label>
                        <select class="form-control @error("server")is-invalid @enderror" name="server" id="server">
                            @foreach($servers as $server)
                                <option value="{{ $server->id }}">{{ $server->name }}</option>
                            @endforeach
                        </select>
                        @error("server")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <small class="text-muted">If you cannot see your server it is because it is either, not online, added within the last 7 days or is already featured.</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="feature_length">How long would you like to be featured for?</label>
                        <div class="input-group">
                            <input type="number" name="feature_length" id="feature_length" class="form-control @error("feature_length")is-invalid @enderror" required value="{{ old("feature_length") }}" min="{{ config("serverlist.minsponsordays") }}" max="{{ config("serverlist.maxsponsordays") }}">
                            <div class="input-group-append">
                                <span class="input-group-text">Days</span>
                            </div>
                        </div>
                        @error("feature_length")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div>Your featured slot will begin from</div>
                        <span class="form-control">{{ \Carbon\Carbon::now()->format("jS F Y") }}</span>
                    </div>
                    <div class="form-group">
                        <div>Total Price</div>
                        <span class="form-control">$<span class='price'>{{ number_format((float)config("serverlist.sponsorpriceperday") * ((int)old('feature_length') ?? 7), 2) }}</span></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-success" value="Confirm" />
                    </div>
                </form>
            </form>
            </div>
            <div class="col-12 col-md-6">
                <h2>Why feature your server?</h2>
                <p>Here's just a few reasons to feature your server:</p>
                <ul>
                    <li>Always at the top of our server list</li>
                    <li>See an increase in your player count</li>
                    <li>Always included within our random server</li>
                </ul>
            </div>
        </div>
    @else
    <div class="jumbotron text-center">
        <h4>No slots left</h4>
        <p>We don't currently have any featured slots left, please keep checking back as when one becomes available it will be up for grabs here.</p>
    </div>
    @endif
    
    <small class="text-muted">*minimum of {{ config("serverlist.minsponsordays") }} days to feature your server.</small>
</div>
@endsection

@section("inline-script")
<script>
    $(document).ready(function(){
        var price_per_day = {{ config("serverlist.sponsorpriceperday") }};
        $("#feature_length").on("change", function(){
            var days = parseInt($(this).val());
            if(isNaN(days)){
                return;
            }

            $(".price").text( (price_per_day * days).toFixed(2) );
        });
    });
</script>
@endsection