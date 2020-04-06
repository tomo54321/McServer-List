@extends('layouts.app')

@section('content')
<div class="container">
    @if($promoted->count() == 0)
    <h1>Server List</h1>
    @if(!is_null($query))
    <p class="text-muted">Based off the search term {{$query}}</p>
    @endif
    @endif

    @if(\Session::has("success"))
    <div class="alert alert-success"><strong>Success</strong> {{\Session::get("success")}}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger"><strong>Error</strong> {{$errors->first()}}</div>
    @endif

    @if($promoted->count() > 0)
    <h1>Featured Servers</h1>
    <div id="servers-list" class="container-fluid">

        @foreach($servers as $srv)

        <div class="card my-3">
            <div class="card-body px-2 py-3">
                <div class="row align-items-center">
                    <div class="col-4 col-sm-2 text-center">
                        @if($srv->has_icon)
                        <a href="{{route('server.show', ['server'=>$srv->id])}}">
                            <img src="{{asset("storage/".$srv->user_id."/".$srv->icon_path)}}" alt="{{$srv->name}}" />
                        </a>
                        @else
                        <a href="{{route('server.show', ['server'=>$srv->id])}}"
                            class="h5 text-dark font-weight-bold">{{$srv->name}}</a>
                        @endif
                    </div>
                    <div class="col-8 col-sm-7">

                        <div class="server-ip-container">
                            <a href="{{route('server.show', ['server'=>$srv->id])}}">
                                <img src="{{$srv->has_banner ? asset("storage/".$srv->user_id."/".$srv->banner_path) : "https://placehold.it/468x60"}}"
                                    class="d-none d-md-block" alt="{{$srv->name}}" width="100%" height="auto" />
                            </a>
                            <div class="server-ip d-flex align-items-center ip-copy-btn" data-ip="{{$srv->full_ip}}"
                                data-server="{{$srv->id}}">
                                <p class="flex-grow-1 text-truncate">{{$srv->full_ip}}</p>
                                <span class="badge badge-dark d-none d-sm-inline mx-1">{{$srv->version_string}}</span>
                                <button type="button" class="btn btn-sm">Copy
                                    IP</button>
                            </div>
                        </div>

                    </div>
                    <div class="d-none d-sm-block col-3 text-center">
                        <p class="mb-0">{{$srv->online_players}} / {{$srv->max_players}}</p>
                        <small class="d-block mb-3">Players Online</small>
                        <a href="{{route('server.show', ['server'=>$srv->id])}}"
                            class="btn btn-success btn-sm d-none d-md-block">Vote</a>
                    </div>
                    <div class="col-12 d-block d-md-none mt-1">
                        <a href="{{route('server.show', ['server'=>$srv->id])}}"
                            class="btn btn-success btn-sm btn-block">Vote</a>
                    </div>
                </div>
            </div>
        </div>

        @endforeach

    </div>

    <h1 class="mt-5">Server List</h1>
    @if(!is_null($query))
    <p class="text-muted">Based off the search term {{$query}}</p>
    @endif

    @endif


    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-12 col-md-5 col-lg-6 col-xl-7">
                    <form action="" method="GET">
                        <div class="d-flex">
                            <input type="search" name="query" class="form-control @error('query')is-invalid @enderror"
                                value="{{$query}}" placeholder="Server Name" />
                            <input type="submit" class="btn btn-primary btn-sm ml-1" value="Search" />
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-7 col-lg-6 col-xl-5 d-flex flex-wrap">
                    <div class="category_dropdown">
                        <button class="btn dropdown-toggle" type="button" id="category_dropdownButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sort By Category
                        </button>
                        <div class="dropdown-menu dropdown-menu-scrollable" aria-labelledby="category_dropdownButton">
                            <a class="dropdown-item"
                                href="{{request()->fullUrlWithQuery(['category' => null])}}">Any</a>
                            @foreach($tags as $t)
                            <a class="dropdown-item"
                                href="{{request()->fullUrlWithQuery(['category' => $t->id])}}">{{$t->name}}</a>
                            @endforeach
                        </div>
                    </div>

                    <div class="sortby_dropdown">
                        <button class="btn dropdown-toggle" type="button" id="sortby_dropdownButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sort By
                        </button>
                        <div class="dropdown-menu" aria-labelledby="sortby_dropdownButton">
                            <a class="dropdown-item" href="{{request()->fullUrlWithQuery(['sortby' => null])}}">Any</a>
                            <a class="dropdown-item" href="{{request()->fullUrlWithQuery(['sortby' => 'votes'])}}">Most
                                Voted For</a>
                            <a class="dropdown-item"
                                href="{{request()->fullUrlWithQuery(['sortby' => 'players'])}}">Most Players Online</a>
                            <a class="dropdown-item"
                                href="{{request()->fullUrlWithQuery(['sortby' => 'updated'])}}">Recently Updated</a>
                            <a class="dropdown-item"
                                href="{{request()->fullUrlWithQuery(['sortby' => 'added'])}}">Recently Added</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="server-list" class="container-fluid">

        @if($servers->count() < 1) <div class="card mt-5">
            <div class="card-body text-center">
                <h2>No Servers Found</h2>
                <p>We couldn't find any servers, please try resetting your filters and trying again.</p>
            </div>
    </div>
    @endif

    @foreach($servers as $srv)

    <div class="card my-3">
        <div class="card-body px-2 py-3">
            <div class="row align-items-center">
                <div class="col-4 col-sm-2 text-center">
                    @if($srv->has_icon)
                    <a href="{{route('server.show', ['server'=>$srv->id])}}">
                        <img src="{{asset("storage/".$srv->user_id."/".$srv->icon_path)}}" alt="{{$srv->name}}" />
                    </a>
                    @else
                    <a href="{{route('server.show', ['server'=>$srv->id])}}"
                        class="h5 text-dark font-weight-bold">{{$srv->name}}</a>
                    @endif
                </div>
                <div class="col-8 col-sm-7">

                    <div class="server-ip-container">
                        <a href="{{route('server.show', ['server'=>$srv->id])}}">
                            <img src="{{$srv->has_banner ? asset("storage/".$srv->user_id."/".$srv->banner_path) : "https://placehold.it/468x60"}}"
                                class="d-none d-md-block" alt="{{$srv->name}}" width="100%" height="auto" />
                        </a>
                        <div class="server-ip d-flex align-items-center ip-copy-btn" data-ip="{{$srv->full_ip}}"
                            data-server="{{$srv->id}}">
                            <p class="flex-grow-1 text-truncate">{{$srv->full_ip}}</p>
                            <span class="badge badge-dark d-none d-sm-inline mx-1">{{$srv->version_string}}</span>
                            <button type="button" class="btn btn-sm">Copy
                                IP</button>
                        </div>
                        <div class="tags-container d-none d-md-block">
                            @foreach($srv->tags as $tg)
                            <span class="badge badge-dark">{{$tg->name}}</span>
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="d-none d-sm-block col-3 text-center">
                    <p class="mb-0">{{$srv->online_players}} / {{$srv->max_players}}</p>
                    <small class="d-block mb-3">Players Online</small>
                    <a href="{{route('server.show', ['server'=>$srv->id])}}"
                        class="btn btn-success btn-sm d-none d-md-block">Vote</a>
                </div>
                <div class="col-12 d-block d-md-none mt-1">
                    <a href="{{route('server.show', ['server'=>$srv->id])}}"
                        class="btn btn-success btn-sm btn-block">Vote</a>
                </div>
            </div>
        </div>
    </div>

    @endforeach

</div>

<div class="card my-5">
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                {{$servers->links()}}
            </div>
            <div class="col-6 text-right">
                <span class="text-muted">Page {{$servers->currentPage()}} of {{$servers->lastPage()}}</span>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@section("inline-script")
<script>
    $(document).ready(function(){
        $(".ip-copy-btn").click(function(){
            var ip = $(this).data("ip");
            var server = $(this).data("server");
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(ip).select();
            document.execCommand("copy");
            $temp.remove();

            $.get("/api/server/"+server+"/copy");

            $(this).children(".btn").text("Copied");
            var btn = $(this).children(".btn");
            setTimeout(function(){
                $(btn).text("Copy IP");
            }, 2500);

        });
    });
</script>
@endsection