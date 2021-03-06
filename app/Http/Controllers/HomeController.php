<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingRequest;
use App\Charts\BookingsChart;
use App\Team;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['hasTeam']);
    }

    public function bookingReport()
    {
        $owners = User::leftJoin('team_users', 'team_users.user_id', '=', 'users.id')
            ->distinct('team_id')
            ->where('role', 'owner')
            ->orWhere('role', 'director')
            ->get();

        /*foreach ($owners as $owner) {
            $team = Team::find($owner->team_id);

            $monthlyBookings = $team->bookings()
                ->whereMonth('bookings.created_at', 10)
                ->whereYear('bookings.created_at', 2018)
                ->count();

            $data = [
                'team' => $team->id,
                'bookings' => $monthlyBookings
            ];


        }*/

        $team = Team::find(3);
        $data = $team->availabilities;

        return '<pre>' . print_r($data, true) . '</pre>';

    }

    public function index()
    {
        $authUser       = Auth::user();
        $team           = ($authUser) ? $authUser->currentTeam() : null;

        // Owner view
        if ($team && $authUser->roleOn($team) == 'owner') {

            $nurseries          = $team->nurseries()->orderBy('created_at', 'desc')->get();
            $count_nursery      = $nurseries->count();
            $count_user         = $team->users()->where('id', '!=', $authUser->id)->whereNotIn('role', ['director', 'owner'])->count();
            $count_booking      = $team->bookings()->whereMonth('start', date('m'))->count();
            $count_booking_req  = $team->bookingRequests()->whereMonth('start', date('m'))->count();
            $bookingsChart      = new BookingsChart();

            $data = [
                'nurseries'     => $nurseries,
                'count_nursery' => $count_nursery,
                'count_user'    => $count_user,
                'count_booking' => $count_booking,
                'count_booking_req' => $count_booking_req,
                'chartBookings' => $bookingsChart,
                'team'          => $team
            ];

            return view('home', $data);

        }
        // Director view
        elseif ($team && $authUser->roleOn($team) == 'director') {
            $nurseries          = $team->nurseries()->orderBy('created_at', 'desc')->get();
            $count_nursery      = $nurseries->count();
            $count_user         = $team->users()
                ->where('id', '!=', $authUser->id)
                ->where('role', '=', 'substitute')
                ->count();
            $bookings           = $team->bookings()
                ->whereMonth('start', date('m'))
                ->where('start', '>', now())
                ->where('status', Booking::STATUS_APPROVED)
                ->get();
            $count_booking      = $bookings->count();
            $booking_requests   = $team->bookingRequests()
                ->where('start', '>', now())
                ->where('status', BookingRequest::STATUS_PENDING)
                ->get();
            $count_booking_req  = $booking_requests->count();
            $bookingsChart      = new BookingsChart();
            $birthdays_limit    = now()->addMonth(1);
            $birthdays          = $team->users()
                ->whereMonth('birthdate', '=', now()->month)
                ->orWhereMonth('birthdate', '=', $birthdays_limit->month)
                ->where('team_id', $team->id)
                ->get();

            return view('home-director', [
                'nurseries'             => $nurseries,
                'count_nursery'         => $count_nursery,
                'count_user'            => $count_user,
                'count_booking'         => $count_booking,
                'count_booking_req'     => $count_booking_req,
                'chartBookings'         => $bookingsChart,
                'team'                  => $team,
                'bookings'              => $bookings,
                'bookingRequests'       => $booking_requests,
                'birthdays'             => $birthdays
            ]);
        }
        // Substitute view
        else {

            $availabilities     = $authUser->availabilities()
                ->where('start', '>', now())
                ->orderBy('start')->get();
            $bookings           = $authUser->bookings()
                ->where('start', '>', now())
                ->where('status', Booking::STATUS_APPROVED)
                ->orderBy('start')->get();
            $ownBookings        = $authUser->ownBookings()
                ->where('start', '>', now())
                ->where('status', Booking::STATUS_APPROVED)
                ->orderBy('start')->get();
            $bookingRequests    = $authUser->bookingRequests()
                ->where('start', '>', now())
                ->where('status', BookingRequest::STATUS_PENDING)
                ->orderBy('start')->get();
            $favorites          = $authUser->favorite_substitutes;

            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

            $contactPreferences = ($authUser->contact_preferences) ? $authUser->contact_preferences : [];
            $contactPreferencesLabels = [
                'sms'   => ['label' => 'SMS', 'icon' => 'fas fa-comments'],
                'email'   => ['label' => 'E-mail', 'icon' => 'fas fa-envelope'],
                'phone'   => ['label' => 'Téléphone', 'icon' => 'fas fa-phone'],
            ];

            return view('home-user', [
                'user'              => $authUser,
                'bookings'          => $bookings,
                'ownBookings'       => $ownBookings,
                'bookingRequests'   => $bookingRequests,
                'availabilities'    => $availabilities,
                'favorites'         => $favorites,
                'months'            => $months,
                'contactPreferences'   => $contactPreferences,
                'contactPreferencesLabels' => $contactPreferencesLabels,
            ]);
        }
    }

}
