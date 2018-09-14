<?php

namespace App\Http\Controllers\API;

use App\Availability;
use App\Http\Controllers\Controller;
use App\Http\Resources\Availability as AvailabilityResource;
use App\User;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

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
        $availabilities = Availability::all();
        return $availabilities; //TODO: check where this is called, must change the return value
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userID = $request->params['userID'];
        $user   = User::find($userID);
        $event  = $request->params['event'];
        $default_duration = config('nursery.default_availability_duration');
        
        $start  = Carbon::parse($event['start']);
        $end    = Carbon::parse($event['end']);

        // get business hours
        $opening_time = $start->copy()->hour(config('nursery.opening_time'))->minute(0);
        $closing_time = $end->copy()->hour(config('nursery.closing_time'))->minute(0);

        // if no starting hour is passed (thanks momentJS), set it to 8
        if (!$start->hour) {
            $start = $opening_time;
            $end = $start->copy()->addHours($default_duration);
        }

        if ($start->lt($opening_time)) { $start = $opening_time; $end = $start->copy()->addHours($default_duration); }
        if ($start->gte($closing_time)) { $start = $closing_time->copy()->subHours($default_duration); }
        if ($end->gt($closing_time)) { $end = $closing_time; $start = $end->copy()->subHours($default_duration); }

        $availabilities = Availability::whereYear('start', $start->format('Y'))
            ->whereMonth('start', $start->format('m'))
            ->whereDay('start', $start->format('d'))
            ->where('user_id', $userID)
            ->get();

        $isOverlapping  = false;
        $overlapStart   = null;
        $overlapEnd     = null;

        foreach ($availabilities as $availability) {
            if (
                ($start->lte($availability->start) && $end->gt($availability->start) && $end->lte($availability->end)) || // overlapse before
                ($start->gte($availability->start) && $start->lt($availability->end) && $end->gte($availability->end)) || // overlapse after
                ($start->gte($availability->start) && $end->lte($availability->end)) || // between
                ($end->lt($availability->start) && $end->gt($availability->end)) // total overlapse
            ) {
                $isOverlapping = true;
            }
        }
        
        if ($isOverlapping) {
            $freetime = UserController::getAvailableSlots($user, $start);
            
            if ($freetime['available_freetime'] >= $default_duration) {
                foreach ($freetime['slots'] as $free) {
                    $freestart  = Carbon::parse($free['start']);
                    $freeend    = Carbon::parse($free['end']);
                    
                    if ($freestart->diffInHours($freeend) >= 2) {
                        if ($start->diffInHours($freestart) > $start->diffInHours($freeend)) {
                            $start = $freeend->copy()->subHours($default_duration);
                            $end = $freeend;
                        } else {
                            $start = $freestart;
                            $end = $freestart->copy()->addHours($default_duration);
                        }
                        
                        $isOverlapping = false;
                        break;
                    }
                }
            }
        }

        $availability = null;
        if (!$isOverlapping) {
            $availability = new Availability();
            $availability->start    = $start;
            $availability->end      = $end;
            $availability->user_id  = $userID;
            $availability->save();
        }

        return response()->json([
            'status'        => UserController::getAvailableSlots($user, $start),
            'isOverlapping' => $isOverlapping,
            'event'         => $availability,
            'id'            => isset($availability) ? $availability->id : null
        ]);
    }
    
    function rangesNotOverlapOpen($start_time1, $end_time1, $start_time2, $end_time2)
    {
        $utc        = new DateTimeZone('UTC');
        
        $start1     = new DateTime($start_time1,$utc);
        $end1       = new DateTime($end_time1,$utc);
        if ($end1 < $start1) { throw new Exception('Range is negative.'); }
        
        $start2     = new DateTime($start_time2,$utc);
        $end2       = new DateTime($end_time2,$utc);
        if ($end2 < $start2) { throw new Exception('Range is negative.'); }
        
        return ($end1 <= $start2) || ($end2 <= $start1);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Availability $availability
     * @return \Illuminate\Http\Response
     */
    public function show(Availability $availability)
    {
        //
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Availability $availability
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Availability $availability)
    {
        $start  = Carbon::parse($request->params['start']);
        $end    = Carbon::parse($request->params['end']);
        
        //TODO: add constraint check
        
        $availability->start = $start;
        $availability->end = $end;
        $availability->save();
        
        return response('Availability updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Availability $availability
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function destroy(Availability $availability)
    {
        //TODO: return a boolean to do a check on the front side
        if ($availability) {
            $availability->delete();
            return response('Availability destroyed');
        }
        
        return response('Availability not destroyed');
    }
    
    /**
     * Show availabilities for a specific user
     *
     * @param \App\User $user
     * @param Request $request
     * @return array
     */
    public function showForUser(\App\User $user, Request $request)
    {
        // Retrieve user availabilities, constrains to start and end paramaters passed from fullcalendar
        $availabilities = $user->availabilities()
            ->where('start', '>=', $request->start)
            ->where('end', '<=', $request->end)
            ->get();
        
        // New array for formatted data
        $availabilities_formatted = [];
        
        $colors = ['#3a87ad', '#607d8b', '#fbc02d', '#666666'];
        
        // Loop through each object
        foreach ($availabilities as $availability) {
            
            // See fullcalendar doc for format
            $availabilities_formatted[] = [
                'id'        => $availability->id,
                'title'     => 'Disponible',
                'start'     => $availability->start->toDateTimeString(),
                'end'       => $availability->end->toDateTimeString(),
                'status'    => $availability->status,
                'color'     => $colors[$availability->status],
                'rendering' => ($availability->status == Availability::STATUS_UNTOUCHED) ? '' : 'background',
                'type'      => 'availability'
            ];
        }
        
        return $availabilities_formatted;
    }
    
    /**
     * Search for availabilities
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        global $date_start, $date_end;

        $user   = User::find($request->uid);
        $team   = $user->currentTeam();

        $ext_search     = ($request->extended == 'true') ? true : false ;
        $date_start     = null;
        $date_end       = null;
        
        // check if all the inputs are presents
        if ($request->day_start && $request->hour_start && $request->hour_end) {
            // retrieve the starting day
            $day_start = Carbon::parse($request->day_start)->format('d.m.Y');
            // starting hour
            $hour_start = Carbon::parse($request->hour_start, 'Europe/Zurich')->format('H:i');
            // ending hour
            $hour_end = Carbon::parse($request->hour_end, 'Europe/Zurich')->format('H:i');
            
            // recompose the date object through Carbon
            $date_start = Carbon::parse($day_start . ' ' . $hour_start);
            $date_end = Carbon::parse($day_start . ' ' . $hour_end);
        }
        
        // if the search perimeter is correctly defined, proceed
        if ($team && $date_start && $date_end) {

            // extend the hour search parameter to allow for user errors
            $date_start_extended    = $date_start->copy()->addHour(1);
            $date_end_extended      = $date_end->copy()->subHour(1);

            global $date_start, $date_end;
            
            // availabilities request
            if (!$ext_search) {
                $collection = Availability::select('availabilities.*')
                ->where('availabilities.user_id', '!=', $user->id)
                ->where([
                    ['start', '<=', $date_start_extended],
                    ['end', '>=', $date_end_extended],
                    ['status', '!=', Availability::STATUS_ARCHIVED],
                    ['status', '!=', Availability::STATUS_BOOKED] // complete
                ])
                ->join('users', 'users.id', '=', 'availabilities.user_id')
                ->join('team_users', 'team_users.user_id', '=', 'users.id')
                ->where('team_users.team_id', $team->id)
                ->orderBy('start')
                ->get();
            } else {
                $collection = Availability::select('availabilities.*')
                ->with('networks')
                ->join('users', 'users.id', '=', 'availabilities.user_id')
                ->join('team_users', 'team_users.user_id', '=', 'users.id')
                ->where('team_users.team_id', $team->id)
                ->where(function ($query) {
                    global $date_start, $date_end;

                    $query->where([
                        ['start', '<=', $date_start],
                        ['end', '>=', $date_end],
                        ['status', '!=', Availability::STATUS_ARCHIVED],
                        ['status', '!=', Availability::STATUS_BOOKED] // complete
                    ])->orWhere([
                        ['start', '<=', $date_start],
                        ['end', '<', $date_end],
                        ['end', '>', $date_start],
                        ['status', '!=', Availability::STATUS_ARCHIVED],
                        ['status', '!=', Availability::STATUS_BOOKED] // partial (not until the end)
                    ])->orWhere([
                        ['start', '>', $date_start],
                        ['end', '>=', $date_end],
                        ['start', '<', $date_end],
                        ['status', '!=', Availability::STATUS_ARCHIVED],
                        ['status', '!=', Availability::STATUS_BOOKED] // partial (not since beginning)
                    ])->orWhere([
                        ['start', '>', $date_start],
                        ['end', '<', $date_end],
                        ['status', '!=', Availability::STATUS_ARCHIVED],
                        ['status', '!=', Availability::STATUS_BOOKED] // partial (not since beginning nor end)
                    ]);
                })
                ->where('availabilities.user_id', '!=', $user->id)
                ->orderBy('start')
                ->get();
            }

            // determine the matching between the slot and the request
            $collection->each(function ($item, $key) {
                global $collection, $date_start, $date_end;
                
                if ($item->start <= $date_start && $item->end >= $date_end) {
                    $item->matching = 'complete';
                } elseif ($item->start <= $date_start && $item->end < $date_end && $item->end > $date_start) {
                    $item->matching = 'partial';
                } elseif ($item->start > $date_start && $item->end >= $date_end && $item->start < $date_end) {
                    $item->matching = 'partial';
                } elseif ($item->start > $date_start && $item->end < $date_end) {
                    $item->matching = 'partial';
                } else {
                    $item->matching = 'none';
                }
            });
            
        } else {
            $collection = new Collection();
        }

        $collection = $collection->sortBy('matching');

        return AvailabilityResource::collection($collection);
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

}
