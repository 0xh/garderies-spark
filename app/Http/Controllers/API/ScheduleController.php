<?php

namespace App\Http\Controllers\API;

use App\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->params;

        $start  = Carbon::parse($params['hour_start']);
        $end    = Carbon::parse($params['hour_end']);

        $schedule = new Schedule();
        $schedule->start    = $start;
        $schedule->end      = $end;
        $schedule->user_id  = $params['resource'];
        $schedule->save();

        return response()->json($schedule);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        $date           = Carbon::parse($request->params['date']);
        $hour_start     = Carbon::parse($request->params['hour_start']);
        $hour_end       = Carbon::parse($request->params['hour_end']);
        $resourceId     = $request->params['resource'];

        $start  = Carbon::parse($date->format('d.m.Y') . ' ' . $hour_start->format('H:i'));
        $end    = Carbon::parse($date->format('d.m.Y') . ' ' . $hour_end->format('H:i'));

        $schedule->start    = $start;
        $schedule->end      = $end;
        $schedule->user_id  = $resourceId;
        $schedule->save();

        return response()->json($date);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response()->json($schedule);
    }
}
