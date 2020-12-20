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
    <div id="servers-list">

        @foreach($servers as $srv)

        @include("includes.server-row", ['srv' => $srv])

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

    <div id="server-list">

        @if($servers->count() < 1) <div class="card mt-5">
            <div class="card-body text-center">
                <h2>No Servers Found</h2>
                <p>We couldn't find any servers, please try resetting your filters and trying again.</p>
            </div>
    </div>
    @endif

    @foreach($servers as $srv)

    @include("includes.server-row", ['srv' => $srv])

    @endforeach

</div>

<div class="card mt-3 mb-5">
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