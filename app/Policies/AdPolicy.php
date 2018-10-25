<?php

namespace App\Policies;

use App\User;
use App\Ad;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) { return true; }
    }

    /**
     * Determine whether the user can view the ad.
     *
     * @param  \App\User  $user
     * @param  \App\Ad  $ad
     * @return mixed
     */
    public function view(User $user, Ad $ad)
    {
        //
    }

    /**
     * Determine whether the user can create ads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->roleOnCurrentTeam() == 'owner' || $user->roleOnCurrentTeam() == 'director') {
            return true;
        }
    }

    /**
     * Determine whether the user can update the ad.
     *
     * @param  \App\User  $user
     * @param  \App\Ad  $ad
     * @return mixed
     */
    public function update(User $user, Ad $ad)
    {
        //
    }

    /**
     * Determine whether the user can delete the ad.
     *
     * @param  \App\User  $user
     * @param  \App\Ad  $ad
     * @return mixed
     */
    public function delete(User $user, Ad $ad)
    {
        //
    }

    /**
     * Determine whether the user can restore the ad.
     *
     * @param  \App\User  $user
     * @param  \App\Ad  $ad
     * @return mixed
     */
    public function restore(User $user, Ad $ad)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the ad.
     *
     * @param  \App\User  $user
     * @param  \App\Ad  $ad
     * @return mixed
     */
    public function forceDelete(User $user, Ad $ad)
    {
        //
    }
}
