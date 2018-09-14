<?php

namespace App\Http\Controllers;

use App\Availability;
use App\Booking;
use App\BookingRequest;
use App\Charts\BookingsChart;
use App\Nursery;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['hasTeam']);
    }

    public function index()
    {
        $user = \auth()->user();
    }

    public function dashboard()
    {
        $authUser       = Auth::user();
        $team           = ($authUser) ? $authUser->currentTeam() : null;

        if ($team && $authUser->roleOn($team) == 'owner') {

            $count_nursery      = $team->nurseries->count();
            $count_user         = $team->users->where('id', '!=', $authUser->id)->count();
            $count_booking      = $team->bookings()->whereMonth('start', date('m'))->count();
            $count_booking_req  = $team->bookingRequests()->whereMonth('start', date('m'))->count();
            $bookingsChart      = new BookingsChart();

            return view('home', [
                'count_nursery' => $count_nursery,
                'count_user'    => $count_user,
                'count_booking' => $count_booking,
                'count_booking_req' => $count_booking_req,
                'chartBookings' => $bookingsChart,
                'team'          => $team
            ]);

        } else {

            $availabilities     = $authUser->availabilities()->where('start', '>', now())->get();
            $bookings           = $authUser->bookings()->where('start', '>', now())->get();
            $bookingRequests    = $authUser->bookingRequests()->where('start', '>', now())->get();
            $favorites          = $authUser->favorite_substitutes;

            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

            return view('home-user', [
                'user'              => $authUser,
                'bookings'          => $bookings,
                'bookingRequests'   => $bookingRequests,
                'availabilities'    => $availabilities,
                'favorites'         => $favorites,
                'months'            => $months
            ]);

        }
    }
}
