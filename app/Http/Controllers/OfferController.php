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
        $infos = $request->validate([
            'name'=>'required',
            'logement_type'=>'required',
            'trading_type'=>'required',
            'rooms'=>'required',
            'price'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',

        ]);

        $offer = Offer::create([
            'name'=>$infos['name'],
            'logement_type'=>$infos['logement_type'],
            'trading_type'=>$infos['trading_type'],
            'rooms'=>$infos['rooms'],
            'price'=>$infos['price'],
            'latitude'=>$infos['latitude'],
            'longitude'=>$infos['longitude'],
            'agency_id'=> auth()->user()->id,
        ]);
        return response([
            'message' => 'Offer created.',
            'post' => $offer,
        ], 200);;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response([
            'offer' => Offer::where('id', $id)->get()
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(offer $offer,)
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

        if(!$offer)
        {
            return response([
                'message' => 'Offer not found.'
            ], 403);
        }

        if($offer->agency_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $offer->update($request->all());
        return response([
            'message' => 'Offer updated.',
            'post' => $offer,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\offer  $offer
     * @return \Illuminate\Http\Response|int
     */
    public function destroy($id)
    {
        $offer = Offer::find($id);

        if(!$offer)
        {
            return response([
                'message' => 'Offer not found.'
            ], 403);
        }

        if($offer->agency_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }
        $offer->delete();
        return response([
            'message' => 'Offer deleted.'
        ], 200);
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
