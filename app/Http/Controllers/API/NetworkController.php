<?php

namespace App\Http\Controllers\API;

use App\Network;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Network as NetworkResource;

class NetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::find($request->uid);
        $team = $user->currentTeam();

        if ($request->get('sort')) {
            list($sortCol, $sortDir) = explode('|', $request->get('sort'));
            $query = Network::with('team')->orderBy($sortCol, $sortDir);
        } else {
            $query = Network::with('team')->orderBy('networks.name', 'asc');
        }

        if ($request->exists('filter')) {
            $query->where(function($q) use($request) {
                $value = "%{$request->filter}%";
                $q->where('networks.name', 'like', $value)
                    ->orWhere('users.name', 'like', $value);
            });
        }

        $perPage = $request->has('per_page') ? (int) $request->per_page : null;

        $query->where('networks.team_id', $team->id);

        $data = $query
            ->withCount('users')
            ->withCount('nurseries')
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
     * @param  \App\Network  $network
     * @return \Illuminate\Http\Response
     */
    public function show(Network $network)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Network  $network
     * @return \Illuminate\Http\Response
     */
    public function destroy(Network $network)
    {
        $network->delete();

        return response()->json([
            'status'    => 'Network deleted',
            'redirect'  => route('networks.index')
        ]);
    }
}
