@extends('layouts.app',[
    "pagename"=>$server->name
])

@section('content')
<div class="container">

    @if($server->has_header)
        <span class="d-block server-view-header" style="background-image: url({{asset("storage/".$path."/".$server->header_path)}});"></span>
    @endif
    
    <div class="row my-4">
        <div class="col-6">

            <div class="d-flex align-items-center w-100">
                @if($server->has_icon)
                <img src="{{asset("storage/".$path."/".$server->icon_path)}}" alt="{{$server->name}}" class="mr-2"/>
                @endif
                <h1>{{$server->name}}</h1>
            </div>

        </div>
        <div class="col-6 text-right">
            @auth
                @if($server->user_id == Auth::user()->id)
                <a href="{{route('server.edit', ['server'=>$server->id])}}" class="btn btn-primary">Edit</a>
                @endif
            @endauth
            <a href="" class="btn btn-dark mx-2">Share</a>
            @if($server->website)
                <a href="https://{{$server->website}}" rel="noopener" target="_blank" class="btn btn-primary mx-2">Website</a>
            @endif
            <a href="{{route("server.vote", ["server"=>$server->id])}}" class="btn btn-success">Vote</a>
        </div>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{$server->name}}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-md-6">
            @if(!$server->has_header && $server->has_banner)
                <div class="card mb-3">
                    <div class="card-body">
                        <img src="{{asset( "storage/".$path."/".$server->banner_path )}}" class="d-block w-100" alt="{{$server->name}}" />
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    {{$server->description}}
                </div>
            </div>
            @if($server->youtube_id)
                <div class="card mt-3">
                    <div class="card-header">Video</div>
                    <div class="card-body">
                        <iframe width="100%" height="315" src="https://www.youtube.com/embed/{{$server->youtube_id}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-12 col-md-6">
            <div class="card mt-3 mt-md-0">
                <div class="card-header">Details</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>Name</th>
                            <td>{{$server->name}}</td>
                        </tr>
                        <tr>
                            <th>IP</th>
                            <td>
                                <div class="d-flex">
                                    <span class="flex-grow-1">{{$server->full_ip}}</span>
                                    <button class="btn btn-dark btn-sm copy-ip-btn">Copy IP</button>
                                </div>
                            </td>
                        </tr>
                        @if($server->is_online == false)
                        <tr>
                            <th>Last Online</th>
                            <td class="font-weight-bold text-danger">{{$server->offline_since->format("d/m/Y \\a\\t h:ia")}}</td>
                        </tr>
                        @else
                        <tr>
                            <th>Version</th>
                            <td>{{$server->version_string}}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Current Status</th>
                            <td class="text-{{$server->is_online ? 'success':'danger'}} font-weight-bold">{{$server->is_online?"Online":"Offline"}}</td>
                        </tr>
                        <tr>
                            <th>Players</th>
                            <td>{{$server->online_players ?? 0}} / {{$server->max_players ?? 0}}</td>
                        </tr>
                        <tr>
                            <th>Votes in {{date("F")}}</th>
                            <td>10</td>
                        </tr>
                        <tr>
                            <th>Votes all time</th>
                            <td>10</td>
                        </tr>
                        <tr>
                            <th>Owner</th>
                            <td>{{$server->owner->username}}
                        </tr>
                        <tr>
                            <th>Added</th>
                            <td>{{$server->created_at->format("d/m/Y \\a\\t h:ia")}}</td>
                        </tr>
                        <tr>
                            <th>Last pinged</th>
                            <td>{{$server->last_pinged->diffForHumans()}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">Tags</div>
                <div class="card-body">
                    @foreach($server->tags as $t)
                    <span class="badge badge-secondary">{{$t->name}}</span>
                    @endforeach
                </td>
            </div>
        </div>
    </div>
</div>
@endsection

@section("inline-script")
<script>
    $(document).ready(function(){
        $(".copy-ip-btn").click(function(){
            var ip = "{{$server->full_ip}}";
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(ip).select();
            document.execCommand("copy");
            $temp.remove();

            $(this).text("Copied");
            setTimeout(function(){
                $(".copy-ip-btn").text("Copy IP");
            }, 2500);

        });
    });
</script>
@endsection