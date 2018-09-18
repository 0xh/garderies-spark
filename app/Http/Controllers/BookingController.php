<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingRequest;
use App\Http\Resources\Availability;
use App\Nursery;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\CalendarLinks\Link;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['subscribed', 'hasTeam']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get future bookings
        $bookings = Booking::where('start', '>=', now())
            ->orderBy('status', 'desc')
            ->orderBy('start')
            ->get();

        // Get archived bookings
        $bookings_archive = Booking::where('status', Booking::STATUS_ARCHIVED)
            ->orderBy('start')
            ->get();

        return view('booking.index', [
            'bookings'          => $bookings,
            'bookings_archive'  => $bookings_archive,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $team = $user->currentTeam();

        // Get users except for the ID 1, for demo purposes
        $users  = $team->users()->where('id', '!=', $user->id)->orderBy('name')->get();
        // Get nuseries
        $nurseries  = $team->nurseries()->orderBy('name')->get();

        return view('booking.create', ['users' => $users, 'nurseries' => $nurseries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Validate the request
         * TODO: check if we can simplify this by using the default validations strings
         */
        Validator::make($request->all(), [
            'user'          => 'required',
            'substitute'    => 'required|not_in:' . $request->user,
            'nursery'       => 'required',
            'date_start'    => 'required|date|after:yesterday',
            'date_end'      => 'required|date|after:date_start',
        ], [
            'user.required'         => 'Veuillez sélectionner un employé.',
            'substitute.required'   => 'Veuillez sélectionner un remplaçant.',
            'substitute.not_in'     => 'Veuillez sélectionner un remplaçant différent de l\'employé.',
            'nursery.required'      => 'Veuillez sélectionner une garderie.',
            'date_start.required'   => 'Veuillez sélectionner une date de début.',
            'date_start.after'      => 'Veuillez sélectionner une date à partie d\'aujourd\'hui.',
            'date_end.required'     => 'Veuillez sélectionner une date de fin.',
            'date_end.after'        => 'La date de fin doit être après la date de début.',
        ])->validate();

        // Save the new object
        $booking = new Booking();
        $booking->user_id       = $request->user;
        $booking->substitute_id = $request->substitute;
        $booking->nursery_id    = $request->nursery;
        $booking->start         = Carbon::parse($request->date_start);
        $booking->end           = Carbon::parse($request->date_end);
        $booking->save();

        return redirect()->route('bookings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        /**
         * Calculate the difference between the original booking request and the availability
         */
        $matching_pct = 0;
        $matching_start_pct = 0;
        $matching_end_pct = 0;

        // check if a booking request is associated to this booking
        if ($booking->request) {
            $request_start      = $booking->request->start;
            $request_end        = $booking->request->end;
            $availability_start = $booking->request->availability->start;
            $availability_end   = $booking->request->availability->end;

            // Durations
            $request_duration       = $request_start->diffInMinutes($request_end);
            $availability_duration  = $availability_start->diffInMinutes($availability_end);

            // Differences between starting and ending hours
            $booking_delay_start = ($request_start->lte($availability_start))
                ? $request_start->diffInMinutes($availability_start)
                : 0;
            $booking_delay_end = ($request_end->gte($availability_end))
                ? $request_end->diffInMinutes($availability_end)
                : 0;

            // Percentages calculation
            $matching_pct = ($availability_duration * 100) / $request_duration;
            $matching_start_pct = ($booking_delay_start * 100) / $request_duration;
            $matching_end_pct = ($booking_delay_end * 100) / $request_duration;
        }

        // calculate the booking duration in hours, with 2 decimals
        $booking_duration = $booking->start->diffInMinutes($booking->end);
        $booking_duration = number_format($booking_duration / 60, 2);

        // retrieve the booking's feedbacks
        $feedbacks = $booking->feedbacks;

        // generate calendar link
        $calendar_link = new Link('Remplacement', $booking->start, $booking->end);
        $calendar_link->description('Remplacement de ' . $booking->user->name . ', à la garderie ' . $booking->nursery->name);
        $calendar_link->address($booking->nursery->address . ', ' . $booking->nursery->post_code . ' ' . $booking->nursery->city);

        return view('booking.show', [
            'booking'               => $booking,
            'matching_pct'          => $matching_pct,
            'matching_start_pct'    => $matching_start_pct,
            'matching_end_pct'      => $matching_end_pct,
            'booking_duration'      => $booking_duration,
            'feedbacks'             => $feedbacks,
            'calendar_link'         => $calendar_link
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        return view('booking.edit', ['booking' => $booking]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        $start  = Carbon::parse($request->date_start);
        $end    = Carbon::parse($request->date_end);

        $booking->start = $start;
        $booking->end   = $end;
        $booking->save();

        return redirect()->route('bookings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
