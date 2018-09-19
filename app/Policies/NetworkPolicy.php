<?php

namespace App\Policies;

use App\User;
use App\Network;
use Illuminate\Auth\Access\HandlesAuthorization;

class NetworkPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->isSuperAdmin()) { return true; }
    }

    /**
     * Determine whether the user can view the index
     *
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        if ($user->roleOnCurrentTeam() == 'owner') {
            return true;
        }
    }

    /**
     * Determine whether the user can view the network.
     *
     * @param  \App\User  $user
     * @param  \App\Network  $network
     * @return mixed
     */
    public function view(User $user, Network $network)
    {
        if ($user->currentTeam()->id == $network->team->id && $user->roleOnCurrentTeam() == 'owner') {
            return true;
        }
    }

    /**
     * Determine whether the user can create networks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->onGenericTrial() && $user->currentTeam()->networks->count() >= 1) {
            return false;
        }

        if ($user->roleOnCurrentTeam() == 'owner') {
            return true;
        }
    }

    /**
     * Determine whether the user can update the network.
     *
     * @param  \App\User  $user
     * @param  \App\Network  $network
     * @return mixed
     */
    public function update(User $user, Network $network)
    {
        if ($user->currentTeam()->id == $network->team->id && $user->roleOnCurrentTeam() == 'owner') {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the network.
     *
     * @param  \App\User  $user
     * @param  \App\Network  $network
     * @return mixed
     */
    public function delete(User $user, Network $network)
    {
        //
    }

    /**
     * Determine whether the user can restore the network.
     *
     * @param  \App\User  $user
     * @param  \App\Network  $network
     * @return mixed
     */
    public function restore(User $user, Network $network)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the network.
     *
     * @param  \App\User  $user
     * @param  \App\Network  $network
     * @return mixed
     */
    public function forceDelete(User $user, Network $network)
    {
        //
    }
}
