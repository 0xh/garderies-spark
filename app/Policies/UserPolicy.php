<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) { return true; }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        if (($user->roleOnCurrentTeam() == 'owner' || $user->roleOnCurrentTeam() == 'director') && $model->onTeam($user->currentTeam())) {
            return true;
        }
        if ($user->id === $model->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        if ($user->roleOnCurrentTeam() == 'owner' || $user->roleOnCurrentTeam() == 'director') { return true; }
        if ($user->id === $model->id) { return true; }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }

    /**
     * Determine if the user can see another user's availabilities
     *
     * @param User $user
     * @param User $model
     */
    public function availabilities(User $user, User $model)
    {
        // if the user is an owner && the intended user is on the current team
        if (($user->roleOnCurrentTeam() == 'owner' || $user->roleOnCurrentTeam() == 'director') && $model->onTeam($user->currentTeam())) {
            return true;
        }
        // if the user is himself
        if ($user->id === $model->id) {
            return true;
        }

        return false;
    }
}
