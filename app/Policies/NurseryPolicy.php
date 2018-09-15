<?php

namespace App\Policies;

use App\User;
use App\Nursery;
use Illuminate\Auth\Access\HandlesAuthorization;

class NurseryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the nursery.
     *
     * @param  \App\User  $user
     * @param  \App\Nursery  $nursery
     * @return mixed
     */
    public function view(User $user, Nursery $nursery)
    {
        //
    }

    /**
     * Determine whether the user can create nurseries.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the nursery.
     *
     * @param  \App\User  $user
     * @param  \App\Nursery  $nursery
     * @return mixed
     */
    public function update(User $user, Nursery $nursery)
    {
        //
    }

    /**
     * Determine whether the user can delete the nursery.
     *
     * @param  \App\User  $user
     * @param  \App\Nursery  $nursery
     * @return mixed
     */
    public function delete(User $user, Nursery $nursery)
    {
        //
    }

    /**
     * Determine whether the user can restore the nursery.
     *
     * @param  \App\User  $user
     * @param  \App\Nursery  $nursery
     * @return mixed
     */
    public function restore(User $user, Nursery $nursery)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the nursery.
     *
     * @param  \App\User  $user
     * @param  \App\Nursery  $nursery
     * @return mixed
     */
    public function forceDelete(User $user, Nursery $nursery)
    {
        //
    }
}
