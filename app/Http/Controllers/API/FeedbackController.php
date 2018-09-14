<?php

namespace App\Http\Controllers\API;

use App\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
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
        $feedback_data  = $request->params['feedback'];
        $name           = $feedback_data['name'];
        $description    = $feedback_data['description'];
        $rating         = $feedback_data['rating'];

        $booking_id     = $request->params['bookingId'];
        $user_id        = $request->params['userId'];
        $substitute_id  = $request->params['substituteId'];

        $feedback = new Feedback();
        $feedback->name             = $name;
        $feedback->description      = $description;
        $feedback->booking_id       = $booking_id;
        $feedback->user_id          = $user_id;
        $feedback->substitute_id    = $substitute_id;
        $feedback->rating           = $rating;
        $feedback->save();

        return response()->json($request->params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feedback $feedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $feedback)
    {
        //
    }
}
