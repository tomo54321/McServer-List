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


    <div class="card mt-5">
        <div class="card-header">Vote for {{$server->name}}</div>
        <div class="card-body">
            <form action="{{route("server.cast", ["server"=>$server->id])}}" method="POST">
                @csrf

                <div class="form-group">
                    {!! ReCaptcha::htmlFormSnippet() !!}
                    @error("g-recaptcha-response")
                        <div class="invalid-feedback d-block">{{$message}}</div>
                    @enderror
                </div>

                @if($server->votifier_enabled)
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" />
                    </div>
                @endif

                <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Cast Vote" />
                </div>

            </form>
        </div>
    </div>

</div>
@endsection