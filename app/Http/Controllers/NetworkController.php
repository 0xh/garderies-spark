<?php

namespace App\Http\Controllers;

use App\Network;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Auth;

class NetworkController extends Controller
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
        $this->authorize('index', 'App\Network');

        $user       = \auth()->user();
        $id         = encrypt($user->id);
        $api_url    = '/api/networks?id=' . $id;

        return view('network.index', [
            'api_url'   => $api_url,
            'user'      => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', 'App\Network');

        $user = \auth()->user();
        $team = $user->currentTeam();

        $networks = $team->networks->count();
        $can_create = ($user->onGenericTrial() && $networks >= 1) ? false : true;

        return view('network.create', ['can_create' => $can_create]);
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

        // if no color is defined, choose one randomly
        if ($request->color) {
            $color = strtolower($request->color);
        } else {
            $color = array_random(config('network.colors'));
        }

        $user   = \auth()->user();
        $team   = $user->currentTeam();

        if ($team) {
            $network = new Network();
            $network->name      = $request->name;
            $network->color     = $color;
            $network->team_id   = $team->id;
            $network->save();
        }

        return redirect()->route('networks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Network  $network
     * @return \Illuminate\Http\Response
     */
    public function show(Network $network)
    {
        $this->authorize('view', $network);

        $ads            = $network->ads;
        $availabilities = $network->availabilities;

        $user                   = \auth()->user();
        $id                     = encrypt($user->id);
        $users_api_url          = '/api/users?nursery=0&network=' . $network->id . '&id=' . $id;
        $nurseries_api_url      = '/api/nurseries?network=' . $network->id . '&id=' . $id;

        return view('network.show', [
            'network'               => $network,
            'ads'                   => $ads,
            'availabilities'        => $availabilities,
            'users_api_url'         => $users_api_url,
            'nurseries_api_url'     => $nurseries_api_url
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Network  $network
     * @return \Illuminate\Http\Response
     */
    public function edit(Network $network)
    {
        $this->authorize('update', $network);

        return view('network.edit', ['network' => $network]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Network  $network
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Network $network)
    {
        // validate the request
        $request->validate([
            'name' => 'required',
        ]);
        
        $network->name      = $request->name;
        $network->color     = strtolower($request->color);
        $network->save();

        return redirect()->route('networks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Network  $network
     * @return \Illuminate\Http\Response
     */
    public function destroy(Network $network)
    {
        //
    }

    public function ads(Network $network)
    {
        $ads = $network->ads()->orderBy('created_at', 'desc')->get();

        return view('network.ads', [
            'network'   => $network,
            'ads'       => $ads
        ]);
    }
}
