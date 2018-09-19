<?php

namespace App\Http\Controllers;

use App\Availability;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['hasTeam']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function show(Availability $availability)
    {
        // Get bookings ordered by most recent
        $bookings   = $availability->bookings()->orderBy('start')->get();
        $slots      = $this->getAvailableBookingSlots($availability);

        $user = auth()->user();
        $date = Carbon::parse('30.07.2018');
        $freetime   = $this->getAvailableSlots($user, $date);

        return view('availability.show', [
            'availability' => $availability,
            'bookings'      => $bookings,
            'slots'         => $slots,
            'freetime'      => $freetime
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function edit(Availability $availability)
    {
        return view('availability.edit', ['availability' => $availability]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Availability $availability)
    {
        $availability->start    = Carbon::parse($request->date_start);
        $availability->end      = Carbon::parse($request->date_end);
        $availability->save();

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function destroy(Availability $availability)
    {
        $availability->delete();
        return redirect()->route('users.index');
    }

    public function search()
    {
        $this->authorize('search', 'App\Availability');

        return view('availability.search');
    }

    /**
     * Look through booking related to an availability
     * to determine the available slots
     *
     * @param Availability $availability
     * @return array
     */
    public function getAvailableBookingSlots(Availability $availability)
    {
        // get related bookings
        $bookings   = $availability->bookings()->orderBy('start')->get();

        // init
        $start      = $availability->start->copy();
        $busy_min   = 0;
        $slots      = [];

        // loop through the associated bookings
        foreach ($bookings as $key => $booking) {

            // store each booking duration
            if ($booking->start->lt($availability->start)) {
                $busy_min += $booking->end->diffInMinutes($availability->start);
            } elseif ($booking->end->gt($availability->end)) {
                $busy_min += $booking->start->diffInMinutes($availability->end);
            } else {
                $busy_min += $booking->start->diffInMinutes($booking->end);
            }

            // If this is the last booking and it is exactly the same length, exit early
            if ($key == $bookings->count() &&
                $booking->start->equaltTo($availability->start) &&
                $booking->end->equaltTo($availability->end)
            ) {
                break;
            }

            // register available slots
            if ($booking->start->equalTo($start)) {
                $start->addMinutes($booking->start->diffInMinutes($booking->end));
            } else {
                $slot_start = $start->copy();
                $slot_end   = $start->copy()->addMinutes($start->diffInMinutes($booking->start));
                $start      = $booking->end;

                $slots[] = ['start' => $slot_start, 'end' => $slot_end];
            }

            // if the last booking ends before the availability, retrieve the ending slot
            if ($key == $bookings->count() - 1 && $booking->end->lt($availability->end)) {
                $slot_start = $booking->end->copy();
                $slot_end   = $slot_start->copy()->addMinutes($booking->end->diffInMinutes($availability->end));

                $slots[] = ['start' => $slot_start, 'end' => $slot_end];
            }
        }

        // prep return data
        $data = [
            'availability_duration' => $availability->start->diffInMinutes($availability->end),
            'bookings_duration'     => $busy_min,
            'slots'                 => $slots
        ];

        return $data;
    }


    /**
     * Look through the availabilities for a specific user and date
     * and return its free time
     *
     * @param User $user
     * @param Carbon $date
     * @return array
     */
    public function getAvailableSlots(User $user, Carbon $date)
    {
        $day_start  = $date->copy()->hour(6);
        $day_end    = $date->copy()->hour(18);

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

        // prep return data
        $data = [
            'total_freetime'        => $day_start->diffInMinutes($day_end),
            'available_freetime'    => $free_min,
            'slots'                 => $slots
        ];

        return $data;
    }

}
