@extends('layouts.app',[
    "pagename"=>"Vote for ".$server->name
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
            <a href="{{route("server.show", ["server"=>$server->id])}}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
          <li class="breadcrumb-item" aria-current="page"><a href="{{route("server.show", ["server"=>$server->id])}}">{{$server->name}}</a></li>
          <li class="breadcrumb-item active" aria-current="page">Vote</li>
        </ol>
    </nav>

    @error("vote")
        <div class="alert alert-danger"><strong>Error</strong> {{$message}}</div>
    @enderror

    <div class="row align-items-center mt-5">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">Vote for {{$server->name}}</div>
                <div class="card-body">
                    <p>You are able to vote every 24 hours.</p>
                    <form action="{{route("server.cast", ["server"=>$server->id])}}" method="POST">
                        @csrf
        
                        <div class="form-group">
                            <label for="username">Minecraft Username</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" required placeholder="Minecraft Username"/>
                            @error("username")
                            <div class="invalid-feedback" role="alert"><strong>{{$message}}</strong></div>
                            @enderror
                        </div>
        
                        <div class="form-group">
                            {!! ReCaptcha::htmlFormSnippet() !!}
                            @error("g-recaptcha-response")
                                <div class="invalid-feedback d-block">{{$message}}</div>
                            @enderror
                        </div>
        
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Cast Vote" />
                        </div>
        
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4>Why vote?</h4>
                    <p>There are a few reasons to vote for {{$server->name}}:</p>
                    <ul>
                        <li>It helps {{$server->name}} to rank better in our listings</li>
                        <li>It shows to the server owner that you enjoy their server</li>
                        @if($server->enabled_votifier)
                            <li>You could unlock in game items using Votifier</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection