<?php

namespace App\Http\Controllers;

use App\Nextnumber;
use Illuminate\Http\Request;

class NextnumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nextNumber = Nextnumber::all();
        return $nextNumber;
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
     * @param  \App\Nextnumber  $nextnumber
     * @return \Illuminate\Http\Response
     */
    public function show(Nextnumber $nextnumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Nextnumber  $nextnumber
     * @return \Illuminate\Http\Response
     */
    public function edit(Nextnumber $nextnumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nextnumber  $nextnumber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nextnumber $nextnumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nextnumber  $nextnumber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nextnumber $nextnumber)
    {
        //
    }
}
