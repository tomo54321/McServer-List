@extends('layouts.app',[
"pagename"=>"Analytics for ".$server->name
])

@section('content')
<div class="container">
    <div class="row align-items-center">
        <div class="col-6">
            <h1>Analytics for {{$server->name}}</h1>
            <p class="text-muted">Showing analytics for the past 7 days</p>
        </div>
        <div class="col-6 text-right">
            <a href="{{route('server.show', ['server'=>$server->id])}}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Vote performace</div>
        <div class="card-body">
            <canvas height="300px" width="100%" id="votes"></canvas>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">Peak players online performace</div>
        <div class="card-body">
            <canvas height="300px" width="100%" id="players"></canvas>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">IP Copies</div>
        <div class="card-body">
            <canvas height="300px" width="100%" id="ipcopies"></canvas>
        </div>
    </div>
</div>
@endsection

@section("inline-script")
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script>
var voteCTX = document.getElementById('votes').getContext('2d');
var playerCTX = document.getElementById('players').getContext('2d');
var ipCopiesCTX = document.getElementById('ipcopies').getContext('2d');
var chartOptions = {
    scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    },
    responsive:true,
    maintainAspectRatio: false
};
var chartDates = [@foreach($dates as $d) "{{$d->format('jS M')}}", @endforeach];

var votesChart = new Chart(voteCTX, {
    type: 'line',
    data: {
        labels: chartDates,
        datasets: [{
            label: '# of Votes',
            data: [@foreach($votes as $vote) {{ $vote }}, @endforeach],
            backgroundColor: [
                'rgba(101, 116, 205, 0.2)',
            ],
            borderColor: [
                '#6574cd',
            ],
            borderWidth: 2
        }]
    },
    options: chartOptions
});
var playersChart = new Chart(playerCTX, {
    type: 'line',
    data: {
        labels: chartDates,
        datasets: [{
            label: 'Peak number of players',
            data: [@foreach($players as $player) {{ $player }}, @endforeach],
            backgroundColor: [
                'rgba(149, 97, 226, 0.2)',
            ],
            borderColor: [
                '#9561e2',
            ],
            borderWidth: 2
        }]
    },
    options: chartOptions
});
var ipChart = new Chart(ipCopiesCTX, {
    type: 'line',
    data: {
        labels: chartDates,
        datasets: [{
            label: '# of IP Copies',
            data: [@foreach($ipcopies as $ipc) {{ $ipc }}, @endforeach],
            backgroundColor: [
                'rgba(246, 109, 155, 0.2)',
            ],
            borderColor: [
                '#f66d9b',
            ],
            borderWidth: 2
        }]
    },
    options: chartOptions
});
</script>
@endsection