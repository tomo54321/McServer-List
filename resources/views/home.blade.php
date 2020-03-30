@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Servers</h1>

    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-12 col-md-5 col-lg-6 col-xl-7">
                    <form action="" method="GET">
                        <div class="d-flex">
                            <input type="search" class="form-control" required placeholder="Server Name" />
                            <input type="submit" class="btn btn-primary btn-sm ml-1" value="Search" />
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-7 col-lg-6 col-xl-5 d-flex flex-wrap">
                    <div class="version_dropdown">
                        <button class="btn dropdown-toggle" type="button" id="version_dropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sort By Version
                        </button>
                        <div class="dropdown-menu" aria-labelledby="version_dropdownButton">
                            <a class="dropdown-item" href="#">Any</a>
                            <a class="dropdown-item" href="#">1.15</a>
                            <a class="dropdown-item" href="#">1.14</a>
                        </div>
                    </div>

                    <div class="category_dropdown">
                        <button class="btn dropdown-toggle" type="button" id="category_dropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sort By Category
                        </button>
                        <div class="dropdown-menu" aria-labelledby="category_dropdownButton">
                            <a class="dropdown-item" href="#">Any</a>
                            <a class="dropdown-item" href="#">Mini Games</a>
                            <a class="dropdown-item" href="#">Spleef</a>
                            <a class="dropdown-item" href="#">SG</a>
                        </div>
                    </div>

                    <div class="sortby_dropdown">
                        <button class="btn dropdown-toggle" type="button" id="sortby_dropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sort By
                        </button>
                        <div class="dropdown-menu" aria-labelledby="sortby_dropdownButton">
                            <a class="dropdown-item" href="#">Any</a>
                            <a class="dropdown-item" href="#">Most Voted For</a>
                            <a class="dropdown-item" href="#">Most Players Online</a>
                            <a class="dropdown-item" href="#">Recently Updated</a>
                            <a class="dropdown-item" href="#">Recently Added</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row align-items-center mt-4 pb-4 border-bottom">
        <div class="col-2 text-center">
            <span class="h5 font-weight-bold">Hypixel</span>
        </div>
        <div class="col-7">

            <div class="server-ip-container">
                <a href="">
                    <img src="https://minecraftservers.org/banners/2218431430479118.gif" class="d-none d-md-block" alt="Hypixel" width="100%" height="auto" />
                </a>
                <div class="server-ip d-flex align-items-center">
                    <p class="flex-grow-1">mc.hypixel.net</p>
                    <span class="badge badge-dark d-none d-sm-inline mx-1">Version 1.15</span>
                    <button type="button" class="btn btn-sm">Copy IP</button>
                </div>
                <div class="tags-container d-none d-mb-block">
                    <span class="badge badge-success">100% Uptime</span>
                    <span class="badge badge-dark">Mini Games</span>
                    <span class="badge badge-dark">Walls</span>
                    <span class="badge badge-dark">SG</span>
                    <span class="badge badge-dark">Prison</span>
                </div>
            </div>

        </div>
        <div class="col-3 text-center">
            <p class="mb-0">10 / 1000</p>
            <small class="d-block mb-3">Players Online</small>
            <a href="" class="btn btn-success btn-sm d-none d-md-block">Vote</a>
        </div>
        <div class="col-12 d-block d-md-none mt-1">
            <a href="" class="btn btn-success btn-sm btn-block">Vote</a>
        </div>
    </div>

    <div class="row align-items-center mt-4 pb-4 border-bottom">
        <div class="col-2 text-center">
            <img src="https://cdn.minecraft-server-list.com/servericon/411920.png" alt="Hypixel"/>
        </div>
        <div class="col-7">

            <div class="server-ip-container">
                <a href="">
                    <img src="https://minecraftservers.org/banners/2218431430479118.gif" class="d-none d-md-block" alt="Hypixel" width="100%" height="auto" />
                </a>
                <div class="server-ip d-flex align-items-center">
                    <p class="flex-grow-1">mc.hypixel.net</p>
                    <span class="badge badge-dark d-none d-sm-inline mx-1">Version 1.15</span>
                    <button type="button" class="btn btn-sm">Copy IP</button>
                </div>
                <div class="tags-container d-none d-md-block">
                    <span class="badge badge-success">100% Uptime</span>
                    <span class="badge badge-dark">Mini Games</span>
                    <span class="badge badge-dark">Walls</span>
                    <span class="badge badge-dark">SG</span>
                    <span class="badge badge-dark">Prison</span>
                </div>
            </div>

        </div>
        <div class="col-3 text-center">
            <p class="mb-0">10 / 1000</p>
            <small class="d-block mb-3">Players Online</small>
            <a href="" class="btn btn-success btn-sm d-none d-md-block">Vote</a>
        </div>
        <div class="col-12 d-block d-md-none mt-1">
            <a href="" class="btn btn-success btn-sm btn-block">Vote</a>
        </div>
    </div>

</div>
@endsection
