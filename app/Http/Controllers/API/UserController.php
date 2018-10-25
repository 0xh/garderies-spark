<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // filters to restrict users to a nursery or network
        $nursery = $request->nursery;
        $network = $request->network;

        $user   = User::find($request->uid);
        $team   = $user->currentTeam();

        $users = $team->users()
            ->distinct()
            ->where('users.id', '!=', $user->id)
            ->leftJoin('nurseries', 'nurseries.id', '=', 'nursery_id')->with('nursery')
            ->leftJoin('network_user', 'network_user.user_id', '=', 'users.id')
            ->leftJoin('networks', 'networks.id', '=', 'network_user.network_id')->with('networks');

        if ($user->isSuperAdmin()) {
            $users = User::select('users.*')
                ->leftJoin('nurseries', 'nurseries.id', '=', 'nursery_id')->with('nursery')
                ->leftJoin('network_user', 'network_user.user_id', '=', 'users.id')
                ->leftJoin('networks', 'networks.id', '=', 'network_user.network_id')->with('networks');
        } else {
            // exclude roles from results
            $users->whereNotIn('role', ['director', 'owner']);
        }

        if ($nursery) {
            $users->where('nurseries.id', '=', $nursery);
        }
        
        if ($network) {
            $users->where('networks.id', '=', $network);
        }
        
        if ($request->exists('filter')) {
            $users->where(function($q) use($request) {
                $value = "%{$request->filter}%";
                $q->where('users.name', 'like', $value)
                    ->orWhere('users.email', 'like', $value)
                    ->orWhere('users.phone', 'like', $value)
                    ->orWhere('networks.name', 'like', $value)
                    ->orWhere('nurseries.name', 'like', $value);
            });
        }
    
        if ($request->get('sort')) {
            list($sortCol, $sortDir) = explode('|', $request->get('sort'));
            $users->orderBy($sortCol, $sortDir);
        } else {
            $users->orderBy('users.name');
        }
        
        $perPage    = $request->has('per_page') ? (int) $request->per_page : null;

        if ($request->noPagination) {
            $data = $users->get();
        } else {
            $data = $users->distinct()->paginate($perPage);
        }

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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->availabilities()->delete();
        $user->delete();

        return response()->json([
            'status'    => 'User deleted',
            'redirect'  => route('users.index')
        ]);
    }

    public function addToFavorites(Request $request)
    {
        $params     = $request->params;
        $user       = User::find($params['userId']);
        $substitute = $params['substituteId'];

        $toggle = $user->favorite_substitutes()->toggle($substitute);

        $attached = true;
        if (count($toggle['detached'])) { $attached = false; }

        return response()->json($attached);
    }
}
