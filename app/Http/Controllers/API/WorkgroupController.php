<?php

namespace App\Http\Controllers\API;

use App\Workgroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkgroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workgroups = Workgroup::all();
        return response()->json($workgroups);
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
     * @param  \App\Workgroup  $workgroup
     * @return \Illuminate\Http\Response
     */
    public function show(Workgroup $workgroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Workgroup  $workgroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Workgroup $workgroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Workgroup  $workgroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workgroup $workgroup)
    {
        //
    }
}
