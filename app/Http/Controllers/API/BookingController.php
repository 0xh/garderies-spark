<?php

namespace App\Http\Controllers\API;

use App\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status     = $request->status;
        $nursery    = $request->nursery;

        // Retrieve bookings and their relations
        $bookings = Booking::select(
                'bookings.*',
                DB::raw("DATE_FORMAT(bookings.start, '%H:%i') as start_time"),
                DB::raw("DATE_FORMAT(bookings.end, '%H:%i') as end_time")
            )
            ->join('users', 'users.id', 'bookings.user_id')->with('user')
            ->join('users as substitutes', 'substitutes.id', 'bookings.substitute_id')->with('substitute')
            ->join('nurseries', 'nurseries.id', 'bookings.nursery_id')->with('nursery');
    
        if ($nursery) {
            $bookings->where('nurseries.id', '=', $nursery);
        }

        // Filter by status
        if ($status) {
            $bookings->where('bookings.status', $status);
        } else {
            $bookings->where('start', '>=', now());
        }

        if ($status == Booking::STATUS_ARCHIVED) {
            $bookings->orderBy('start', 'desc');
        }

        // Handle user search
        if ($request->exists('filter')) {
            $bookings->where(function($q) use($request) {
                $value = "%{$request->filter}%";
                $q->orWhere('users.name', 'like', $value)
                    ->orWhere('substitutes.name', 'like', $value)
                    ->orWhere('nurseries.name', 'like', $value);
            });
        }

        // Handle column sorting
        if ($request->get('sort')) {
            list($sortCol, $sortDir) = explode('|', $request->get('sort'));
            switch ($sortCol) {
                case 'start':
                    $bookings->orderBy('start_time', $sortDir);
                    break;
                case 'end':
                    $bookings->orderBy('end_time', $sortDir);
                    break;
                default:
                    $bookings->orderBy($sortCol, $sortDir);
                    break;
            }
        } else {
            $bookings->orderBy('bookings.start', 'asc');
        }

        // Pagination
        $perPage    = $request->has('per_page') ? (int) $request->per_page : null;

        // Paginate data
        $data       = $bookings->paginate($perPage);

        return response()->json($data);
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
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json([
            'status'    => 'Booking deleted',
            'redirect'  => route('bookings.index')
        ]);
    }

    public function approve(Booking $booking)
    {
        $booking->status = Booking::STATUS_APPROVED;
        $booking->save();

        return response()->json([
            'status'    => 'Booking approved'
        ]);
    }

    /**
     * @param \App\User $user
     * @param Request $request
     * @return array
     */
    public function showForUser(\App\User $user, Request $request)
    {
        // TODO: retrieve only validated bookings

        // Retrieve user availabilities, constrains to start and end paramaters passed from fullcalendar
        $bookings = $user->bookings()
            ->where('start', '>=', $request->start)
            ->where('end', '<=', $request->end)
            ->get();

        // New array for formatted data
        $bookings_formatted = [];

        // Loop through each object
        foreach ($bookings as $booking) {

            // See fullcalendar doc for format
            $bookings_formatted[] = [
                'id'        => 'b_' . $booking->id,
                'title'     => 'En remplacement',
                'start'     => $booking->start->toDateTimeString(),
                'end'       => $booking->end->toDateTimeString(),
                'status'    => $booking->status,
                'type'      => 'booking'
            ];
        }

        return $bookings_formatted;
    }
}
