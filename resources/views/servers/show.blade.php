@extends('layouts.app',[
    "pagename"=>$server->name
])

@section('content')
<div class="container">

    @if($server->has_header)
        <span class="d-block server-view-header" style="background-image: url({{asset("storage/".$path."/".$server->header_path)}});"></span>
    @endif
    
    <div class="row my-4">
        <div class="col-12 col-md-6">

            <div class="d-flex align-items-center w-100">
                @if($server->has_icon)
                <img src="{{asset("storage/".$path."/".$server->icon_path)}}" alt="{{$server->name}}" class="mr-2"/>
                @endif
                <h1>{{$server->name}}</h1>
            </div>

        </div>
        <div class="col-12 col-md-6 mt-3 mt-md-0 text-center text-md-right">
            @auth
                @if($server->user_id == Auth::user()->id)
                <div class="d-inline border-right mr-2 pr-1">
                    <a href="{{route('analytics.basic', ['server'=>$server->id])}}" class="btn btn-dark">Analytics</a>
                    <a href="{{route('server.edit', ['server'=>$server->id])}}" class="btn btn-primary">Edit</a>
                </div>
                @endif
            @endauth
            <a href="#" data-toggle="modal" data-target="#shareModal" class="btn btn-dark">Share</a>
            @if($server->website)
                <a href="#" data-toggle="modal" data-target="#websiteWarning" class="btn btn-primary">Website</a>
            @endif
            @if($server->discord)
                <a href="https://discord.gg/{{ $server->discord }}" target="_blank" rel="noreferrer" class="btn btn-discord">Discord</a>
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

    @if(\Session::has("success"))
        <div class="alert alert-success">{{\Session::get("success")}}</div>
    @endif

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
                        @if($server->country)
                        <tr>
                            <th>Country</th>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('img/flags/' . $server->country . '.png') }}" alt="{{ \Countries::getOne($server->country, 'en') }}" /> 
                                    <span class="pl-2">{{ \Countries::getOne($server->country, 'en') }}</span>
                                </div>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>Votes in {{date("F")}}</th>
                            <td>{{number_format($month_votes, 0)}}</td>
                        </tr>
                        <tr>
                            <th>Votes all time</th>
                            <td>{{number_format($alltime_votes, 0)}}</td>
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


<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share {{$server->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Share {{$server->name}} using the following link:</p>
                <input type="text" disabled readonly class="form-control" value="{{URL()->current()}}" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@if($server->website)
<div class="modal fade" id="websiteWarning" tabindex="-1" role="dialog" aria-labelledby="websiteWarningLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="websiteWarningLabel">External Website Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Warning, you are about to leave {{config("app.name")}} and visit a 3rd party website.</p>
                <p>We have no control over the the website you are about to visit, including the content</p>
                <p>You are going to: <b>{{$server->website}}</b></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Stay Here</button>
                <a href="http://{{$server->website}}" class="btn">Go</a>
            </div>
        </div>
    </div>
</div>
@endif

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

            $.get("/api/server/{{$server->id}}/copy");

            $(this).text("Copied");
            setTimeout(function(){
                $(".copy-ip-btn").text("Copy IP");
            }, 2500);

        });
    });
</script>
@endsection