<?php

namespace App\Http\Controllers\API;

use App\Booking;
use App\Http\Resources\Nursery as NurseryResource;
use App\Nursery;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Booking as BookingResource;

class NurseryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user   = User::find($request->uid);
        $team   = $user->currentTeam();

        $network = $request->network;

        $query = $team->nurseries()
            ->leftJoin('networks', 'networks.id', 'nurseries.network_id')->with('network');

        // restrict query to a certain network
        if ($network) {
            $query->where('networks.id', '=', $network);
        }

        if ($request->get('sort')) {
            list($sortCol, $sortDir) = explode('|', $request->get('sort'));
            $query->orderBy($sortCol, $sortDir);
        } else {
            $query->orderBy('nurseries.name', 'asc');
        }
        
        if ($request->exists('filter')) {
            $query->where(function($q) use($request) {
                $value = "%{$request->filter}%";
                $q->where('nurseries.name', 'like', $value)
                ->orWhere('networks.name', 'like', $value);
            });
        }
    
        $perPage = $request->has('per_page') ? (int) $request->per_page : null;

        if ($team) {
            //$query->where('nurseries.team_id', $team->id);
        }

        $data = $query
            ->withCount('users')
            ->withCount('ads')
            ->paginate($perPage);

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nursery  $nursery
     * @return \Illuminate\Http\Response
     */
    public function show(Nursery $nursery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nursery  $nursery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nursery $nursery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nursery  $nursery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nursery $nursery)
    {
        $nursery->delete();

        return response()->json([
            'status'    => 'Nursery deleted',
            'redirect'  => route('nurseries.index')
        ]);
    }

    public function planning(Request $request)
    {
        $nursery_ID = $request->nursery;
        $date_start = Carbon::parse($request->start);
        $date_end   = Carbon::parse($request->end);

        $bookings = Booking::where('nursery_id', $nursery_ID)
            ->where('start', '>=', $date_start)
            ->where('end', '<=', $date_end)
            ->with('user')
            ->with('substitute')
            ->with('request')
            ->get();

        $bookings = BookingResource::collection($bookings);

        return response()->json($bookings);
    }
}
