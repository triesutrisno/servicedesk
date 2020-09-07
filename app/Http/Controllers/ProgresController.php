<?php

namespace App\Http\Controllers;

use App\Progres;
use Illuminate\Http\Request;

class ProgresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $progres = Progres::all();
        return $progres;
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
     * @param  \App\Progres  $progres
     * @return \Illuminate\Http\Response
     */
    public function show(Progres $progres)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Progres  $progres
     * @return \Illuminate\Http\Response
     */
    public function edit(Progres $progres)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Progres  $progres
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Progres $progres)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Progres  $progres
     * @return \Illuminate\Http\Response
     */
    public function destroy(Progres $progres)
    {
        //
    }
}
