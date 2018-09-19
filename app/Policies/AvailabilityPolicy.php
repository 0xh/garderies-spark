<?php

namespace App\Policies;

use App\User;
use App\Availability;
use Illuminate\Auth\Access\HandlesAuthorization;

class AvailabilityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the availability.
     *
     * @param  \App\User  $user
     * @param  \App\Availability  $availability
     * @return mixed
     */
    public function view(User $user, Availability $availability)
    {
        //
    }

    /**
     * Determine whether the user can create availabilities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the availability.
     *
     * @param  \App\User  $user
     * @param  \App\Availability  $availability
     * @return mixed
     */
    public function update(User $user, Availability $availability)
    {
        //
    }

    /**
     * Determine whether the user can delete the availability.
     *
     * @param  \App\User  $user
     * @param  \App\Availability  $availability
     * @return mixed
     */
    public function delete(User $user, Availability $availability)
    {
        //
    }

    /**
     * Determine whether the user can restore the availability.
     *
     * @param  \App\User  $user
     * @param  \App\Availability  $availability
     * @return mixed
     */
    public function restore(User $user, Availability $availability)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the availability.
     *
     * @param  \App\User  $user
     * @param  \App\Availability  $availability
     * @return mixed
     */
    public function forceDelete(User $user, Availability $availability)
    {
        //
    }
}
