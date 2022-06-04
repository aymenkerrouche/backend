<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\offer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Sodium\add;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
            'offers' => Offer::orderBy('created_at', 'desc')->with('user:id,name')->with('likes', function($like){
                return $like->where('user_id', auth()->user()->id)
                    ->select('id', 'user_id', 'offer_id')->get();
            })->select('id','name','image', 'price','location','user_id','agency_id')
                ->get()
        ], 200);
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
            'name'=>'required',
            'logement_type'=>'required',
            'trading_type'=>'required',
            'rooms'=>'required',
            'price'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',

        ]);

        $request['user_id'] = auth()->user()->id;
        $request['agency_id'] = auth()->user()->id;

        $offer = Offer::create($request->all());
        return response([
            'message' => 'Offer created.',
            'offer' => $offer,
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
        $offer = Offer::find($id);

        if(!$offer)
        {
            return response([
                'message' => 'Offer not found.'
            ], 403);
        }
        $offer = Offer::where('id', $id)->with('user:id,name')->with('likes', function($like){
            return $like->where('user_id', auth()->user()->id)
                ->select('id', 'user_id', 'offer_id')->get();
        })->get();
        return response([
            'offer' => $offer
        ], 200);
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
        return response([
        'offers' => Offer::where('name', 'like','%'.$name.'%')
            ->orWhere('description', 'like','%'.$name.'%')
            ->orWhere('logement_type', 'like','%'.$name.'%')
            ->orWhere('trading_type', 'like','%'.$name.'%')
            ->orWhere('rooms', 'like','%'.$name.'%')
            ->orWhere('price', '>=','%'.$name.'%')
            ->orWhere('price', '<=','%'.$name.'%')
            ->orWhere('location', 'like','%'.$name.'%')
            ->orderBy('created_at', 'desc')->with('user:id,name')
            ->with('likes', function($like){
                return $like->where('user_id', auth()->user()->id)
                    ->select('id', 'user_id', 'offer_id')->get();
        })->select('id','name','image', 'price','location','user_id','agency_id')
            ->get()
    ], 200);;
    }

    public function searchPrice($small,$big)
    {
        if ($small and $big != -1){
            return response([
                'offers' => Offer::where('price','>=',$small,)->where('price','<=',$big,)
                    ->orderBy('price', 'asc')->with('user:id,name')
                    ->with('likes', function($like){
                        return $like->where('user_id', auth()->user()->id)
                            ->select('id', 'user_id', 'offer_id')->get();
                    })->select('id','name','image', 'price','location','user_id','agency_id')
                    ->get()
            ], 200);

        }
        if ($small == -1) {
            return response([
                'offers' => Offer::where('price', '<=', $big,)
                    ->orderBy('price', 'asc')->with('user:id,name')
                    ->with('likes', function ($like) {
                        return $like->where('user_id', auth()->user()->id)
                            ->select('id', 'user_id', 'offer_id')->get();
                    })->select('id', 'name', 'image', 'price', 'location', 'user_id', 'agency_id')
                    ->get()
            ], 200);
        }
        if($big == -1){
            return response([
                'offers' => Offer::where('price','>=',$small,)
                    ->orderBy('price', 'asc')->with('user:id,name')
                    ->with('likes', function($like){
                        return $like->where('user_id', auth()->user()->id)
                            ->select('id', 'user_id', 'offer_id')->get();
                    })->select('id','name','image', 'price','location','user_id','agency_id')
                    ->get()
            ], 200);
        }
    }

    public function agencyPhone($id)
    {
        $phone=Agency::where('agency_id', $id)->select('phone')->get();
        return $phone;
    }


    public function agencyOffers(): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {

        $offers = Offer::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->get();
        return response($offers);
    }

    public function getOfferId(){

        $offers = Offer::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->get();
        $offer = $offers->first();
        $id = $offer->id;
        return response([
            'id' => $id,
        ]);
    }

    public function recentOffers(): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {

        return response([
            'offers' => Offer::orderBy('created_at', 'desc')->with('user:id,name')->with('likes', function($like){
                return $like->where('user_id', auth()->user()->id)
                    ->select('id', 'user_id', 'offer_id')->get();
            })->select('id','name', 'price','image','location','user_id','agency_id')
                ->take(2)->get()
        ], 200);
    }

    public function randomOffers(): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {

        return response([
            'offers' => Offer::inRandomOrder()->limit(6)->with('user:id,name')->with('likes', function($like){
                return $like->where('user_id', auth()->user()->id)
                    ->select('id', 'user_id', 'offer_id')->get();
            })->select('id','name', 'price','location','image','user_id','agency_id')
                ->get()
        ], 200);
    }

}
