<?php

namespace App\Http\Controllers;

use App\Charts\BookingPurposesPerNursery;
use App\Charts\DiplomasPerNursery;
use App\Network;
use App\Nursery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NurseryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['subscribed', 'hasTeam']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('nursery.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', 'App\Nursery');

        $user       = auth()->user();
        $team       = $user->currentTeam();
        $networks   = $team->networks;
        return view('nursery.create', ['networks' => $networks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate the request
        $request->validate([
            'name' => 'required',
        ]);

        $user = auth()->user();
        $team = $user->currentTeam();

        if ($team) {
            $nursery = new Nursery();
            $nursery->name      = $request->name;
            $nursery->team_id   = $team->id;
            $nursery->address   = $request->address;
            $nursery->post_code = $request->post_code;
            $nursery->city      = $request->city;
            $nursery->email     = $request->email;
            $nursery->phone     = $request->phone;
            $nursery->network_id    = $request->network;
            $nursery->save();
        }

        return redirect()->route('nurseries.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nursery  $nursery
     * @return \Illuminate\Http\Response
     */
    public function show(Nursery $nursery)
    {
        $this->authorize('view', $nursery);

        // get bookings
        $bookings = $nursery->bookings;
        // get the DiplomasPerNursery Chart
        $diplomas_chart = new DiplomasPerNursery($nursery->id);
        // get the BookingPurposesPerNursery Chart
        $bookings_chart = new BookingPurposesPerNursery($nursery->id);

        return view('nursery.show', [
            'nursery'           => $nursery,
            'bookings'          => $bookings,
            'diplomas_chart'    => $diplomas_chart,
            'bookings_chart'    => $bookings_chart,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Nursery  $nursery
     * @return \Illuminate\Http\Response
     */
    public function edit(Nursery $nursery)
    {
        $this->authorize('update', $nursery);

        $user       = auth()->user();
        $team       = $user->currentTeam();
        $networks   = Network::where('team_id', $team->id)->get();
        return view('nursery.edit', ['nursery' => $nursery, 'networks' => $networks]);
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
        // validate the request
        $request->validate([
            'name' => 'required',
        ]);
        
        $nursery->name          = $request->name;
        $nursery->address       = $request->address;
        $nursery->post_code     = $request->post_code;
        $nursery->city          = $request->city;
        $nursery->email         = $request->email;
        $nursery->phone         = $request->phone;
        $nursery->network_id    = $request->network;
        $nursery->save();

        return redirect()->route('nurseries.show', $nursery)->with('status', 'Nursery mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nursery  $nursery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nursery $nursery)
    {
        // If there are no users bound to this nursersy, proceed
        if ($nursery->users->count() == 0) {
            // Delete nursery
            $nursery->delete();
        } else {
            // Retrieve the associated users
            $users = $nursery->users;

            // Loop through it
            foreach($users as $user) {
                $user->nursery_id = 0;
                $user->save();
            }

            // Delete nursery
            $nursery->delete();
        }

        return redirect()->route('nurseries.index');
    }

    public function planning(Nursery $nursery)
    {
        $this->authorize('planning', $nursery);

        // get bookings
        $bookings           = $nursery->bookings;
        // get the first day of the current month
        $first_day_month    = Carbon::now()->day(1)->format('d.m.Y');
        // get the last day of the current month
        $last_day_month     = Carbon::now()->lastOfMonth()->format('d.m.Y');

        return view('nursery.planning', [
            'nursery'           => $nursery,
            'bookings'          => $bookings,
            'first_day_month'   => $first_day_month,
            'last_day_month'    => $last_day_month,
        ]);
    }

    public function ads(Nursery $nursery)
    {
        $this->authorize('ads', $nursery);

        $ads = $nursery->ads()->orderBy('created_at', 'desc')->get();

        return view('nursery.ads', [
            'nursery' => $nursery,
            'ads'     => $ads
        ]);
    }
}
