@extends('layouts.app',[
    "pagename"=>"My Servers"
])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-6">
            <h1>My Servers</h1>
        </div>
        <div class="col-6 text-right">
            <a href="{{route('server.create')}}" class="btn btn-primary">New Server</a>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            @if(!is_null($servers))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Server</th>
                            <th>IP</th>
                            <th>Status</th>
                            <th>Currently Online</th>
                            <th>Liftime Votes</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($servers as $srv)
                        <tr>
                            <th><a href="{{route('server.show', ['server'=>$srv->id])}}">{{$srv->name}}</a></th>
                            <td>{{$srv->full_ip}}</td>
                            <td>{{$srv->is_online ? "Online" : "Offline"}}</td>
                            <td>{{number_format($srv->online_players ?? 0, 0)}} Players Online</td>
                            <td>{{number_format($srv->votes()->count(), 0)}} Votes</td>
                            <td><a href="{{route('server.edit', ['server'=>$srv->id])}}" class="btn btn-secondary btn-sm">Edit</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <center>
                    <span class="d-block h1 mb-3">No Servers</span>
                    <p class="text-muted">It looks like you don't have any servers listed</p>
                    <a href="{{route('server.create')}}" class="btn btn-success">Add your server?</a>
                </center>
            @endif
        </div>
    </div>
@endsection