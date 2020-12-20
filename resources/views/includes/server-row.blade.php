<div class="card my-3">
    <div class="card-body px-2 py-3">
        <div class="row align-items-center">
            <div class="col-4 col-sm-2 text-center">
                @if($srv->has_icon)
                <a href="{{route('server.show', ['server'=>$srv->id])}}">
                    <img src="{{asset("storage/".$srv->user_id."/".$srv->icon_path)}}" alt="{{$srv->name}}" />
                    <div class="mt-1 text-dark font-weight-bold small">{{$srv->name}}</div>
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