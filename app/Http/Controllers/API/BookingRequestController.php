<?php

namespace App\Http\Controllers\API;

use App\Availability;
use App\Booking;
use App\BookingRequest;
use App\Notifications\BookingRequestAcceptedNotification;
use App\Notifications\BookingRequestNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingRequest as BookingRequestResource;
use Laravel\Spark\Contracts\Repositories\NotificationRepository;

class BookingRequestController extends Controller
{
    private $notifications;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notifications = $notificationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user   = User::find($request->uid);
        $team   = $user->currentTeam();

        $requests = $team->bookingRequests()->where('status', BookingRequest::STATUS_PENDING)
            ->where('start', '>', now())
            ->get();
        return response()->json([
            'count'     => $requests->count(),
            'requests'  => BookingRequestResource::collection($requests)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->employee) {
            $authUser       = User::find($request->employee);
        } else {
            $authUser       = User::find($request->uid);
        }

        $availabilities = $request->availabilities;
        $start          = Carbon::parse($request->date_start);
        $end            = Carbon::parse($request->date_end);

        // Create a request group identifier
        $request_group_ID   = str_random();

        // Loop through availabilities
        foreach ($availabilities as $availability) {
            $substitute = User::find($availability['user_id']);

            $bookingRequest = new BookingRequest();
            $bookingRequest->request_group      = $request_group_ID;
            $bookingRequest->availability_id    = $availability['id'];
            $bookingRequest->substitute_id      = $substitute->id;
            $bookingRequest->user_id            = $authUser->id;
            $bookingRequest->nursery_id         = $request->nursery;
            $bookingRequest->workgroup_id       = $request->workgroup;
            $bookingRequest->purpose_id         = $request->purpose;
            $bookingRequest->message            = $request->message;
            $bookingRequest->start              = $start;
            $bookingRequest->end                = $end;
            $bookingRequest->save();


            // Send notifications
            $substitute->notify(new BookingRequestNotification($bookingRequest));
        }

        return response()->json([
            'status'            => 'received',
            'start'             => $start,
            'end'               => $end,
            'availabilities'    => $availabilities,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingRequest $bookingRequest)
    {
        $bookingRequest->delete();

        return response()->json([
            'status'    => 'Booking request deleted',
            'redirect'  => route('booking-requests.index')
        ]);
    }

    public function approve(BookingRequest $bookingRequest)
    {
        // If we have a booking request
        if (!$bookingRequest->id && $bookingRequest->status != BookingRequest::STATUS_PENDING) {
            return false;
        }

        // Create the booking object
        $booking = new Booking();
        $booking->request_id        = $bookingRequest->id;
        $booking->user_id           = $bookingRequest->user->id;
        $booking->substitute_id     = $bookingRequest->substitute->id;
        $booking->nursery_id        = $bookingRequest->nursery->id;

        $booking->start = ($bookingRequest->start->gte($bookingRequest->availability->start))
            ? $bookingRequest->start
            : $bookingRequest->availability->start;

        $booking->end = ($bookingRequest->end->gte($bookingRequest->availability->end))
            ? $bookingRequest->availability->end
            : $bookingRequest->end;

        $booking->status            = Booking::STATUS_APPROVED; //TODO: will depends on the process
        $booking->save();

        // Update the availability status
        $availability = $bookingRequest->availability;

        /**
         * If the booking takes up more than half of the availability
         * we can assume the day is filled
         */
        $bookings_duration = 0;
        $related_bookings = $availability->bookings;
        foreach ($related_bookings as $booking) {
            $bookings_duration += $booking->start->diffInHours($booking->end);
        }

        $availability_duration  = $availability->start->diffInHours($availability->end);

        // determine if the days is considered filled with bookings
        $day_filled = false;
        if ($availability_duration > $bookings_duration) {
            if (($availability_duration - $bookings_duration) < ($availability_duration / 2)) {
                $day_filled = true;
            }
        } else { $day_filled = true; }

        // select the correct status
        if ($day_filled) {
            $availability->status = Availability::STATUS_BOOKED;
        } else {
            $availability->status = Availability::STATUS_PARTIALLY_BOOKED;
        }
        $availability->save();

        // update the booking request
        $bookingRequest->status = BookingRequest::STATUS_APPROVED;
        $bookingRequest->save();

        // Update the associated booking requests
        BookingRequest::where('request_group', $bookingRequest->request_group)
            ->where('id', '!=', $bookingRequest->id)
            ->update(['status' => BookingRequest::STATUS_DENIED]);

        // Notify the employee and substitute
        $bookingRequest->user->notify(new BookingRequestAcceptedNotification($bookingRequest));
        $bookingRequest->substitute->notify(new BookingRequestAcceptedNotification($bookingRequest));

        return response()->json([
            'status'    => 'Booking request approved'
        ]);
    }
}
