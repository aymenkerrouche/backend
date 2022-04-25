<?php

namespace App\Http\Controllers;

use App\Models\offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Offer::all();
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
        $request->validate([
            // 'id'=>'required',
            'name'=>'required',
            'logement_type'=>'required',
            'trading_type'=>'required',
            'rooms'=>'required',
            // 'bathroom'=>'required',
            'price'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
            'agency_id'=>'required',
        ]);
        return Offer::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Offer::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(offer $offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $offer = Offer::find($id);
        $offer->update($request->all());
        return $offer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\offer  $offer
     * @return \Illuminate\Http\Response|int
     */
    public function destroy($id)
    {
        return Offer::destroy($id);
    }

    /**
     * search the specified resource from storage.
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Offer::where('name', 'like','%'.$name.'%')->get();
    }


}
