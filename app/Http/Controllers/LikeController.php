<?php

namespace App\Http\Controllers;

use App\Models\like;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    // like or unlike

    public function likeOrUnlike($id)
    {
        $offer = Offer::find($id);

        if(!$offer)
        {
            return response([
                'message' => 'Offer not found.'
            ], 403);
        }

        $like = $offer->likes()->where('user_id', auth()->user()->id)->first();

        // if not liked then like
        if(!$like)
        {
            Like::create([
                'offer_id' => $id,
                'user_id' => auth()->user()->id
            ]);

            return response([
                'message' => 'Liked'
            ], 200);
        }
        // else dislike it
        $like->delete();
        return response([
            'message' => 'Disliked'
        ], 200);
    }

    public function index()
    {

        $likes = DB::table("offers")->leftJoin("likes", function($join){
                $join->on("offers.id", "=", "likes.offer_id");
        })->get();

        $like = $likes->where('user_id',auth()->user()->id);

        if($like == null){
            return response([
                'offers' => 'Offer not found.'
            ], 403);
        }else{
            return response([
                'offers' => $like,
            ], 200);
        }

        // $likes = Like::orderBy('created_at', 'desc')->with('user:id,name')->where('user_id',auth()->user()->id)->get();
        // foreach ($likes as $like){
        //     $offer_id = $like['offer_id'];
        //     $likes->add(Offer::where('id',$offer_id)->get());
        // }

    }

    public function Unlike($id)
    {
        $offer = Offer::find($id);

        if(!$offer)
        {
            return response([
                'message' => 'Offer not found.'
            ], 403);
        }else{
            $like = $offer->likes()->where('user_id', auth()->user()->id)->first();

            $like->delete();
        }
        return response([
            'message' => 'Disliked'
        ], 200);
    }
}
