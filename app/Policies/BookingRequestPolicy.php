<?php

namespace App\Policies;

use App\User;
use App\BookingRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingRequestPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
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
        if ($user->roleOnCurrentTeam() == 'owner' || $user->roleOnCurrentTeam() == 'director') {
            return true;
        }
    }

    /**
     * Determine whether the user can view the booking request.
     *
     * @param  \App\User  $user
     * @param  \App\BookingRequest  $bookingRequest
     * @return mixed
     */
    public function view(User $user, BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Determine whether the user can create booking requests.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the booking request.
     *
     * @param  \App\User  $user
     * @param  \App\BookingRequest  $bookingRequest
     * @return mixed
     */
    public function update(User $user, BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Determine whether the user can delete the booking request.
     *
     * @param  \App\User  $user
     * @param  \App\BookingRequest  $bookingRequest
     * @return mixed
     */
    public function delete(User $user, BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Determine whether the user can restore the booking request.
     *
     * @param  \App\User  $user
     * @param  \App\BookingRequest  $bookingRequest
     * @return mixed
     */
    public function restore(User $user, BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the booking request.
     *
     * @param  \App\User  $user
     * @param  \App\BookingRequest  $bookingRequest
     * @return mixed
     */
    public function forceDelete(User $user, BookingRequest $bookingRequest)
    {
        //
    }
}
