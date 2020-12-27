<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProxyController extends Controller
{
    
    /**
     * Get a random server json for the proxy
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function random(){

        $select = ["id", DB::raw("ip as 'host'"), "port"];

        $featured_servers = Server::select($select)
            ->whereNotNull("featured_until")
            ->get();

        $random_servers = Server::select($select)
            ->inRandomOrder()
            ->take(30)
            ->get();

        $servers = Cache::remember("proxy_random_servers", Carbon::now()->addDays(7), function() use($featured_servers, $random_servers){
            $srv = $featured_servers->merge($random_servers);
            return $srv->toArray();
        });


        return response()->json($servers);

    }

}
