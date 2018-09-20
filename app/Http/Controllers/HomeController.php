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
        $authUser       = Auth::user();
        $team           = ($authUser) ? $authUser->currentTeam() : null;

        // Owner view
        if ($team && $authUser->roleOn($team) == 'owner') {

            $nurseries          = $team->nurseries()->orderBy('created_at', 'desc')->get();
            $count_nursery      = $nurseries->count();
            $count_user         = $team->users->where('id', '!=', $authUser->id)->count();
            $count_booking      = $team->bookings()->whereMonth('start', date('m'))->count();
            $count_booking_req  = $team->bookingRequests()->whereMonth('start', date('m'))->count();
            $bookingsChart      = new BookingsChart();

            return view('home', [
                'nurseries'     => $nurseries,
                'count_nursery' => $count_nursery,
                'count_user'    => $count_user,
                'count_booking' => $count_booking,
                'count_booking_req' => $count_booking_req,
                'chartBookings' => $bookingsChart,
                'team'          => $team
            ]);

        }
        // Director view
        elseif ($team && $authUser->roleOn($team) == 'director') {
            $nurseries          = $team->nurseries()->orderBy('created_at', 'desc')->get();
            $count_nursery      = $nurseries->count();
            $count_user         = $team->users->where('id', '!=', $authUser->id)->count();
            $bookings           = $team->bookings()->whereMonth('start', date('m'))->where('start', '>', now())->get();
            $count_booking      = $bookings->count();
            $booking_requests   = $team->bookingRequests()->where('start', '>', now())->get();
            $count_booking_req  = $booking_requests->count();
            $bookingsChart      = new BookingsChart();

            return view('home-director', [
                'nurseries'             => $nurseries,
                'count_nursery'         => $count_nursery,
                'count_user'            => $count_user,
                'count_booking'         => $count_booking,
                'count_booking_req'     => $count_booking_req,
                'chartBookings'         => $bookingsChart,
                'team'                  => $team,
                'bookings'              => $bookings,
                'bookingRequests'       => $booking_requests
            ]);
        }
        // Substitute view
        else {

            $availabilities     = $authUser->availabilities()
                ->where('start', '>', now())->get();
            $bookings           = $authUser->bookings()
                ->where('start', '>', now())
                ->where('status', Booking::STATUS_APPROVED)->get();
            $bookingRequests    = $authUser->bookingRequests()
                ->where('start', '>', now())
                ->where('status', BookingRequest::STATUS_PENDING)->get();
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
