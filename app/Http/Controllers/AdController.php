<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Nursery;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function __construct()
    {
        $this->middleware([]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Nursery $nursery)
    {
        return view('nursery.ads', [
            'nursery'   => $nursery,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Nursery $nursery)
    {
        return view('ad.create', [
            'nursery'    => $nursery
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
        $ad                 = new Ad();
        $ad->title          = $request->title;
        $ad->description    = $request->description;
        $ad->nursery_id     = $request->nursery;
        $ad->save();

        $nursery = Nursery::find($request->nursery);

        return redirect()->route('nurseries.ads', $nursery);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function show(Ad $ad)
    {
        return view('ad.show', ['ad' => $ad]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function edit(Ad $ad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ad $ad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ad $ad)
    {
        $nursery = $ad->nursery;
        $ad->delete();

        return redirect()->route('nurseries.ads', $nursery);
    }
}
