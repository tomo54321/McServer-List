@extends('layouts.app',[
    "pagename"=>"Hypixel"
])

@section('content')
<div class="container">
    <span class="d-block server-view-header" style="background-image: url(https://external-preview.redd.it/XvQ_3WBjhUvJOLJ4EGIseU4Y98CVCNKknmdMXC-trpQ.png?auto=webp&s=9ab7e41689aded1c719afe014574d95e2d02df52);"></span>
    <div class="row my-4">
        <div class="col-6">
            <h1>Hypixel</h1>
        </div>
        <div class="col-6 text-right">
            <a href="" class="btn btn-dark mx-2">Share</a>
            <a href="" class="btn btn-primary mx-2">Website</a>
            <a href="" class="btn btn-success">Vote</a>
        </div>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Hypixel</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    MineSuperior is part of the next generation of networks. Designed to be for the players. Always constantly adding new ideas. Designed to be transparent. Designed for all.

                    Features:
                    - Factions
                    - Skyblock
                    - Survival
                    - Towny
                    - Prison
                    - Creative
                    - KitPVP

                    IP: play.minesuperior.com
                    Website. www.minesuperior.com
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">Video</div>
                <div class="card-body">
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/o77MzDQT1cg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card mt-3 mt-md-0">
                <div class="card-header">Details</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>Name</th>
                            <td>Hypixel</td>
                        </tr>
                        <tr>
                            <th>IP</th>
                            <td>
                                <div class="d-flex">
                                    <span class="flex-grow-1">mc.hypixel.net</span>
                                    <button class="btn btn-dark btn-sm">Copy IP</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Version</th>
                            <td>Java Edition 1.15</td>
                        </tr>
                        <tr>
                            <th>Current Status</th>
                            <td class="text-success font-weight-bold">Online</td>
                        </tr>
                        <tr>
                            <th>Players</th>
                            <td>10 / 100</td>
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
                            <th>Added</th>
                            <td>30/03/2020 at 2:59pm</td>
                        </tr>
                        <tr>
                            <th>Last updated</th>
                            <td>10 minutes ago</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">Tags</div>
                <div class="card-body">
                    <span class="badge badge-secondary">Mini Games</span>
                </td>
            </div>
        </div>
    </div>
</div>
@endsection
