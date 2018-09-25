<?php

namespace App\Http\Controllers;

use App\Availability;
use App\Booking;
use App\BookingRequest;
use App\Charts\UserBookingsChart;
use App\Diploma;
use App\Network;
use App\Nursery;
use App\User;
use App\Workgroup;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'hasTeam']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $this->authorize('view', $user);

        // Get future availabilities
        $availabilities = $user->availabilities()
            ->where('start', '>=', now())
            ->orderBy('start')
            ->get();

        // Get approved future bookings
        $bookings = $user->bookings()
            ->with('nursery')
            ->where('start', '>=', now())
            ->where('status', '=', Booking::STATUS_APPROVED)
            ->orderBy('start')
            ->get();

        // Get archived bookings
        $archivedBookings = $user->bookings()
            ->with('nursery')
            ->where('status', '=', Booking::STATUS_ARCHIVED)
            ->orderBy('start')
            ->get();

        // Get pending future booking requests
        $bookingRequests = $user->bookingRequests()
            ->where('start', '>=', now())
            ->where('status', '=', BookingRequest::STATUS_PENDING)
            ->with('nursery')
            ->orderBy('start')
            ->get();

        // Get a user's own booking requests
        $userBookingRequests = $user->ownBookingRequests()
            ->where('start', '>=', now())
            ->with('nursery')
            ->orderBy('start')
            ->get();

        $chart = new UserBookingsChart($user->id);

        $authUser = \auth()->user();
        // check if the user being viewed is one of the user favorites
        $isFavorite = $authUser->favorite_substitutes->where('id', $user->id)->count();

        $contactPreferences = ($authUser->contact_preferences) ? $authUser->contact_preferences : [];
        $contactPreferencesLabels = [
            'sms'   => ['label' => 'SMS', 'icon' => 'fas fa-comments'],
            'email'   => ['label' => 'E-mail', 'icon' => 'fas fa-envelope'],
            'phone'   => ['label' => 'Téléphone', 'icon' => 'fas fa-phone'],
        ];

        return view('user.show', [
            'user'                  => $user,
            'availabilities'        => $availabilities,
            'bookings'              => $bookings,
            'archivedBookings'      => $archivedBookings,
            'bookingRequests'       => $bookingRequests,
            'userBookingRequests'   => $userBookingRequests,
            'chart'                 => $chart,
            'isFavorite'            => $isFavorite,
            'contactPreferences'    => $contactPreferences,
            'contactPreferencesLabels' => $contactPreferencesLabels
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $authUser   = \auth()->user();
        $team       = $authUser->currentTeam();

        $nurseries          = $team->nurseries()->orderBy('name', 'desc')->get();
        $managedNetworks    = $team->networks;
        $workgroups         = Workgroup::all();
        $diplomas           = Diploma::all();

        // current objects IDs
        $currentNetworksKeys    = array_flatten($user->networks->pluck('id'));
        $currentWorkgroups      = array_flatten($user->workgroups->pluck('id'));
        $contactPreferences     = ($user->contact_preferences) ? $user->contact_preferences : [];

        $contactPreferencesLabels = [
            'sms'   => ['label' => 'SMS', 'icon' => 'fas fa-comments'],
            'email'   => ['label' => 'E-mail', 'icon' => 'fas fa-envelope'],
            'phone'   => ['label' => 'Téléphone', 'icon' => 'fas fa-phone'],
        ];

        if ($authUser->isSuperAdmin() || $authUser->id == $user->id) {
            return view('user.edit', [
                'user'                  => $user,
                'nurseries'             => $nurseries,
                'managedNetworks'       => $managedNetworks,
                'currentNetworksKeys'   => $currentNetworksKeys,
                'workgroups'            => $workgroups,
                'currentWorkgroups'     => $currentWorkgroups,
                'diplomas'              => $diplomas,
                'contactPreferences'    => $contactPreferences,
                'contactPreferencesLabels' => $contactPreferencesLabels
            ]);
        } elseif ($authUser->roleOnCurrentTeam() == 'owner' || $authUser->roleOnCurrentTeam() == 'director') {
            return view('user.owner.edit', [
                'user'                  => $user,
                'nurseries'             => $nurseries,
                'managedNetworks'       => $managedNetworks,
                'currentNetworksKeys'   => $currentNetworksKeys,
                'workgroups'            => $workgroups,
                'currentWorkgroups'     => $currentWorkgroups,
                'diplomas'              => $diplomas,
            ]);
        }
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
        $authUser           = auth()->user();
        $team               = $authUser->currentTeam;

        if ($authUser->roleOnCurrentTeam() == 'owner' || $authUser->roleOnCurrentTeam() == 'director') {
            $user->nursery_id   = $request->nursery;
            $user->save();

            /**
             * Networks sync
             */
            $selectedNetworksRaw   = array_values($request->networks ?? []); // selected networks from the form
            $selectedNetworks = [];
            foreach ($selectedNetworksRaw as $network) { $selectedNetworks[] = intval($network); }

            $managedNetworks    = array_flatten($team->networks->pluck('id')); // current team's networks
            $currentNetworks    = array_flatten($user->networks->pluck('id')); // user's associated networks

            // compute which ids to store
            $network_ids = [];
            if (count($selectedNetworks)) {
                // merge the current networks with the selected one
                $network_ids = array_flatten(array_unique(array_merge($currentNetworks, $selectedNetworks)));
            } else {
                // remove managed networks from current networks
                foreach ($currentNetworks as $network) {
                    if (!in_array($network, $managedNetworks)) {
                        $network_ids[] = $network;
                    }
                }
            }
            // sync the networks
            $user->networks()->sync($network_ids);

            return redirect()->route('users.show', $user);

        } else {
            // validate the request
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
            ]);

            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->phone        = $request->phone;
            $user->nursery_id   = $request->nursery;
            $user->diploma_id   = $request->diploma;

            $user->billing_address  = $request->billing_address;
            $user->billing_state    = $request->billing_state;
            $user->billing_zip      = $request->billing_zip;
            $user->billing_city     = $request->billing_city;

            $user->contact_preferences = $request->contact_preferences;

            $user->save();

            // workgroups
            $workgroup_ids = [];
            if ($request->workgroups) {
                $workgroup_ids = array_values($request->workgroups);
            }
            $user->workgroups()->sync($workgroup_ids);

            return redirect('/');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }

    public function availabilities(User $user)
    {
        $this->authorize('availabilities', $user);

        // Define the current day to display
        $current_day = Carbon::now();
        if ($current_day->isSaturday()) { $current_day->addDays(2); }
        if ($current_day->isSunday()) { $current_day->addDays(1); }
        $current_day_formatted = $current_day->format('Y-m-d');

        // Opening and closing time
        $opening_time = config('nursery.opening_time_calendar');
        $closing_time = config('nursery.closing_time_calendar');

        return view('user.availabilities', [
            'user'          => $user,
            'current_day'   => $current_day_formatted,
            'opening_time'  => $opening_time,
            'closing_time'  => $closing_time
        ]);
    }

    public function bookings(User $user)
    {
        // Get archived bookings
        $archivedBookings = $user->bookings()
            ->with('nursery')
            ->where('status', '=', Booking::STATUS_ARCHIVED)
            ->orderBy('start')
            ->get();

        return view('user.bookings', [
            'user'               => $user,
            'archivedBookings'   => $archivedBookings
        ]);
    }

    /**
     * Look through the availabilities for a specific user and date
     * and return its free time
     *
     * @param User $user
     * @param Carbon $date
     * @return array
     */
    public static function getAvailableSlots(User $user, Carbon $date)
    {
        // get the opening and closing hours
        $day_start  = $date->copy()->hour(config('nursery.opening_time'))->minute(0);
        $day_end    = $date->copy()->hour(config('nursery.closing_time'))->minute(0);

        // get related bookings
        $availabilities = Availability::whereYear('start', $day_start->format('Y'))
            ->whereMonth('start', $day_start->format('m'))
            ->whereDay('start', $day_start->format('d'))
            ->where('user_id', $user->id)
            ->orderBy('start')
            ->get();

        // init
        $start      = $day_start->copy();
        $free_min   = $day_start->diffInMinutes($day_end);
        $slots      = [];

        // loop through the associated bookings
        foreach ($availabilities as $key => $availability) {

            // store each booking duration
            if ($availability->start->lt($day_start)) {
                $free_min -= $availability->end->diffInMinutes($day_start);
            } elseif ($availability->end->gt($day_end)) {
                $free_min -= $availability->start->diffInMinutes($day_end);
            } else {
                $free_min -= $availability->start->diffInMinutes($availability->end);
            }

            // If this is the last booking and it is exactly the same length, exit early
            if ($key == $availability->count() &&
                $availability->start->equaltTo($day_start) &&
                $availability->end->equaltTo($day_end)
            ) {
                break;
            }

            // register available slots
            if ($availability->start->equalTo($start)) {
                $start->addMinutes($availability->start->diffInMinutes($availability->end));
            } else {
                $slot_start = $start->copy();
                $slot_end   = $start->copy()->addMinutes($start->diffInMinutes($availability->start));
                $start      = $availability->end;

                $slots[] = ['start' => $slot_start, 'end' => $slot_end];
            }

            // if the last booking ends before the availability, retrieve the ending slot
            if ($key == $availabilities->count() - 1 && $availability->end->lt($day_end)) {
                $slot_start = $availability->end->copy();
                $slot_end   = $slot_start->copy()->addMinutes($availability->end->diffInMinutes($day_end));

                $slots[] = ['start' => $slot_start, 'end' => $slot_end];
            }
        }

        // if no availabilities are registered for this user, he is available all day long
        if (!$availabilities->count()) {
            $slots[] = ['start' => $day_start, 'end' => $day_end];
        }

        // prep return data
        $data = [
            'total_freetime'        => $day_start->diffInMinutes($day_end),
            'available_freetime'    => $free_min,
            'slots'                 => $slots
        ];

        return $data;
    }
}
