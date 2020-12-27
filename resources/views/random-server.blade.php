@extends('layouts.app', [
    'pagename' => "Random Server"
])

@section('content')
<div id="random-server">
    <center>
        <h3>One Moment</h3>
        <p>Please wait while we connect to our random game server...</p>
    </center>
</div>
@endsection

@section("inline-script")
    <script src="{{ mix('js/rnsrv.js') }}"></script>
@endsection